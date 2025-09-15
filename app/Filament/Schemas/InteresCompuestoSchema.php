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

class InteresCompuestoSchema
{
    public static function configure(Schema $schema): Schema
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
                                    Grid::make(2)->schema([
                                        TextInput::make('capital')
                                            ->rules(['nullable', 'numeric', 'min:0'])
                                            ->validationMessages([
                                                'min' => 'El capital inicial debe ser mayor o igual a 0',
                                            ])
                                            ->label('Capital Inicial (P)')
                                            ->numeric()
                                            ->prefix('$')
                                            ->placeholder('Ejemplo: 10000')
                                            ->hint('Monto inicial de inversión')
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
                                            ->hint('Valor final esperado')
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
                                            ->hint('Tasa nominal (%)')
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
                                            ->visible(fn(callable $get) => !$get('usar_select_periodicidad_tasa'))
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
                                            ->visible(fn(callable $get) => $get('usar_select_periodicidad_tasa'))
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
                                            ->live(onBlur: true)
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
                                                ->visible(fn(callable $get) => !$get('usar_fechas_tiempo'))
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
                                                ->visible(fn(callable $get) => !$get('usar_fechas_tiempo'))
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
                                                ->visible(fn(callable $get) => !$get('usar_fechas_tiempo'))
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
                                                ->visible(fn(callable $get) => !$get('usar_fechas_tiempo'))
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
                                            ->visible(fn(callable $get) => !$get('usar_fechas_tiempo')),


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
                                                ->visible(fn(callable $get) => $get('usar_fechas_tiempo')),

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
                                                ->visible(fn(callable $get) => $get('usar_fechas_tiempo')),

                                            Toggle::make('usar_fechas_tiempo')
                                                ->label('Usar fechas')
                                                ->default(false)
                                                ->live()
                                                ->extraAttributes(['class' => 'text-center items-center ml-4 mt-1'])
                                                ->inline(false)
                                                ->columnSpan(3)
                                                ->columnStart(10)
                                                ->visible(fn(callable $get) => $get('usar_fechas_tiempo'))
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
                                            ->visible(fn(callable $get) => $get('usar_fechas_tiempo')),

                                        TextInput::make('tiempo')
                                            ->label('Tiempo en Años')
                                            ->suffix('años')
                                            ->columnSpan(6)
                                            ->disabled()
                                    ])
                                ]),
                            Section::make('Frecuencia de Capitalización')
                                ->description('Configure con qué frecuencia se capitaliza el interés')
                                ->icon('heroicon-o-arrow-path')
                                ->schema([
                                    Grid::make(12)->schema([
                                        TextInput::make('frecuencia')
                                            ->rules(['nullable', 'integer', 'min:1'])
                                            ->validationMessages([
                                                'min' => 'La frecuencia debe ser mayor o igual a 1',
                                            ])
                                            ->label('Frecuencia (numérica)')
                                            ->numeric()
                                            ->placeholder('12 para mensual')
                                            ->hint('Veces por año')
                                            ->default(1)
                                            ->columnSpan(8)
                                            ->visible(fn(callable $get) => !$get('usar_select_frecuencia'))
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(function (callable $set) {
                                                $set('campo_calculado', null);
                                                $set('resultado_calculado', null);
                                                $set('interes_generado_calculado', null);
                                                $set('mensaje_calculado', null);
                                            }),

                                        Select::make('frecuencia')
                                            ->label('Frecuencia de Capitalización')
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
                                            ->columnSpan(8)
                                            ->visible(fn(callable $get) => $get('usar_select_frecuencia'))
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(function (callable $set) {
                                                $set('campo_calculado', null);
                                                $set('resultado_calculado', null);
                                                $set('interes_generado_calculado', null);
                                                $set('mensaje_calculado', null);
                                            }),

                                        Toggle::make('usar_select_frecuencia')
                                            ->label('Seleccionar frecuencia')
                                            ->default(true)
                                            ->live(onBlur: true)
                                            ->columnStart(10)
                                            ->extraAttributes(['class' => 'text-center items-center ml-12 mt-1'])
                                            ->inline(false)
                                            ->columnSpan(3)
                                            ->afterStateUpdated(function (callable $set) {
                                                $set('campo_calculado', null);
                                                $set('resultado_calculado', null);
                                                $set('interes_generado_calculado', null);
                                                $set('mensaje_calculado', null);
                                            }),
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
                                                // Tu código existente para mostrar resultados
                                                $capital = $get('capital');
                                                $montoFinal = $get('monto_final');
                                                $tasaInteres = $get('tasa_interes');
                                                $tiempo = $get('tiempo');

                                                $campoCalculado = $get('campo_calculado');
                                                $resultado = $get('resultado_calculado');
                                                $interesGenerado = $get('interes_generado_calculado');
                                                $mensaje = $get('mensaje_calculado');

                                                $frecuencia = $get('frecuencia') ?: 12;
                                                $periodicidadTasa = $get('periodicidad_tasa') ?: 1;

                                                // Resto del código para mostrar resultados...
                                                // (Mantén tu lógica existente aquí)

                                                // Contar campos vacíos
                                                $emptyFields = [];
                                                foreach (['capital', 'monto_final', 'tasa_interes', 'tiempo'] as $field) {
                                                    $value = $get($field);
                                                    if ($value === null || $value === '' || $value === 0) {
                                                        $emptyFields[] = $field;
                                                    }
                                                }

                                                // Si hay un cálculo exitoso, mostrar resultados
                                                if ($campoCalculado && $resultado) {
                                                    return static::buildResultHtml(
                                                        $capital, $montoFinal, $tasaInteres, $tiempo,
                                                        $periodicidadTasa, $frecuencia, $interesGenerado,
                                                        $mensaje, $campoCalculado, $resultado
                                                    );
                                                }

                                                // Resto de la lógica de validación y mensajes...
                                                // (Mantén tu código existente)

                                                if (empty($capital) && empty($montoFinal) && empty($tasaInteres) && empty($tiempo)) {
                                                    return new HtmlString('
                                            <div class="text-center py-12 text-gray-500 dark:text-gray-400">
                                                <div class="text-5xl mb-4">📈</div>
                                                <h3 class="text-xl font-semibold mb-2">Complete los campos para ver los detalles</h3>
                                                <p class="text-sm text-gray-400">Los resultados aparecerán aquí después del cálculo</p>
                                            </div>
                                        ');
                                                }

                                                // Validación: debe haber exactamente un campo vacío
                                                if (count($emptyFields) !== 1) {
                                                    $errorMessage = count($emptyFields) === 0
                                                        ? 'Debes dejar exactamente un campo vacío para calcular.'
                                                        : 'Solo un campo puede estar vacío. Actualmente hay ' . count($emptyFields) . ' campos vacíos.';

                                                    return new HtmlString('
                                            <div class="text-center py-12">
                                                <div class="bg-gradient-to-r from-red-50 to-orange-50 dark:from-red-950/50 dark:to-orange-950/50 rounded-xl p-8 border border-red-200 dark:border-red-800">
                                                    <div class="text-6xl mb-4">⚠️</div>
                                                    <h3 class="text-xl font-bold text-red-900 dark:text-red-100 mb-3">Error de Validación</h3>
                                                    <p class="text-red-700 dark:text-red-300 mb-4 text-lg">' . $errorMessage . '</p>
                                                    <div class="bg-red-100 dark:bg-red-900/50 rounded-lg p-4 border border-red-300 dark:border-red-700">
                                                        <p class="text-sm text-red-800 dark:text-red-200">
                                                            <strong>Instrucciones:</strong><br>
                                                            • Completa todos los campos conocidos<br>
                                                            • Deja vacío únicamente el campo que deseas calcular<br>
                                                            • Presiona el botón "Calcular" para obtener el resultado
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        ');
                                                }

                                                // Si hay exactamente un campo vacío pero aún no se ha calculado
                                                return new HtmlString('
                                        <div class="text-center py-12 text-gray-500 dark:text-gray-400">
                                            <div class="text-5xl mb-4">⏳</div>
                                            <h3 class="text-xl font-semibold mb-2">Listo para calcular</h3>
                                            <p class="text-sm text-gray-400">Presiona el botón "Calcular" para ver los resultados</p>
                                        </div>
                                    ');
                                            }),
                                    ]),
                                ]),
                        ])
                ])
                    ->skippable()
                    ->startOnStep(1)
                    ->contained(false)
                    ->submitAction(new HtmlString(Blade::render(<<<BLADE
                            <div class="items-center space-x-4">
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
                            </div>
                    BLADE
                    ))),
            ]);
    }

    // Mantén tu método buildResultHtml existente aquí...
    protected static function buildResultHtml(
        $capital, $montoFinal, $tasaInteres, $tiempo,
        $periodicidadTasa, $frecuencia, $interesGenerado,
        $mensaje, $campoCalculado, $resultado
    ): Htmlable
    {
        $html = '<div class="space-y-6">';

        // Header con título dinámico
        $html .= '
            <div class="bg-gradient-to-r from-blue-50 to-indigo-200 dark:from-blue-950/50 dark:to-indigo-700/50 rounded-xl p-6 border border-blue-200 dark:border-blue-800">
                <h3 class="text-xl font-bold text-blue-900 dark:text-blue-100 flex items-center gap-3">
                    <span class="text-3xl">💰</span>
                    <div>
                        <div>Resumen de Interés Compuesto</div>
                        <div class="text-sm font-normal text-blue-600 dark:text-blue-300">Cálculos financieros completados</div>
                    </div>
                </h3>
            </div>
        ';

        // Grid de valores principales
        $html .= '<div class="grid grid-cols-1 md:grid-cols-2 gap-6">';

        // Capital Inicial
        $isCalculated = $campoCalculado === 'capital';
        $bgClass = $isCalculated ? 'bg-gradient-to-br from-green-50 to-emerald-50 border-green-300 dark:from-green-950/50 dark:to-emerald-950/50 dark:border-green-700' : 'bg-gray-50 border-gray-200 dark:bg-gray-900/50 dark:border-gray-700';
        $textClass = $isCalculated ? 'text-green-900 dark:text-green-100' : 'text-gray-900 dark:text-gray-100';
        $badgeClass = $isCalculated ? 'bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-200' : 'bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-200';

        $displayValue = $isCalculated
            ? number_format($resultado, 2)
            : (is_numeric($capital) ? number_format($capital, 2) : '--');

        $html .= "
            <div class='rounded-xl p-6 border {$bgClass} shadow-sm'>
                <div class='flex items-center justify-between mb-3'>
                    <h4 class='font-semibold {$textClass} flex items-center gap-2'>
                        <span>💵</span>
                        Capital Inicial
                    </h4>
                    " . ($isCalculated ? "<span class='px-3 py-1 text-xs font-medium rounded-full {$badgeClass}'>✨ Calculado</span>" : "<span class='px-3 py-1 text-xs font-medium rounded-full {$badgeClass}'>📝 Ingresado</span>") . "
                </div>
                <p class='text-3xl font-bold {$textClass} mb-2'>{$displayValue}</p>
                <p class='text-sm text-gray-600 dark:text-gray-400'>Inversión inicial</p>
            </div>
        ";

        // Monto Final
        $isCalculated = $campoCalculado === 'monto_final';
        $bgClass = $isCalculated ? 'bg-gradient-to-br from-green-50 to-emerald-50 border-green-300 dark:from-green-950/50 dark:to-emerald-950/50 dark:border-green-700' : 'bg-gray-50 border-gray-200 dark:bg-gray-900/50 dark:border-gray-700';
        $textClass = $isCalculated ? 'text-green-900 dark:text-green-100' : 'text-gray-900 dark:text-gray-100';
        $badgeClass = $isCalculated ? 'bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-200' : 'bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-200';

        $displayValue = $isCalculated
            ? number_format($resultado, 2)
            : (is_numeric($montoFinal) ? number_format($montoFinal, 2) : '--');

        $html .= "
            <div class='rounded-xl p-6 border {$bgClass} shadow-sm'>
                <div class='flex items-center justify-between mb-3'>
                    <h4 class='font-semibold {$textClass} flex items-center gap-2'>
                        <span>🎯</span>
                        Monto Final
                    </h4>
                    " . ($isCalculated ? "<span class='px-3 py-1 text-xs font-medium rounded-full {$badgeClass}'>✨ Calculado</span>" : "<span class='px-3 py-1 text-xs font-medium rounded-full {$badgeClass}'>📝 Ingresado</span>") . "
                </div>
                <p class='text-3xl font-bold {$textClass} mb-2'>{$displayValue}</p>
                <p class='text-sm text-gray-600 dark:text-gray-400'>Valor al vencimiento</p>
            </div>
        ";

        // Tasa de Interés
        $isCalculated = $campoCalculado === 'tasa_interes';
        $isCalculated = smartRound($isCalculated);

        $bgClass = $isCalculated ? 'bg-gradient-to-br from-green-50 to-emerald-50 border-green-300 dark:from-green-950/50 dark:to-emerald-950/50 dark:border-green-700' : 'bg-gray-50 border-gray-200 dark:bg-gray-900/50 dark:border-gray-700';
        $textClass = $isCalculated ? 'text-green-900 dark:text-green-100' : 'text-gray-900 dark:text-gray-100';
        $badgeClass = $isCalculated ? 'bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-200' : 'bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-200';

        $displayValue = $isCalculated
            ? $resultado . '%'
            : (is_numeric($tasaInteres) ? $tasaInteres . '%' : '--');

        $html .= "
            <div class='rounded-xl p-6 border {$bgClass} shadow-sm'>
                <div class='flex items-center justify-between mb-3'>
                    <h4 class='font-semibold {$textClass} flex items-center gap-2'>
                        <span>📈</span>
                        Tasa de Interés
                    </h4>
                    " . ($isCalculated ? "<span class='px-3 py-1 text-xs font-medium rounded-full {$badgeClass}'>✨ Calculado</span>" : "<span class='px-3 py-1 text-xs font-medium rounded-full {$badgeClass}'>📝 Ingresado</span>") . "
                </div>
                <p class='text-3xl font-bold {$textClass} mb-2'>{$displayValue}</p>
                <p class='text-sm text-gray-600 dark:text-gray-400'>Según periodicidad seleccionada</p>
            </div>
        ";

        // Tiempo
        $isCalculated = $campoCalculado === 'tiempo';
        $isCalculated = smartRound($isCalculated);

        $bgClass = $isCalculated ? 'bg-gradient-to-br from-green-50 to-emerald-50 border-green-300 dark:from-green-950/50 dark:to-emerald-950/50 dark:border-green-700' : 'bg-gray-50 border-gray-200 dark:bg-gray-900/50 dark:border-gray-700';
        $textClass = $isCalculated ? 'text-green-900 dark:text-green-100' : 'text-gray-900 dark:text-gray-100';
        $badgeClass = $isCalculated ? 'bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-200' : 'bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-200';
        $displayValue = $isCalculated
            ? $resultado . ' años'
            : (is_numeric($tiempo) ? $tiempo . ' años' : '--');
        $html .= "
            <div class='rounded-xl p-6 border {$bgClass} shadow-sm'>
                <div class='flex items-center justify-between mb-3'>
                    <h4 class='font-semibold {$textClass} flex items-center gap-2'>
                        <span>⏰</span>
                        Tiempo
                    </h4>
                    " . ($isCalculated ? "<span class='px-3 py-1 text-xs font-medium rounded-full {$badgeClass}'>✨ Calculado</span>" : "<span class='px-3 py-1 text-xs font-medium rounded-full {$badgeClass}'>📝 Ingresado</span>") . "
                </div>
                <p class='text-3xl font-bold {$textClass} mb-2'>{$displayValue}</p>
                <p class='text-sm text-gray-600 dark:text-gray-400'>Período de inversión</p>
            </div>
        ";

        $html .= '</div>'; // Fin del grid principal

        // Información adicional si hay datos
        if ($periodicidadTasa || $frecuencia || $interesGenerado) {
            $html .= '<div class="grid grid-cols-1 md:grid-cols-3 gap-4">';

            // Periodicidad de la tasa
            if ($periodicidadTasa) {
                $periodicidadTexto = match ((int)$periodicidadTasa) {
                    1 => 'Anual',
                    2 => 'Semestral',
                    4 => 'Trimestral',
                    6 => 'Bimestral',
                    12 => 'Mensual',
                    24 => 'Quincenal',
                    52 => 'Semanal',
                    360 => 'Diaria Comercial',
                    365 => 'Diaria',
                    default => $periodicidadTasa . ' veces/año'
                };

                $html .= "
                    <div class='rounded-lg p-4 border bg-indigo-50 border-indigo-200 dark:bg-indigo-950/50 dark:border-indigo-700 shadow-sm'>
                        <div class='flex items-center gap-2 mb-2'>
                            <span class='text-indigo-600 dark:text-indigo-400'>📊</span>
                            <h4 class='font-semibold text-indigo-900 dark:text-indigo-100 text-sm'>Periodicidad Tasa</h4>
                        </div>
                        <p class='text-lg font-bold text-indigo-900 dark:text-indigo-100'>{$periodicidadTexto}</p>
                        <p class='text-xs text-indigo-600 dark:text-indigo-400'>{$periodicidadTasa} períodos/año</p>
                    </div>
                ";
            }

            // Frecuencia de capitalización
            if ($frecuencia) {
                $frecuenciaTexto = match ((int)$frecuencia) {
                    1 => 'Anual',
                    2 => 'Semestral',
                    4 => 'Trimestral',
                    6 => 'Bimestral',
                    12 => 'Mensual',
                    24 => 'Quincenal',
                    52 => 'Semanal',
                    365 => 'Diaria',
                    360 => 'Diaria Comercial',
                    default => $frecuencia . ' veces/año'
                };

                $html .= "
                    <div class='rounded-lg p-4 border bg-purple-50 border-purple-200 dark:bg-purple-950/50 dark:border-purple-700 shadow-sm'>
                        <div class='flex items-center gap-2 mb-2'>
                            <span class='text-purple-600 dark:text-purple-400'>🔄</span>
                            <h4 class='font-semibold text-purple-900 dark:text-purple-100 text-sm'>Capitalización</h4>
                        </div>
                        <p class='text-lg font-bold text-purple-900 dark:text-purple-100'>{$frecuenciaTexto}</p>
                        <p class='text-xs text-purple-600 dark:text-purple-400'>{$frecuencia} veces/año</p>
                    </div>
                ";
            }

            // Interés generado
            if ($interesGenerado) {
                $html .= "
                    <div class='rounded-lg p-4 border bg-amber-50 border-amber-200 dark:bg-amber-950/50 dark:border-amber-700 shadow-sm'>
                        <div class='flex items-center gap-2 mb-2'>
                            <span class='text-amber-600 dark:text-amber-400'>💎</span>
                            <h4 class='font-semibold text-amber-900 dark:text-amber-100 text-sm'>Interés Generado</h4>
                        </div>
                        <p class='text-lg font-bold text-amber-900 dark:text-amber-100'>$" . number_format($interesGenerado, 2) . "</p>
                        <p class='text-xs text-amber-600 dark:text-amber-400'>Ganancia total</p>
                    </div>
                ";
            }

            $html .= '</div>';
        }

        // Mensaje de resultado si existe
        if ($mensaje) {
            $html .= "
                <div class='bg-gradient-to-r from-blue-50 to-purple-50 dark:from-blue-950/50 dark:to-purple-950/50 rounded-xl p-6 border border-blue-200 dark:border-blue-700 shadow-sm'>
                    <div class='flex items-start gap-4'>
                        <div class='flex-shrink-0 text-3xl'>🎯</div>
                        <div>
                            <h4 class='font-bold text-blue-900 dark:text-blue-100 mb-2 text-lg'>Resultado del Cálculo</h4>
                            <p class='text-blue-800 dark:text-blue-200 leading-relaxed'>{$mensaje}</p>
                        </div>
                    </div>
                </div>
            ";
        }

        $html .= '</div>'; // Fin del contenedor principal

        return new HtmlString($html);
    }
}
