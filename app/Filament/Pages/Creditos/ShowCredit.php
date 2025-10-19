<?php

namespace App\Filament\Pages\Creditos;

use App\Enums\PageGroupType;
use App\Models\Credit;
use App\Models\Payment;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Support\Enums\Width;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;

class ShowCredit extends Page implements HasTable
{
    use InteractsWithTable;

    public Credit $record;

    protected static bool $shouldRegisterNavigation = false;

    protected string $view = 'filament.pages.creditos.show';

    protected static string|null|\BackedEnum $navigationIcon = 'heroicon-o-document-text';

    protected static string|null|\UnitEnum $navigationGroup = PageGroupType::CREDIT->value;

    protected static ?string $slug = 'creditos/{recordId}';

    public function mount(int $recordId): void
    {
        $this->record = Credit::findOrFail($recordId)->load('payments');
    }

    public static function getNavigationLabel(): string
    {
        return 'Ver Crédito';
    }

    public function getTitle(): string|Htmlable
    {
        return "Crédito #{$this->record->reference_code}";
    }

    public function getHeading(): string|Htmlable
    {
        return '';
    }

    public function getMaxContentWidth(): Width
    {
        return Width::ScreenTwoExtraLarge;
    }

    protected function getHeaderActions(): array
    {
        $pagosData = $this->record->getPagosData();
        $saldoRestante = $pagosData['cuotas_restantes'] ?? 0;

        $actions = [];
        // dd($saldoRestante);
        // Solo mostrar si hay saldo restante
        if ($saldoRestante > 0) {
            $actions[] = Action::make('pagar_cuota_siguiente')
                ->label('Proxima Cuota')
                ->icon('heroicon-o-bolt')
                ->color('success')
                ->requiresConfirmation()
                ->modalHeading(function () {
                    $pagosData = $this->record->getPagosData();
                    $cuotaActual = $pagosData['cuota_siguiente'] ?? null;

                    return $cuotaActual
                        ? 'Pagar Cuota #'.$cuotaActual['periodo']
                        : 'Pagar Cuota';
                })
                ->modalDescription(function () {
                    $pagosData = $this->record->getPagosData();
                    $cuotaActual = $pagosData['cuota_siguiente'] ?? null;

                    if (! $cuotaActual) {
                        return 'No hay cuotas pendientes.';
                    }

                    return 'Monto de la cuota: $'.number_format($cuotaActual['cuota'], 2).
                        ' (Interés: $'.number_format($cuotaActual['interes'], 2).
                        ' + Capital: $'.number_format($cuotaActual['amortizacion'], 2).')';
                })
                ->modalIcon('heroicon-o-currency-dollar')
                ->modalSubmitActionLabel('Confirmar Pago')
                ->action(function () {
                    $pagosData = $this->record->getPagosData();
                    $cuotaActual = $pagosData['cuota_siguiente'] ?? null;

                    if (! $cuotaActual) {
                        Notification::make()
                            ->title('Error')
                            ->body('No hay cuotas pendientes para pagar.')
                            ->danger()
                            ->send();

                        return;
                    }

                    $this->createPayment([
                        'amount' => $cuotaActual['cuota'],
                        'payment_date' => now(),
                        'status' => 'completed',
                    ]);
                })
                ->tooltip('Pagar automáticamente la cuota propuesta');

            $actions[] = Action::make('create_payment')
                ->label('Registrar Pago')
                ->icon('heroicon-o-plus-circle')
                ->color('success')
                ->modalHeading('Registrar Nuevo Pago')
                ->modalDescription('Ingresa los datos del pago realizado')
                ->modalSubmitActionLabel('Guardar Pago')
                ->modalCancelActionLabel('Cancelar')
                ->modalWidth('2xl')
                ->form($this->getPaymentFormSchema())
                ->action(function (array $data) {
                    $this->createPayment($data);
                });
        }

        // Solo mostrar editar si no hay pagos
        if ($this->record->payments()->count() === 0) {
            $actions[] = Action::make('edit')
                ->label('Editar')
                ->icon('heroicon-o-pencil')
                ->color('warning')
                ->url(fn (): string => EditCredit::getUrl(['recordId' => $this->record->id]))
                ->tooltip('Editar los datos del crédito');
        }

        return $actions;
    }

    protected function getActions(): array
    {
        return [
            Action::make('createPayment')
                ->label('Registrar Pago')
                ->icon('heroicon-o-plus-circle')
                ->color('success')
                ->modalHeading('Registrar Nuevo Pago')
                ->modalDescription('Ingresa los datos del pago realizado')
                ->modalSubmitActionLabel('Guardar Pago')
                ->modalCancelActionLabel('Cancelar')
                ->modalWidth('2xl')
                ->form($this->getPaymentFormSchema())
                ->action(function (array $data) {
                    $this->createPayment($data);
                }),
        ];
    }

    /**
     * Schema del formulario de pagos
     */
    protected function getPaymentFormSchema(): array
    {
        $record = $this->record;

        return [
            Section::make('Información del Pago')
                ->schema([
                    Grid::make(2)
                        ->schema([
                            DatePicker::make('payment_date')
                                ->label('Fecha de Pago')
                                ->required()
                                ->default(now())
                                ->maxDate(now())
                                ->native(false)
                                ->displayFormat('d/m/Y')
                                ->closeOnDateSelection(),

                            Select::make('status')
                                ->label('Estado')
                                ->options([
                                    'completed' => 'Completado',
                                    'pending' => 'Pendiente',
                                ])
                                ->default('pending')
                                ->required()
                                ->native(false),
                        ]),
                ])->collapsible(),

            Section::make('Distribución del Pago')
                ->description('Puedes ingresar el monto total o dividirlo entre capital e interés')
                ->schema([
                    // 🔘 Selector de modo
                    ToggleButtons::make('payment_mode')
                        ->label('Modo de ingreso')
                        ->options([
                            'total' => 'Monto Total',
                            'detalle' => 'Dividir entre Capital e Interés',
                        ])
                        ->default('total')
                        ->inline()
                        ->reactive(),

                    // Campos de entrada
                    Grid::make(1)
                        ->schema([
                            // MODO: MONTO TOTAL
                            TextInput::make('amount')
                                ->label('Monto Total del Pago')
                                ->numeric()
                                ->prefix('$')
                                ->minValue(0.01)
                                ->step(0.01)
                                ->columnSpanFull()
                                ->visible(fn (Get $get) => $get('payment_mode') !== 'detalle')
                                ->live(onBlur: true)
                                ->afterStateUpdated(function (Get $get, Set $set, ?string $state) {
                                    static $isCalculating = false;
                                    if ($isCalculating) {
                                        return;
                                    }
                                    $isCalculating = true;
                                    try {
                                        $this->record->calculatePaymentDistribution($get, $set, ['amount' => (float) ($state ?? 0)]);
                                    } finally {
                                        $isCalculating = false;
                                    }
                                }),

                            // MODO: DETALLE CAPITAL + INTERÉS
                            Group::make()
                                ->visible(fn (Get $get) => $get('payment_mode') !== 'total')
                                ->schema([
                                    Grid::make(2)
                                        ->schema([
                                            TextInput::make('interest_paid')
                                                ->label('Interés Pagado')
                                                ->numeric()
                                                ->prefix('$')
                                                ->minValue(0)
                                                ->step(0.01)
                                                ->live(onBlur: true)
                                                ->afterStateUpdated(function (Get $get, Set $set, ?string $state) {
                                                    static $isCalculating = false;
                                                    if ($isCalculating) {
                                                        return;
                                                    }
                                                    $isCalculating = true;
                                                    try {
                                                        $interest = (float) ($get('interest_paid') ?? 0);
                                                        $principal = (float) ($get('principal_paid') ?? 0);
                                                        $set('amount', round($interest + $principal, 2));
                                                        $this->record->calculatePaymentDistribution($get, $set, [
                                                            'interest_paid' => $interest,
                                                            'principal_paid' => $principal,
                                                        ]);
                                                    } finally {
                                                        $isCalculating = false;
                                                    }
                                                }),

                                            TextInput::make('principal_paid')
                                                ->label('Capital Pagado')
                                                ->numeric()
                                                ->prefix('$')
                                                ->minValue(0)
                                                ->step(0.01)
                                                ->live(onBlur: true)
                                                ->afterStateUpdated(function (Get $get, Set $set, ?string $state) {
                                                    static $isCalculating = false;
                                                    if ($isCalculating) {
                                                        return;
                                                    }
                                                    $isCalculating = true;
                                                    try {
                                                        $interest = (float) ($get('interest_paid') ?? 0);
                                                        $principal = (float) ($get('principal_paid') ?? 0);
                                                        $set('amount', round($interest + $principal, 2));
                                                        $this->record->calculatePaymentDistribution($get, $set, [
                                                            'interest_paid' => $interest,
                                                            'principal_paid' => $principal,
                                                        ]);
                                                    } finally {
                                                        $isCalculating = false;
                                                    }
                                                }),
                                        ]),

                                    TextInput::make('amount')
                                        ->label('Monto Total del Pago')
                                        ->numeric()
                                        ->prefix('$')
                                        ->disabled()
                                        ->reactive()
                                        ->helperText('Monto total calculado automáticamente'),
                                ]),

                            // Saldo restante
                            TextInput::make('remaining_balance')
                                ->label('Saldo Restante')
                                ->numeric()
                                ->prefix('$')
                                ->minValue(0)
                                ->step(0.01)
                                ->disabled()
                                ->reactive()
                                ->afterStateHydrated(function (Get $get, Set $set) {
                                    $set('remaining_balance', $this->record->saldo_restante ?? 0);
                                }),
                        ]),
                ]),

            Section::make('Información Adicional')
                ->collapsed()
                ->description('Resumen del estado del crédito')
                ->schema([
                    Placeholder::make('saldo_actual')
                        ->label('Saldo Actual del Crédito')
                        ->content(fn () => '$'.number_format($this->record->saldo_restante, 2)),

                    Placeholder::make('total_pagado')
                        ->label('Total Pagado Hasta Ahora')
                        ->content(fn () => '$'.number_format($this->record->total_pagado, 2)),

                    Placeholder::make('porcentaje_pagado')
                        ->label('Porcentaje Completado')
                        ->content(function () {
                            $pagosData = $this->record->getPagosData();

                            return ($pagosData['porcentaje_pagado'] ?? 0).'%';
                        }),
                ]),
        ];
    }

    /**
     * Crea un nuevo pago
     */
    public function table(Table $table): Table
    {
        return $table
            ->query(
                Payment::query()
                    ->where('credit_id', $this->record->id)
                    ->latest('payment_date')
            )
            ->columns([
                TextColumn::make('index')
                    ->label('#')
                    ->rowIndex()
                    ->sortable(),

                TextColumn::make('payment_date')
                    ->label('Fecha de Pago')
                    ->date('d/m/Y')
                    ->sortable()
                    ->icon('heroicon-o-calendar')
                    ->toggleable(),

                TextColumn::make('amount')
                    ->label('Monto Total')
                    ->money('COP', true)
                    ->sortable()
                    ->icon('heroicon-o-currency-dollar')
                    ->toggleable()
                    ->weight('bold'),

                TextColumn::make('principal_paid')
                    ->label('Capital')
                    ->money('COP', true)
                    ->color('success')
                    ->toggleable(),

                TextColumn::make('interest_paid')
                    ->label('Interés')
                    ->money('COP', true)
                    ->color('warning')
                    ->toggleable(),

                TextColumn::make('remaining_balance')
                    ->label('Saldo Restante')
                    ->money('COP', true)
                    ->color('danger')
                    ->sortable()
                    ->toggleable(),

                BadgeColumn::make('status')
                    ->label('Estado')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'completed' => '✅ Completado',
                        'pending' => '⏳ Pendiente',
                        'reversed' => '↩️ Revertido',
                        default => ucfirst($state),
                    })
                    ->colors([
                        'success' => 'completed',
                        'warning' => 'pending',
                        'danger' => 'reversed',
                    ])
                    ->sortable()
                    ->toggleable(),

                // NUEVA COLUMNA: Tipo de Pago
                BadgeColumn::make('metadata->tipo_pago')
                    ->label('Tipo')
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'normal' => '💳 Normal',
                        'abono_extra' => '💰 Abono Extra',
                        'pago_parcial' => '⚠️ Parcial',
                        'liquidacion' => '🎉 Liquidación',
                        default => '-',
                    })
                    ->colors([
                        'primary' => 'normal',
                        'success' => 'abono_extra',
                        'warning' => 'pago_parcial',
                        'info' => 'liquidacion',
                    ])
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Estado del Pago')
                    ->options([
                        'completed' => 'Completado',
                        'pending' => 'Pendiente',
                        'reversed' => 'Revertido',
                    ])
                    ->placeholder('Todos los estados'),

                SelectFilter::make('tipo_pago')
                    ->label('Tipo de Pago')
                    ->options([
                        'normal' => 'Normal',
                        'abono_extra' => 'Abono Extra',
                        'pago_parcial' => 'Pago Parcial',
                        'liquidacion' => 'Liquidación',
                    ])
                    ->query(function (Builder $query, array $data) {
                        if (isset($data['value'])) {
                            $query->whereJsonContains('metadata->tipo_pago', $data['value']);
                        }
                    })
                    ->placeholder('Todos los tipos'),

                Filter::make('today')
                    ->label('Pagos de Hoy')
                    ->query(fn (Builder $query) => $query->whereDate('payment_date', Carbon::today()))
                    ->toggle(),

                Filter::make('last_week')
                    ->label('Últimos 7 días')
                    ->query(fn (Builder $query) => $query->where('payment_date', '>=', Carbon::now()->subDays(7)))
                    ->toggle(),
            ])
            ->actions([
                Action::make('mark_completed')
                    ->label('')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn (Payment $record) => $record->status === 'pending')
                    ->modalHeading('Completar pago')
                    ->modalDescription(fn (Payment $record) => '¿Deseas Completar este pago de $'.number_format($record->amount, 2).'? Esta acción no se puede deshacer.')
                    ->modalIcon('heroicon-o-check-circle')
                    ->action(function (Payment $record) {
                        $record->update(['status' => 'completed']);

                        // Verificar si el crédito está completamente pagado
                        $pagosData = $this->record->getPagosData();
                        if (($pagosData['saldo_restante'] ?? 0) <= 0) {
                            $this->record->update(['status' => 'completed']);
                        }

                        $this->record->load('payments');

                        Notification::make()
                            ->title('Pago actualizado')
                            ->body('El pago de $'.number_format($record->amount, 2).' ha sido marcado como completado.')
                            ->success()
                            ->send();
                    })
                    ->tooltip('Cambiar estado de pendiente a completado'),

                Action::make('edit')
                    ->label('')
                    ->icon('heroicon-o-pencil')
                    ->color('primary')
                    ->tooltip('Editar pago')
                    ->visible(fn (Payment $record) => $record->status !== 'completed')
                    ->modalHeading('Editar Pago')
                    ->modalWidth('2xl')
                    ->form($this->getPaymentFormSchema())
                    ->fillForm(fn (Payment $record) => [
                        'payment_date' => $record->payment_date,
                        'status' => $record->status,
                        'amount' => $record->amount,
                        'interest_paid' => $record->interest_paid,
                        'principal_paid' => $record->principal_paid,
                        'payment_mode' => 'detalle',
                    ])
                    ->action(function (Payment $record, array $data) {
                        $amount = (float) ($data['amount'] ?? (($data['interest_paid'] ?? 0) + ($data['principal_paid'] ?? 0)));
                        $data['amount'] = $amount;

                        // Recalcular distribución considerando el estado actual sin este pago
                        $paymentData = $this->record->getPagosData($data);

                        $record->update([
                            'amount' => $amount,
                            'principal_paid' => $paymentData['principal_paid'],
                            'interest_paid' => $paymentData['interest_paid'],
                            'remaining_balance' => $paymentData['remaining_balance'],
                            'payment_date' => $data['payment_date'],
                            'status' => $data['status'],
                            'metadata' => $paymentData['metadata'] ?? [],
                        ]);

                        $this->record->load('payments');

                        Notification::make()
                            ->title('Pago actualizado')
                            ->body('El pago de $'.number_format($amount, 2).' fue actualizado.')
                            ->success()
                            ->send();
                    }),

                Action::make('view')
                    ->label('')
                    ->icon('heroicon-o-eye')
                    ->color('primary')
                    ->tooltip('Ver detalles del pago')
                    ->modalHeading('Detalles del Pago')
                    ->modalWidth('2xl')
                    ->modalContent(fn (Payment $record) => view('filament.modals.payment-details', [
                        'payment' => $record,
                    ]))
                    ->modalCancelActionLabel('Cerrar')
                    ->modalSubmitAction(false),

                DeleteAction::make()
                    ->label('')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Eliminar Pago')
                    ->modalDescription(fn (Payment $record) => '¿Deseas eliminar este pago de $'.number_format($record->amount, 2).'? Esta acción recalculará toda la tabla de amortización.')
                    ->modalSubmitActionLabel('Sí, eliminar')
                    ->modalCancelActionLabel('Cancelar')
                    ->after(function () {
                        $this->record->load('payments');

                        // Si ya no hay pagos y el crédito estaba completado, reactivarlo
                        if ($this->record->payments()->count() === 0 && $this->record->status === 'completed') {
                            $this->record->update(['status' => 'active']);
                        }

                        Notification::make()
                            ->title('Pago eliminado')
                            ->body('La tabla de amortización ha sido recalculada.')
                            ->success()
                            ->send();
                    }),
            ])
            ->defaultSort('payment_date', 'desc')
            ->searchable()
            ->paginated([5, 10, 25, 50])
            ->defaultPaginationPageOption(10)
            ->striped()
            ->recordClasses(fn (Payment $record) => match ($record->status) {
                'pending' => 'opacity-70',
                'reversed' => 'bg-red-50 dark:bg-red-900/20',
                default => '',
            })
            ->emptyStateHeading('Sin pagos registrados')
            ->emptyStateDescription('Aún no se han registrado pagos para este crédito.')
            ->emptyStateIcon('heroicon-o-inbox')
            ->emptyStateActions([
                Action::make('create_first_payment')
                    ->label('Registrar Primer Pago')
                    ->icon('heroicon-o-plus-circle')
                    ->color('success')
                    ->modalHeading('Registrar Primer Pago')
                    ->modalWidth('2xl')
                    ->form($this->getPaymentFormSchema())
                    ->action(function (array $data) {
                        $this->createPayment($data);
                    }),
            ]);
    }

    /**
     * Crea un nuevo pago con recálculo automático
     */
    protected function createPayment(array $data): void
    {
        try {
            $amount = (float) ($data['amount'] ?? (($data['interest_paid'] ?? 0) + ($data['principal_paid'] ?? 0)));
            $data['amount'] = $amount;

            // Obtener datos del pago calculados por el modelo
            $paymentData = $this->record->getPagosData($data);

            // Crear el pago
            Payment::create([
                'credit_id' => $this->record->id,
                'amount' => $amount,
                'principal_paid' => $paymentData['principal_paid'],
                'interest_paid' => $paymentData['interest_paid'],
                'remaining_balance' => $paymentData['remaining_balance'],
                'payment_date' => $data['payment_date'],
                'status' => $data['status'],
                'metadata' => $paymentData['metadata'] ?? [],
            ]);

            // Actualizar estado del crédito si está completamente pagado
            if ($paymentData['remaining_balance'] <= 0) {
                $this->record->update(['status' => 'paid']);
            }
            if ($this->record->status === 'calculated' || $this->record->status === 'calculated-updated' || $this->record->status === 'calculated-copied') {
                $this->record->update(['status' => 'pending']);
            }

            // Recargar relación
            $this->record->load('payments');

            $tipoPago = $paymentData['metadata']['tipo_pago'] ?? 'normal';
            $tipoPagoTexto = match ($tipoPago) {
                'normal' => 'Pago normal',
                'abono_extra' => 'Abono extra a capital',
                'pago_parcial' => 'Pago parcial',
                'liquidacion' => 'Liquidación del crédito',
                default => 'Pago registrado',
            };

            Notification::make()
                ->title('Pago registrado exitosamente')
                ->body($tipoPagoTexto.' - Monto: $'.number_format($amount, 2))
                ->success()
                ->send();

        } catch (\Exception $e) {
            Notification::make()
                ->title('Error al registrar el pago')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }
}
