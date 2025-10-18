<?php

namespace App\Filament\Pages\Creditos;

use App\Enums\CalculationType;
use App\Enums\CreditStatusType;
use App\Enums\PageGroupType;
use App\Models\Credit;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Pages\Page;
use Filament\Support\Enums\Width;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;

class ListCredits extends Page implements HasTable
{
    use InteractsWithTable;

    protected string $view = 'filament.pages.creditos.index';

    protected static ?string $slug = 'creditos';

    protected static string|null|\BackedEnum $navigationIcon = 'heroicon-o-document-text';

    protected static string|null|\BackedEnum $activeNavigationIcon = 'heroicon-s-document-text';

    protected static string|null|\UnitEnum $navigationGroup = PageGroupType::CREDIT->value;

    public static function getNavigationSort(): ?int
    {
        return 3;
    }

    public static function getNavigationLabel(): string
    {
        return 'Gestor de Créditos';
    }

    public function getHeading(): Htmlable|string
    {
        return '';
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Credit::query()->with('user')->latest('calculated_at'))
            ->columns([
                TextColumn::make('reference_code')
                    ->label('Código')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->copyMessage('Código copiado')
                    ->icon('heroicon-o-hashtag')
                    ->weight('bold')
                    ->color('primary')
                    ->url(fn (Credit $record): string => ShowCredit::getUrl(['recordId' => $record->id]))
                    ->tooltip('Hacer clic para ver detalles')
                    ->toggleable(),

                TextColumn::make('debtor_names')
                    ->label('Nombre del Deudor')
                    ->searchable()
                    ->sortable()
                    ->icon('heroicon-o-user')
                    ->description(fn (Credit $record): string => $record->debtor_last_names)
                    ->toggleable(),

                TextColumn::make('debtor_id_number')
                    ->label('Cédula')
                    ->searchable()
                    ->sortable()
                    ->icon('heroicon-o-identification')
                    ->copyable()
                    ->copyMessage('Cédula copiada')
                    ->badge()
                    ->toggleable(),

                TextColumn::make('type')
                    ->label('Tipo de Cálculo')
                    ->badge()
                    ->formatStateUsing(fn (CalculationType $state): string => match ($state) {
                        CalculationType::SIMPLE => 'Interés Simple',
                        CalculationType::COMPUESTO => 'Interés Compuesto',
                        CalculationType::ANUALIDAD => 'Anualidad',
                        CalculationType::TASA_INTERES => 'Tasa de Interés',
                        CalculationType::AMORTIZACION => 'Amortización',
                        CalculationType::CAPITALIZACION => 'Capitalización',
                        CalculationType::TIR => 'TIR',
                        CalculationType::GRADIENTES => 'Gradientes',
                    })
                    ->color(fn (CalculationType $state): string => match ($state) {
                        CalculationType::SIMPLE, CalculationType::TIR => 'success',
                        CalculationType::COMPUESTO, CalculationType::TASA_INTERES => 'info',
                        CalculationType::ANUALIDAD => 'warning',
                        CalculationType::AMORTIZACION => 'danger',
                        CalculationType::CAPITALIZACION => 'gray',
                        CalculationType::GRADIENTES => 'primary',
                    })
                    ->sortable()
                    ->toggleable(),

                BadgeColumn::make('status')
                    ->label('Estado')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'calculated' => '✓ Calculado',
                        'calculated-updated' => '🔄 Calculado (actualizado)',
                        'calculated-copied' => '📋 Calculado (Copia)',
                        'pending' => '⏳ Pendiente',
                        'paid' => '✓ Pagado',
                        'rejected' => '✗ Rechazado',
                        default => $state,
                    })
                    ->colors([
                        'info' => ['calculated', 'calculated-updated'],
                        'warning' => 'pending',
                        'success' => 'paid',
                        'danger' => 'rejected',
                    ])
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('user.name')
                    ->label('Creado Por')
                    ->searchable()
                    ->sortable()
                    ->icon('heroicon-o-user-circle')
                    ->color('gray')
                    ->toggleable(),

                TextColumn::make('calculated_at')
                    ->label('Última Actualización')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->icon('heroicon-o-calendar')
                    ->color('gray')
                    ->description(fn (Credit $record): string => $record->calculated_at?->diffForHumans() ?? 'N/A')
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->label('Tipo de Cálculo')
                    ->options([
                        'simple' => 'Interés Simple',
                        'compuesto' => 'Interés Compuesto',
                        'anualidad' => 'Anualidad',
                        'tasa_interes' => 'Tasa de Interés',
                        'amortizacion' => 'Amortización',
                        'capitalizacion' => 'Capitalización',
                        'tir' => 'TIR',
                        'gradientes' => 'Gradientes',
                    ])
                    ->placeholder('Todos los tipos'),

                SelectFilter::make('status')
                    ->label('Estado')
                    ->options([
                        'calculated' => 'Calculado',
                        'pending' => 'Pendiente',
                        'paid' => 'Pagado',
                        'cancelled' => 'Cancelado',
                    ])
                    ->placeholder('Todos los estados'),

                SelectFilter::make('user_id')
                    ->label('Creado Por')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->placeholder('Todos los usuarios'),

                Filter::make('created_today')
                    ->label('Creados Hoy')
                    ->query(fn (Builder $query): Builder => $query->whereDate('created_at', Carbon::today()))
                    ->toggle(),

                Filter::make('calculated_this_week')
                    ->label('Calculados Esta Semana')
                    ->query(fn (Builder $query): Builder => $query->whereBetween('calculated_at', [
                        Carbon::now()->startOfWeek(),
                        Carbon::now()->endOfWeek(),
                    ]))
                    ->toggle(),
            ])
            ->actions([
                Action::make('view')
                    ->label('')
                    ->icon('heroicon-o-eye')
                    ->color('primary')
                    ->url(fn (Credit $record): string => ShowCredit::getUrl(['recordId' => $record->id]))
                    ->tooltip('Ver detalles completos del crédito'),
                Action::make('edit')
                    ->label('')
                    ->icon('heroicon-o-pencil-square')
                    ->color('secondary')
                    ->url(fn (Credit $record): string => EditCredit::getUrl(['recordId' => $record->id]))
                    ->tooltip('Ver detalles completos del crédito'),

                Action::make('duplicate')
                    ->label('')
                    ->icon('heroicon-o-document-duplicate')
                    ->color('primary')
                    ->action(function (Credit $record) {
                        $newCredit = $record->replicate()->fill([
                            'reference_code' => 'COPY-'.$record->reference_code,
                            'status' => CreditStatusType::CALCULATED_COPIED->value,
                        ]);
                        $newCredit->save();
                    })
                    ->tooltip('Crear una copia de este crédito')
                    ->successNotificationTitle('Crédito duplicado exitosamente'),

                DeleteAction::make()
                    ->label('')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Eliminar Crédito')
                    ->modalDescription('¿Estás seguro? Esta acción eliminará el crédito permanentemente y no se puede deshacer.')
                    ->modalSubmitActionLabel('Sí, eliminar')
                    ->modalCancelActionLabel('Cancelar')
                    ->successNotificationTitle('Crédito eliminado correctamente'),
            ])
            ->bulkActions([
                // TODO: agregar acciones masivas si es necesario
            ])
            ->emptyStateHeading('Sin Créditos')
            ->emptyStateDescription('Comienza creando tu primer crédito para visualizarlo aquí.')
            ->emptyStateIcon('heroicon-o-inbox')
            ->defaultSort('calculated_at', 'desc')
            ->striped()
            ->recordClasses(fn (Credit $record) => match ($record->status) {
                'pending' => 'opacity-75',
                default => '',
            })
            ->paginated([5, 10, 25, 50, 100])
            ->defaultPaginationPageOption(10);
    }

    public function getFooter(): ?View
    {
        return view('filament.settings.custom-footer');
    }

    public function getMaxContentWidth(): Width
    {
        return Width::ScreenTwoExtraLarge;
    }
}
