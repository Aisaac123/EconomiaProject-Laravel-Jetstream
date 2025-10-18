<?php

namespace App\Filament\Schemas;

use Blade;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Schemas\Schema;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;

class InteresSimpleSchema
{
    public static function configure(Schema $schema, bool $showSaveButton = false): Schema
    {
        return $schema
            ->schema([
                // Campos ocultos para almacenar resultados
                Hidden::make('campo_calculado'),
                Hidden::make('resultado_calculado'),
                Hidden::make('interes_generado_calculado'),
                Hidden::make('mensaje_calculado'),
                Hidden::make('tiempo'),

                // Wizard con los diferentes pasos
                Wizard::make([

                    // Paso 1: Información básica
                    Step::make('Información Básica')
                        ->icon('heroicon-o-calculator')
                        ->completedIcon('heroicon-s-calculator')
                        ->schema([
                            Section::make('Información Básica')
                                ->description('Ingrese los campos que desee y deje alguno libre para calcular.')
                                ->icon('heroicon-o-calculator')
                                ->schema([
                                    Grid::make(3)->schema([
                                        TextInput::make('capital')
                                            ->rules(['nullable', 'numeric', 'min:0'])
                                            ->validationMessages([
                                                'min' => 'El capital inicial debe ser mayor o igual a 0',
                                            ])
                                            ->label('Capital Inicial (P)')
                                            ->numeric()
                                            ->prefix('$')
                                            ->placeholder('Ejemplo: 10000')
                                            ->live()
                                            ->afterStateUpdated(function (callable $set) {
                                                $set('campo_calculado', null);
                                                $set('resultado_calculado', null);
                                                $set('interes_generado_calculado', null);
                                                $set('mensaje_calculado', null);
                                            }),

                                        TextInput::make('monto_final')
                                            ->rules(['nullable', 'numeric', 'min:0'])
                                            ->validationMessages([
                                                'min' => 'El monto final debe ser mayor o igual a 0',
                                            ])
                                            ->label('Monto Final (A)')
                                            ->numeric()
                                            ->prefix('$')
                                            ->placeholder('Ejemplo: 15000')
                                            ->live()
                                            ->afterStateUpdated(function (callable $set) {
                                                $set('campo_calculado', null);
                                                $set('resultado_calculado', null);
                                                $set('interes_generado_calculado', null);
                                                $set('mensaje_calculado', null);
                                            }),

                                        TextInput::make('interes_generado')
                                            ->rules(['nullable', 'numeric', 'min:0'])
                                            ->validationMessages([
                                                'min' => 'El capital inicial debe ser mayor o igual a 0',
                                            ])
                                            ->label('Interes Generado (I)')
                                            ->numeric()
                                            ->prefix('$')
                                            ->placeholder('Ejemplo: 5000')
                                            ->live()
                                            ->afterStateUpdated(function (callable $set) {
                                                $set('campo_calculado', null);
                                                $set('resultado_calculado', null);
                                                $set('interes_generado_calculado', null);
                                                $set('mensaje_calculado', null);
                                            }),
                                    ]),
                                ]),
                        ]),

                    // Paso 2: Tasa de interés
                    Step::make('Tasa de Interés')
                        ->icon('heroicon-o-percent-badge')
                        ->completedIcon('heroicon-s-percent-badge')
                        ->schema([
                            Section::make('Configuración de Tasa de Interés')
                                ->description('Configure la tasa de interés y su periodicidad')
                                ->icon('heroicon-o-percent-badge')
                                ->schema([
                                    Grid::make(12)->schema([
                                        TextInput::make('tasa_interes')
                                            ->rules(['nullable', 'numeric', 'min:0'])
                                            ->validationMessages([
                                                'min' => 'La tasa de interes debe ser mayor o igual a 0',
                                            ])
                                            ->label('Tasa de Interés')
                                            ->numeric()
                                            ->suffix('%')
                                            ->placeholder('Ejemplo: 5.5')
                                            ->step(0.01)
                                            ->hint('Tasa simple (%)')
                                            ->columnSpan(4)
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(function (callable $set) {
                                                $set('campo_calculado', null);
                                                $set('resultado_calculado', null);
                                                $set('interes_generado_calculado', null);
                                                $set('mensaje_calculado', null);
                                            }),

                                        TextInput::make('periodicidad_tasa')
                                            ->rules(['nullable', 'numeric', 'min:1'])
                                            ->validationMessages([
                                                'min' => 'La periodicidad debe ser mayor o igual a 1',
                                            ])
                                            ->label('Periodicidad (numérica)')
                                            ->numeric()
                                            ->placeholder('12 para mensual')
                                            ->hint('Períodos por año')
                                            ->default(1)
                                            ->columnSpan(5)
                                            ->visible(fn (callable $get) => ! $get('usar_select_periodicidad_tasa'))
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(function (callable $set) {
                                                $set('campo_calculado', null);
                                                $set('resultado_calculado', null);
                                                $set('interes_generado_calculado', null);
                                                $set('mensaje_calculado', null);
                                            }),

                                        Select::make('periodicidad_tasa')
                                            ->label('Periodicidad de la Tasa')
                                            ->options([
                                                1 => 'Anual (1 vez/año)',
                                                2 => 'Semestral (2 veces/año)',
                                                4 => 'Trimestral (4 veces/año)',
                                                6 => 'Bimestral (6 veces/año)',
                                                12 => 'Mensual (12 veces/año)',
                                                24 => 'Quincenal (24 veces/año)',
                                                52 => 'Semanal (52 veces/año)',
                                                365 => 'Diaria (365 veces/año)',
                                                360 => 'Diaria Comercial (360 veces/año)',
                                            ])
                                            ->default(1)
                                            ->searchable()
                                            ->columnSpan(5)
                                            ->visible(fn (callable $get) => $get('usar_select_periodicidad_tasa'))
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(function (callable $set) {
                                                $set('campo_calculado', null);
                                                $set('resultado_calculado', null);
                                                $set('interes_generado_calculado', null);
                                                $set('mensaje_calculado', null);
                                            }),

                                        Toggle::make('usar_select_periodicidad_tasa')
                                            ->label('Selector de periodicidad')
                                            ->default(true)
                                            ->live()
                                            ->inline(false)
                                            ->columnSpan(3)
                                            ->extraAttributes(['class' => 'text-center items-center ml-14 mt-1'])
                                            ->afterStateUpdated(function (callable $set) {
                                                $set('campo_calculado', null);
                                                $set('resultado_calculado', null);
                                                $set('interes_generado_calculado', null);
                                                $set('mensaje_calculado', null);
                                            }),

                                    ]),
                                ]),
                        ]),

                    // Paso 3: Configuración de tiempo
                    Step::make('Período de Tiempo')
                        ->icon('heroicon-o-clock')
                        ->completedIcon('heroicon-s-clock')
                        ->schema([
                            Section::make('Configuración de Tiempo')
                                ->description('Configure el período de inversión')
                                ->icon('heroicon-o-clock')
                                ->schema([
                                    Grid::make(12)->schema([
                                        FieldSet::make('Tiempo')->schema([
                                            TextInput::make('anio')
                                                ->rules(['nullable', 'numeric', 'min:0'])
                                                ->validationMessages([
                                                    'min' => 'El tiempo debe ser mayor o igual a 0',
                                                ])
                                                ->label('Años')
                                                ->numeric()
                                                ->suffix('años')
                                                ->placeholder('Ejemplo: 5')
                                                ->minValue(0)
                                                ->visible(fn (callable $get) => ! $get('usar_fechas_tiempo'))
                                                ->live()
                                                ->afterStateUpdated(function (callable $set, callable $get) {
                                                    calcularTiempo($set, $get);
                                                    $set('campo_calculado', null);
                                                    $set('resultado_calculado', null);
                                                    $set('interes_generado_calculado', null);
                                                    $set('mensaje_calculado', null);
                                                }),

                                            TextInput::make('mes')
                                                ->rules(['nullable', 'numeric', 'min:0'])
                                                ->validationMessages([
                                                    'min' => 'El tiempo debe ser mayor o igual a 0',
                                                ])
                                                ->label('Meses')
                                                ->numeric()
                                                ->suffix('meses')
                                                ->placeholder('Ejemplo: 7')
                                                ->visible(fn (callable $get) => ! $get('usar_fechas_tiempo'))
                                                ->live()
                                                ->afterStateUpdated(function (callable $set, callable $get) {
                                                    calcularTiempo($set, $get);
                                                    $set('campo_calculado', null);
                                                    $set('resultado_calculado', null);
                                                    $set('interes_generado_calculado', null);
                                                    $set('mensaje_calculado', null);
                                                }),

                                            TextInput::make('dia')
                                                ->rules(['nullable', 'numeric', 'min:0'])
                                                ->validationMessages([
                                                    'min' => 'El tiempo debe ser mayor o igual a 0',
                                                ])
                                                ->label('Dias')
                                                ->numeric()
                                                ->suffix('dias')
                                                ->placeholder('Ejemplo: 21')
                                                ->visible(fn (callable $get) => ! $get('usar_fechas_tiempo'))
                                                ->live()
                                                ->afterStateUpdated(function (callable $set, callable $get) {
                                                    calcularTiempo($set, $get);
                                                    $set('campo_calculado', null);
                                                    $set('resultado_calculado', null);
                                                    $set('interes_generado_calculado', null);
                                                    $set('mensaje_calculado', null);
                                                }),
                                            Toggle::make('usar_fechas_tiempo')
                                                ->label('Usar fechas')
                                                ->default(false)
                                                ->live()
                                                ->extraAttributes(['class' => 'text-center items-center ml-4 mt-1'])
                                                ->inline(false)
                                                ->visible(fn (callable $get) => ! $get('usar_fechas_tiempo'))
                                                ->afterStateUpdated(function (callable $set) {
                                                    $set('campo_calculado', null);
                                                    $set('resultado_calculado', null);
                                                    $set('interes_generado_calculado', null);
                                                    $set('mensaje_calculado', null);
                                                    $set('tiempo', null);
                                                    $set('fecha_inicio', null);
                                                    $set('fecha_final', null);
                                                    $set('anio', null);
                                                    $set('mes', null);
                                                    $set('dia', null);
                                                }),
                                        ])
                                            ->columns([
                                                'default' => 1,
                                                'md' => 2,
                                                'xl' => 4,
                                            ])->columnSpan(12)
                                            ->visible(fn (callable $get) => ! $get('usar_fechas_tiempo')),

                                        FieldSet::make('Fechas')->schema([
                                            DatePicker::make('fecha_inicio')
                                                ->label('Fecha de Inicio')
                                                ->placeholder('Seleccione la fecha inicial')
                                                ->live()
                                                ->columnSpan(4)
                                                ->afterStateUpdated(function (callable $set, callable $get, $state) {
                                                    calcularTiempoDesdeFechas($set, $get);
                                                    $set('campo_calculado', null);
                                                    $set('resultado_calculado', null);
                                                    $set('interes_generado_calculado', null);
                                                    $set('mensaje_calculado', null);
                                                })
                                                ->visible(fn (callable $get) => $get('usar_fechas_tiempo')),

                                            DatePicker::make('fecha_final')
                                                ->label('Fecha Final')
                                                ->placeholder('Seleccione la fecha final')
                                                ->live()
                                                ->columnSpan(4)
                                                ->afterStateUpdated(function (callable $set, callable $get, $state) {
                                                    calcularTiempoDesdeFechas($set, $get);
                                                    $set('campo_calculado', null);
                                                    $set('resultado_calculado', null);
                                                    $set('interes_generado_calculado', null);
                                                    $set('mensaje_calculado', null);
                                                })
                                                ->visible(fn (callable $get) => $get('usar_fechas_tiempo')),

                                            Toggle::make('usar_fechas_tiempo')
                                                ->label('Usar fechas')
                                                ->default(false)
                                                ->live()
                                                ->extraAttributes(['class' => 'text-center items-center ml-4 mt-1'])
                                                ->inline(false)
                                                ->columnSpan(3)
                                                ->columnStart(10)
                                                ->visible(fn (callable $get) => $get('usar_fechas_tiempo'))
                                                ->afterStateUpdated(function (callable $set) {
                                                    $set('campo_calculado', null);
                                                    $set('resultado_calculado', null);
                                                    $set('interes_generado_calculado', null);
                                                    $set('mensaje_calculado', null);
                                                    $set('tiempo', null);
                                                    $set('fecha_inicio', null);
                                                    $set('fecha_final', null);
                                                    $set('anio', null);
                                                    $set('mes', null);
                                                    $set('dia', null);
                                                }),
                                        ])->columns([
                                            'default' => 1,
                                            'md' => 6,
                                            'xl' => 12,
                                        ])->columnSpan(12)
                                            ->visible(fn (callable $get) => $get('usar_fechas_tiempo')),

                                        TextInput::make('tiempo')
                                            ->label('Tiempo en Años')
                                            ->suffix('años')
                                            ->columnSpan(6)
                                            ->disabled(),
                                    ]),
                                ]),
                        ]),

                    // Paso 4: Resultados
                    Step::make('Resultados')
                        ->icon('heroicon-o-chart-bar')
                        ->completedIcon('heroicon-s-chart-bar')
                        ->schema([
                            Section::make('Resultados del Cálculo')
                                ->collapsible()
                                ->description('Resumen completo de los valores calculados')
                                ->icon('heroicon-o-chart-bar-square')
                                ->schema([
                                    Grid::make(1)->schema([
                                        Placeholder::make('_')
                                            ->label('')
                                            ->content(function (callable $get): Htmlable {
                                                $capital = $get('capital');
                                                $montoFinal = $get('monto_final');
                                                $tasaInteres = $get('tasa_interes');
                                                $tiempo = $get('tiempo');
                                                $interesGenerado = $get('interes_generado'); // ← Agregar esta línea

                                                $campoCalculado = $get('campo_calculado');
                                                $resultado = $get('resultado_calculado');
                                                $resultado2 = $get('resultado_calculado_2'); // ← Agregar para segundo resultado
                                                $interesGeneradoCalculado = $get('interes_generado_calculado');
                                                $mensaje = $get('mensaje_calculado');

                                                $frecuencia = $get('frecuencia') ?: 12;
                                                $periodicidadTasa = $get('periodicidad_tasa') ?: 1;

                                                // Contar campos vacíos CORRECTAMENTE
                                                $emptyFields = [];
                                                $mainFields = ['capital', 'monto_final', 'tasa_interes', 'tiempo'];

                                                foreach ($mainFields as $field) {
                                                    $value = $get($field);
                                                    if ($value === null || $value === '' || $value === 0) {
                                                        $emptyFields[] = $field;
                                                    }
                                                }

                                                // Verificar si se proporcionó interés generado
                                                $interesGeneradoProvided = ! empty($interesGenerado);

                                                // Validación corregida
                                                $maxEmptyFields = $interesGeneradoProvided ? 2 : 1;

                                                // Si hay un cálculo exitoso, mostrar resultados
                                                if ($campoCalculado && ($resultado || $resultado2)) {
                                                    return static::buildResultHtml(
                                                        $capital, $montoFinal, $tasaInteres, $tiempo,
                                                        $periodicidadTasa, $frecuencia,
                                                        $interesGeneradoCalculado ?? $interesGenerado,
                                                        $mensaje, $campoCalculado, $resultado, $resultado2
                                                    );
                                                }

                                                // Estado inicial - todos vacíos
                                                if (empty($capital) && empty($montoFinal) && empty($tasaInteres) && empty($tiempo) && empty($interesGenerado)) {
                                                    return new HtmlString('
                                    <div class="text-center py-12 text-gray-500 dark:text-gray-400">
                                        <div class="text-5xl mb-4">📈</div>
                                        <h3 class="text-xl font-semibold mb-2">Complete los campos para ver los detalles</h3>
                                        <p class="text-sm text-gray-400">Los resultados aparecerán aquí después del cálculo</p>
                                    </div>
                                ');
                                                }

                                                // Validación de campos vacíos
                                                if (count($emptyFields) === 0) {
                                                    $errorMessage = 'Debes dejar exactamente un campo vacío para calcular'.
                                                        ($interesGeneradoProvided ? ' (o dos si proporcionas el interés generado)' : '').'.';
                                                } elseif (count($emptyFields) > $maxEmptyFields) {
                                                    $errorMessage = $interesGeneradoProvided
                                                        ? 'Con interés generado puedes dejar máximo 2 campos vacíos. Actualmente hay '.count($emptyFields).' campos vacíos.'
                                                        : 'Solo un campo puede estar vacío. Actualmente hay '.count($emptyFields).' campos vacíos.';
                                                } else {
                                                    // Validación exitosa - listo para calcular
                                                    return new HtmlString('
                                    <div class="text-center py-12 text-gray-500 dark:text-gray-400">
                                        <div class="text-5xl mb-4">⏳</div>
                                        <h3 class="text-xl font-semibold mb-2">Listo para calcular</h3>
                                        <p class="text-sm text-gray-400">Presiona el botón "Calcular" para ver los resultados</p>
                                    </div>
                                ');
                                                }

                                                // Mostrar error de validación
                                                return new HtmlString('
                                <div class="text-center py-12">
                                    <div class="bg-gradient-to-r from-red-50 to-orange-50 dark:from-red-950/50 dark:to-orange-950/50 rounded-xl p-8 border border-red-200 dark:border-red-800">
                                        <div class="text-6xl mb-4">⚠️</div>
                                        <h3 class="text-xl font-bold text-red-900 dark:text-red-100 mb-3">Error de Validación</h3>
                                        <p class="text-red-700 dark:text-red-300 mb-4 text-lg">'.$errorMessage.'</p>
                                        <div class="bg-red-100 dark:bg-red-900/50 rounded-lg p-4 border border-red-300 dark:border-red-700">
                                            <p class="text-sm text-red-800 dark:text-red-200">
                                                <strong>Instrucciones:</strong><br>
                                                • Completa todos los campos conocidos<br>
                                                • Deja vacío únicamente el campo que deseas calcular<br>
                                                '.($interesGeneradoProvided ? '• Con interés generado puedes dejar hasta 2 campos vacíos<br>' : '').'
                                                • Presiona el botón "Calcular" para obtener el resultado
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            ');
                                            }),
                                    ]),
                                ]),
                        ]),
                ])->skippable()
                    ->startOnStep(1)
                    ->contained(false)
                    ->submitAction(new HtmlString(Blade::render(<<<'BLADE'
                        <div class="flex items-center gap-4">
                            <x-filament::button
                                type="submit"
                                color="primary"
                                class="text-white"
                            >
                                <x-slot:icon>
                                    <x-heroicon-o-calculator class="size-5 text-white" />
                                </x-slot:icon>
                                Calcular
                            </x-filament::button>

                            @if($showSave)
                            <x-filament::button
                                wire:click="saveCredito"
                                color="success"
                                class="text-white"
                            >
                                <x-slot:icon>
                                    <x-heroicon-o-check class="size-5 text-white" />
                                </x-slot:icon>
                                Guardar Crédito
                            </x-filament::button>
                            @endif
                        </div>
                    BLADE, ['showSave' => $showSaveButton]))),
            ]);
    }

    /**
     * Construye el HTML para mostrar los resultados
     */
    public static function buildResultHtml(
        $capital, $montoFinal, $tasaInteres, $tiempo,
        $periodicidadTasa, $frecuencia, $interesGenerado,
        $mensaje, $campoCalculado, $resultado, $resultado2 = null
    ): Htmlable {

        // Inicio HTML
        $html = '<div class="space-y-5">';

        // ============================================
        // HEADER - Interés Simple
        // ============================================
        $html .= '
        <div class="bg-gradient-to-r from-green-50 to-emerald-100 dark:from-green-950/50 dark:to-emerald-800/50 rounded-xl p-5 border border-green-200 dark:border-green-800">
            <div class="flex items-center gap-3">
                <span class="text-3xl">💰</span>
                <div>
                    <h3 class="text-lg font-bold text-green-900 dark:text-green-100">Cálculo de Interés Simple</h3>
                    <p class="text-sm text-green-700 dark:text-green-300">Interés calculado sobre el capital inicial</p>
                </div>
            </div>
        </div>
    ';

        // Determinar qué campos fueron calculados
        $camposCalculados = explode(',', $campoCalculado);

        // ============================================
        // BLOQUE 1: Parámetros Principales
        // ============================================
        $html .= '<div class="bg-gradient-to-br from-slate-50 to-slate-100 dark:from-slate-900/50 dark:to-slate-800/50 rounded-xl p-4 border border-slate-200 dark:border-slate-700">';
        $html .= '<h4 class="text-sm font-bold text-slate-700 dark:text-slate-300 mb-3 flex items-center gap-2">
              <span>📋</span> PARÁMETROS DE INVERSIÓN
              </h4>';

        $html .= '<div class="grid grid-cols-2 gap-3">';

        // Capital Inicial
        $isCalculated = in_array('capital', $camposCalculados);
        if ($isCalculated) {
            $displayValue = '$'.number_format($resultado, 2);
        } else {
            $displayValue = is_numeric($capital) ? '$'.number_format($capital, 2) : '--';
        }

        $bgColor = $isCalculated ? 'from-green-50 to-emerald-50 border-green-200 dark:from-green-950/50 dark:to-emerald-950/50 dark:border-green-700' : 'from-blue-50 to-cyan-50 border-blue-200 dark:from-blue-950/50 dark:to-cyan-950/50 dark:border-blue-700';
        $textColor = $isCalculated ? 'text-green-900 dark:text-green-100' : 'text-blue-900 dark:text-blue-100';
        $subColor = $isCalculated ? 'text-green-600 dark:text-green-400' : 'text-blue-600 dark:text-blue-400';
        $badgeColor = $isCalculated ? 'bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-200' : 'bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-200';

        $html .= "
        <div class='rounded-lg p-4 border bg-gradient-to-br {$bgColor} shadow-md'>
            <div class='flex items-center justify-between mb-2'>
                <div class='flex items-center gap-2'>
                    <span class='text-xl'>💵</span>
                    <h5 class='font-bold {$textColor} text-sm'>Capital</h5>
                </div>
                ".($isCalculated ? "<span class='px-2 py-0.5 text-xs font-medium rounded-full {$badgeColor}'>✨</span>" : "<span class='px-2 py-0.5 text-xs font-medium rounded-full {$badgeColor}'>📝</span>")."
            </div>
            <p class='text-2xl font-bold {$textColor}'>{$displayValue}</p>
            <p class='text-xs {$subColor} mt-1'>Inversión inicial</p>
        </div>
    ";

        // Monto Final
        $isCalculated = in_array('monto_final', $camposCalculados);
        if ($isCalculated) {
            if (in_array('capital', $camposCalculados) && $resultado2) {
                $displayValue = '$'.number_format($resultado2, 2);
            } else {
                $displayValue = '$'.number_format($resultado, 2);
            }
        } else {
            $displayValue = is_numeric($montoFinal) ? '$'.number_format($montoFinal, 2) : '--';
        }

        $bgColor = $isCalculated ? 'from-purple-50 to-pink-50 border-purple-200 dark:from-purple-950/50 dark:to-pink-950/50 dark:border-purple-700' : 'from-blue-50 to-cyan-50 border-blue-200 dark:from-blue-950/50 dark:to-cyan-950/50 dark:border-blue-700';
        $textColor = $isCalculated ? 'text-purple-900 dark:text-purple-100' : 'text-blue-900 dark:text-blue-100';
        $subColor = $isCalculated ? 'text-purple-600 dark:text-purple-400' : 'text-blue-600 dark:text-blue-400';
        $badgeColor = $isCalculated ? 'bg-purple-100 text-purple-800 dark:bg-purple-900/50 dark:text-purple-200' : 'bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-200';

        $html .= "
        <div class='rounded-lg p-4 border bg-gradient-to-br {$bgColor} shadow-md'>
            <div class='flex items-center justify-between mb-2'>
                <div class='flex items-center gap-2'>
                    <span class='text-xl'>🎯</span>
                    <h5 class='font-bold {$textColor} text-sm'>Monto Final</h5>
                </div>
                ".($isCalculated ? "<span class='px-2 py-0.5 text-xs font-medium rounded-full {$badgeColor}'>✨</span>" : "<span class='px-2 py-0.5 text-xs font-medium rounded-full {$badgeColor}'>📝</span>")."
            </div>
            <p class='text-2xl font-bold {$textColor}'>{$displayValue}</p>
            <p class='text-xs {$subColor} mt-1'>Valor al vencimiento</p>
        </div>
    ";

        $html .= '</div>';
        $html .= '</div>'; // Fin parámetros principales

        // ============================================
        // BLOQUE 2: Condiciones del Préstamo
        // ============================================
        $html .= '<div class="space-y-3">';
        $html .= '<h4 class="text-sm font-bold text-gray-700 dark:text-gray-300 flex items-center gap-2">
              <span>⚙️</span> CONDICIONES
              </h4>';

        $html .= '<div class="grid grid-cols-2 gap-3">';

        // Tasa de Interés
        $isCalculated = in_array('tasa_interes', $camposCalculados);
        if ($isCalculated) {
            if (in_array('monto_final', $camposCalculados) && $resultado2) {
                $displayValue = number_format($resultado2, 4).'%';
            } elseif (in_array('capital', $camposCalculados) && $resultado2) {
                $displayValue = number_format($resultado2, 4).'%';
            } else {
                $displayValue = number_format($resultado, 4).'%';
            }
        } else {
            $displayValue = is_numeric($tasaInteres) ? number_format($tasaInteres, 4).'%' : '--';
        }

        $html .= static::buildCard('Tasa de Interés', '📈', $displayValue, 'Según periodicidad', $isCalculated);

        // Tiempo
        $isCalculated = in_array('tiempo', $camposCalculados);
        if ($isCalculated) {
            if (in_array('capital', $camposCalculados) && $resultado2) {
                $displayValue = number_format($resultado2, 2);
            } elseif (in_array('monto_final', $camposCalculados) && $resultado2) {
                $displayValue = number_format($resultado2, 2);
            } else {
                $displayValue = number_format($resultado, 2);
            }
        } else {
            $displayValue = is_numeric($tiempo) ? number_format($tiempo, 2) : '--';
        }

        $html .= static::buildCard('Tiempo', '⏱️', $displayValue, 'Años', $isCalculated);

        $html .= '</div>';

        // Periodicidad y Frecuencia en línea horizontal
        $html .= '<div class="grid grid-cols-1 gap-3">';

        // Periodicidad
        if ($periodicidadTasa) {
            $periodicidadTexto = match ((int) $periodicidadTasa) {
                1 => 'Anual', 2 => 'Semestral', 4 => 'Trimestral', 6 => 'Bimestral',
                12 => 'Mensual', 24 => 'Quincenal', 52 => 'Semanal',
                360 => 'Diaria Comercial', 365 => 'Diaria',
                default => $periodicidadTasa.' veces/año'
            };

            $html .= "
            <div class='bg-indigo-50/70 dark:bg-indigo-950/30 rounded-lg p-2.5 border border-indigo-200 dark:border-indigo-800'>
                <div class='flex items-center justify-between'>
                    <div class='flex items-center gap-2'>
                        <span class='text-lg'>📊</span>
                        <span class='text-xs font-semibold text-indigo-900 dark:text-indigo-100'>Periodicidad de la tasa</span>
                    </div>
                    <div class='text-right'>
                        <span class='font-bold text-sm text-indigo-900 dark:text-indigo-100'>{$periodicidadTexto}</span>
                        <span class='text-xs text-indigo-600 dark:text-indigo-400 ml-2'>({$periodicidadTasa}/año)</span>
                    </div>
                </div>
            </div>
        ";
        }

        $html .= '</div>';
        $html .= '</div>'; // Fin condiciones

        // ============================================
        // BLOQUE 3: Interés Generado (Destacado)
        // ============================================
        if ($interesGenerado) {
            $html .= '<div class="bg-gradient-to-br from-amber-50 to-yellow-50 dark:from-amber-950/50 dark:to-yellow-950/50 rounded-xl p-4 border-2 border-amber-300 dark:border-amber-700">';
            $html .= '<h4 class="text-sm font-bold text-amber-900 dark:text-amber-100 mb-3 flex items-center gap-2">
                  <span>💎</span> GANANCIA
                  </h4>';

            $html .= '<div class="grid grid-cols-1 gap-3">';
            $html .= static::buildCard('Interés Generado', '💸', '$'.number_format($interesGenerado, 2), 'Ganancia total del período', true, 'amber');
            $html .= '</div>';
            $html .= '</div>'; // Fin interés generado
        }

        // ============================================
        // MENSAJE FINAL (Si existe)
        // ============================================
        if ($mensaje) {
            $html .= "
            <div class='bg-gradient-to-r from-blue-50 to-cyan-50 dark:from-blue-950/50 dark:to-cyan-950/50 rounded-xl p-4 border border-blue-300 dark:border-blue-700'>
                <div class='flex items-start gap-3'>
                    <span class='text-2xl flex-shrink-0'>🎯</span>
                    <div class='flex-1'>
                        <h4 class='font-bold text-blue-900 dark:text-blue-100 text-sm mb-1'>RESULTADO</h4>
                        <p class='text-sm text-blue-800 dark:text-blue-200 leading-relaxed'>{$mensaje}</p>
                    </div>
                </div>
            </div>
        ";
        }

        $html .= '</div>'; // Fin contenedor principal

        return new HtmlString($html);
    }

    private static function buildCard(string $title, string $icon, string $value, string $subtitle, bool $isCalculated, string $color = 'gray', string $p = 'p-6'): string
    {
        $colorClasses = match ($color) {
            'green' => ['bg' => 'bg-gradient-to-br from-green-50 to-emerald-50 border-green-300 dark:from-green-950/50 dark:to-emerald-950/50 dark:border-green-700',
                'text' => 'text-green-900 dark:text-green-100', 'badge' => 'bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-200',
                'subtitle' => 'text-green-600 dark:text-green-400'],
            'blue' => ['bg' => 'bg-gradient-to-br from-blue-50 to-cyan-50 border-blue-300 dark:from-blue-950/50 dark:to-cyan-950/50 dark:border-blue-700',
                'text' => 'text-blue-900 dark:text-blue-100', 'badge' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-200',
                'subtitle' => 'text-blue-600 dark:text-blue-400'],
            'purple' => ['bg' => 'bg-gradient-to-br from-purple-50 to-pink-50 border-purple-300 dark:from-purple-950/50 dark:to-pink-950/50 dark:border-purple-700',
                'text' => 'text-purple-900 dark:text-purple-100', 'badge' => 'bg-purple-100 text-purple-800 dark:bg-purple-900/50 dark:text-purple-200',
                'subtitle' => 'text-purple-600 dark:text-purple-400'],
            'red' => ['bg' => 'bg-gradient-to-br from-red-50 to-rose-50 border-red-300 dark:from-red-950/50 dark:to-rose-950/50 dark:border-red-700',
                'text' => 'text-red-900 dark:text-red-100', 'badge' => 'bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-200',
                'subtitle' => 'text-red-600 dark:text-red-400'],
            'orange' => ['bg' => 'bg-gradient-to-br from-orange-50 to-red-50 border-orange-300 dark:from-orange-950/50 dark:to-red-950/50 dark:border-orange-700',
                'text' => 'text-orange-900 dark:text-orange-100', 'badge' => 'bg-orange-100 text-orange-800 dark:bg-orange-900/50 dark:text-orange-200',
                'subtitle' => 'text-orange-600 dark:text-orange-400'],
            'cyan' => ['bg' => 'bg-gradient-to-br from-cyan-50 to-teal-50 border-cyan-300 dark:from-cyan-950/50 dark:to-teal-950/50 dark:border-cyan-700',
                'text' => 'text-cyan-900 dark:text-cyan-100', 'badge' => 'bg-cyan-100 text-cyan-800 dark:bg-cyan-900/50 dark:text-cyan-200',
                'subtitle' => 'text-cyan-600 dark:text-cyan-400'],
            'slate' => ['bg' => 'bg-gradient-to-br from-slate-50 to-gray-50 border-slate-300 dark:from-slate-950/50 dark:to-gray-950/50 dark:border-slate-700',
                'text' => 'text-slate-900 dark:text-slate-100', 'badge' => 'bg-slate-100 text-slate-800 dark:bg-slate-900/50 dark:text-slate-200',
                'subtitle' => 'text-slate-600 dark:text-slate-400'],
            'amber' => ['bg' => 'bg-gradient-to-br from-amber-50 to-orange-50 border-amber-300 dark:from-amber-950/50 dark:to-orange-950/50 dark:border-amber-700',
                'text' => 'text-amber-900 dark:text-amber-100', 'badge' => 'bg-amber-100 text-amber-800 dark:bg-amber-900/50 dark:text-amber-200',
                'subtitle' => 'text-amber-600 dark:text-amber-400'],
            default => ['bg' => 'bg-gray-50 border-gray-200 dark:bg-gray-900/50 dark:border-gray-700',
                'text' => 'text-gray-900 dark:text-gray-100', 'badge' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-200',
                'subtitle' => 'text-gray-600 dark:text-gray-400']
        };

        $badgeHtml = $isCalculated
            ? "<span class='px-3 py-1 text-xs font-medium rounded-full {$colorClasses['badge']}'>✨ Calculado</span>"
            : "<span class='px-3 py-1 text-xs font-medium rounded-full {$colorClasses['badge']}'>📝 Ingresado</span>";

        return "<div class='rounded-xl $p border {$colorClasses['bg']} shadow-sm'>
            <div class='flex items-center justify-between mb-3'>
                <h4 class='font-semibold {$colorClasses['text']} flex items-center gap-2'><span>{$icon}</span>{$title}</h4>
                {$badgeHtml}
            </div>
            <p class='text-2xl font-bold {$colorClasses['text']} mb-2'>{$value}</p>
            <p class='text-sm {$colorClasses['subtitle']}'>{$subtitle}</p>
        </div>";
    }

    /**
     * Método helper para calcular tiempo desde fechas
     */
}
