<?php

namespace App\Filament\Schemas;

use Blade;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Schemas\Schema;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;

class TasaInternaRetornoSchema
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Hidden::make('campos_calculados'),
                Hidden::make('resultados_calculados'),
                Hidden::make('tabla_flujos'),
                Hidden::make('mensaje_calculado'),
                Hidden::make('numero_periodos'),
                Hidden::make('tiempo'),

                Wizard::make([
                    // Paso 1: Inversión Inicial
                    Step::make('Inversión Inicial')
                        ->icon('heroicon-o-banknotes')
                        ->completedIcon('heroicon-s-banknotes')
                        ->schema([
                            Section::make('Inversión Inicial del Proyecto')
                                ->icon('heroicon-o-banknotes')
                                ->description('Ingrese la inversión inicial y seleccione el tipo de cálculo')
                                ->schema([
                                    Grid::make(2)->schema([
                                        Select::make('tipo_calculo')
                                            ->label('Tipo de Cálculo')
                                            ->options([
                                                'tir' => '📊 TIR - Tasa Interna de Retorno',
                                                'tirm' => '📈 TIRM - Tasa Interna de Retorno Modificada',
                                            ])
                                            ->default('tir')
                                            ->required()
                                            ->searchable()
                                            ->helperText('TIRM es más precisa cuando hay múltiples inversiones o cambios de signo')
                                            ->live()
                                            ->afterStateUpdated(function (callable $set) {
                                                $set('campos_calculados', null);
                                                $set('resultados_calculados', null);
                                                $set('tabla_flujos', null);
                                                $set('mensaje_calculado', null);
                                            })
                                            ->columnSpan(1),
                                        Select::make('tipo_flujo')
                                            ->label('Tipo de Flujos')
                                            ->options([
                                                'constantes' => '📊 Flujos Constantes',
                                                'variables' => '📈 Flujos Variables (Ingresar manualmente)',
                                            ])
                                            ->default('constantes')
                                            ->required()
                                            ->searchable()
                                            ->helperText('Seleccione si los flujos son iguales o diferentes')
                                            ->live()
                                            ->afterStateUpdated(function (callable $set) {
                                                $set('campos_calculados', null);
                                                $set('resultados_calculados', null);
                                                $set('tabla_flujos', null);
                                                $set('mensaje_calculado', null);
                                            })
                                            ->columnSpan(1),
                                        TextInput::make('inversion_inicial')
                                            ->rules(['nullable', 'numeric'])
                                            ->label('Inversión Inicial (I₀)')
                                            ->numeric()
                                            ->prefix('$')
                                            ->placeholder('Ejemplo: 1000')
                                            ->helperText('Ingresa el valor de tu inversion inicial, este sera descontado en el flujo inicial.')
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(function (callable $set) {
                                                $set('campos_calculados', null);
                                                $set('resultados_calculados', null);
                                                $set('tabla_flujos', null);
                                                $set('mensaje_calculado', null);
                                            })
                                            ->columnSpan(2),

                                        // Campos adicionales para TIRM
                                        TextInput::make('tasa_financiamiento')
                                            ->rules(['nullable', 'numeric'])
                                            ->label('Tasa de Financiamiento')
                                            ->numeric()
                                            ->suffix('%')
                                            ->placeholder('Ejemplo: 8')
                                            ->helperText('Costo de capital o tasa de préstamo')
                                            ->visible(fn (callable $get) => $get('tipo_calculo') === 'tirm')
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(function (callable $set) {
                                                $set('campos_calculados', null);
                                                $set('resultados_calculados', null);
                                                $set('tabla_flujos', null);
                                                $set('mensaje_calculado', null);
                                            }),

                                        TextInput::make('tasa_reinversion')
                                            ->rules(['nullable', 'numeric'])
                                            ->label('Tasa de Reinversión')
                                            ->numeric()
                                            ->suffix('%')
                                            ->placeholder('Ejemplo: 12')
                                            ->helperText('Tasa a la que se pueden reinvertir los flujos positivos')
                                            ->visible(fn (callable $get) => $get('tipo_calculo') === 'tirm')
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(function (callable $set) {
                                                $set('campos_calculados', null);
                                                $set('resultados_calculados', null);
                                                $set('tabla_flujos', null);
                                                $set('mensaje_calculado', null);
                                            }),

                                    ]),
                                ]),
                        ]),

                    // Paso 2: Flujos de Caja
                    Step::make('Flujos de Caja')
                        ->icon('heroicon-o-arrow-trending-up')
                        ->completedIcon('heroicon-s-arrow-trending-up')
                        ->schema([
                            Section::make('Configuración de Flujos de Caja')
                                ->icon('heroicon-o-arrow-trending-up')
                                ->description('Configure los flujos de caja del proyecto')
                                ->schema([
                                    // Flujos Constantes
                                    Grid::make(1)->schema([
                                        TextInput::make('flujo_constante')
                                            ->rules(['nullable', 'numeric'])
                                            ->label('Flujo de Caja Constante')
                                            ->numeric()
                                            ->prefix('$')
                                            ->placeholder('Ejemplo: 25000')
                                            ->helperText('Valor que se repite cada período')
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(function (callable $set) {
                                                $set('campos_calculados', null);
                                                $set('resultados_calculados', null);
                                                $set('tabla_flujos', null);
                                                $set('mensaje_calculado', null);
                                            }),

                                    ])->visible(fn (callable $get) => $get('tipo_flujo') === 'constantes'),

                                    // Flujos Variables
                                    Grid::make(1)->schema([
                                        Repeater::make('flujos_variables')
                                            ->label('Flujos de Caja por Período')
                                            ->schema([
                                                Grid::make(2)->schema([
                                                    TextInput::make('periodo')
                                                        ->label('Período')
                                                        ->disabled()
                                                        ->dehydrated(),

                                                    TextInput::make('flujo')
                                                        ->rules(['required', 'numeric'])
                                                        ->label('Flujo de Caja')
                                                        ->numeric()
                                                        ->prefix('$')
                                                        ->placeholder('Ejemplo: 25000')
                                                        ->required()
                                                        ->live(onBlur: true)
                                                        ->afterStateUpdated(function (callable $set) {
                                                            $set('../../campos_calculados', null);
                                                            $set('../../resultados_calculados', null);
                                                            $set('../../tabla_flujos', null);
                                                            $set('../../mensaje_calculado', null);
                                                        }),
                                                ]),
                                            ])
                                            ->defaultItems(3)
                                            ->addActionLabel('Agregar período')
                                            ->collapsible()
                                            ->itemLabel(fn (array $state): ?string => 'Período '.($state['periodo'] ?? ''))
                                            ->reorderable()
                                            ->reorderableWithButtons()
                                            ->afterStateHydrated(function (callable $set, callable $get, $state) {
                                                // Numerar los items por defecto al cargar
                                                if (is_array($state)) {
                                                    $keys = array_keys($state);
                                                    foreach ($keys as $index => $key) {
                                                        $set("flujos_variables.{$key}.periodo", $index + 1);
                                                    }
                                                }
                                            })
                                            ->afterStateUpdated(function (callable $set, callable $get, $state) {
                                                // Renumerar todos los períodos después de cualquier cambio
                                                if (is_array($state)) {
                                                    $keys = array_keys($state);
                                                    foreach ($keys as $index => $key) {
                                                        $set("flujos_variables.{$key}.periodo", $index + 1);
                                                    }
                                                }

                                                $set('campos_calculados', null);
                                                $set('resultados_calculados', null);
                                                $set('tabla_flujos', null);
                                                $set('mensaje_calculado', null);
                                            }),
                                    ])->visible(fn (callable $get) => $get('tipo_flujo') === 'variables'),                                ])
                                ->collapsible(),
                            // Subsección para configurar número de períodos en flujo constante

                            Section::make('Configuración del Número de Períodos')
                                ->icon('heroicon-o-clock')
                                ->description('Determine el número de períodos del proyecto')
                                ->schema([
                                    Select::make('modo_tiempo_pagos')
                                        ->label('Método para determinar número de períodos')
                                        ->options([
                                            'manual' => 'Ingresar número de períodos directamente',
                                            'anios_frecuencia' => 'Calcular con tiempo y frecuencia',
                                            'fechas_frecuencia' => 'Calcular desde fechas y frecuencia',
                                        ])
                                        ->default('manual')
                                        ->live()
                                        ->searchable()
                                        ->afterStateUpdated(function (callable $set) {
                                            $set('tiempo', null);
                                            $set('fecha_inicio', null);
                                            $set('fecha_final', null);
                                            $set('anio', null);
                                            $set('mes', null);
                                            $set('dia', null);
                                            $set('numero_periodos', null);
                                            $set('campos_calculados', null);
                                            $set('resultados_calculados', null);
                                            $set('tabla_flujos', null);
                                            $set('mensaje_calculado', null);
                                        }),

                                    // MODO MANUAL
                                    TextInput::make('numero_periodos')
                                        ->rules(['nullable', 'integer', 'min:1'])
                                        ->validationMessages([
                                            'min' => 'El número de períodos debe ser mayor o igual a 1',
                                        ])
                                        ->label('Número de Períodos')
                                        ->numeric()
                                        ->placeholder('Ejemplo: 5')
                                        ->helperText('Cantidad de períodos del proyecto')
                                        ->visible(fn (callable $get) => $get('modo_tiempo_pagos') === 'manual')
                                        ->live(onBlur: true)
                                        ->afterStateUpdated(function (callable $set) {
                                            $set('campos_calculados', null);
                                            $set('resultados_calculados', null);
                                            $set('tabla_flujos', null);
                                            $set('mensaje_calculado', null);
                                        }),

                                    // MODO AÑOS + FRECUENCIA
                                    Grid::make(12)->schema([
                                        TextInput::make('numero_periodos_calculado_anios')
                                            ->label('Número de Períodos Calculado')
                                            ->disabled()
                                            ->columnSpan(12)
                                            ->hint('Tiempo x Frecuencia de períodos'),

                                        Fieldset::make('Tiempo')->schema([
                                            TextInput::make('anio')
                                                ->rules(['nullable', 'numeric', 'min:0'])
                                                ->validationMessages([
                                                    'min' => 'El tiempo debe ser mayor o igual a 0',
                                                ])
                                                ->label('Años')
                                                ->numeric()
                                                ->suffix('años')
                                                ->placeholder('Ejemplo: 5')
                                                ->step(0.01)
                                                ->live()
                                                ->afterStateUpdated(function (callable $set, callable $get) {
                                                    static::calcularTiempo($set, $get);
                                                    static::calcularNumeroPeriodos($set, $get);
                                                    $set('campos_calculados', null);
                                                    $set('resultados_calculados', null);
                                                    $set('tabla_flujos', null);
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
                                                ->step(0.01)
                                                ->live()
                                                ->afterStateUpdated(function (callable $set, callable $get) {
                                                    static::calcularTiempo($set, $get);
                                                    static::calcularNumeroPeriodos($set, $get);
                                                    $set('campos_calculados', null);
                                                    $set('resultados_calculados', null);
                                                    $set('tabla_flujos', null);
                                                    $set('mensaje_calculado', null);
                                                }),

                                            TextInput::make('dia')
                                                ->rules(['nullable', 'numeric', 'min:0'])
                                                ->validationMessages([
                                                    'min' => 'El tiempo debe ser mayor o igual a 0',
                                                ])
                                                ->label('Días')
                                                ->numeric()
                                                ->suffix('días')
                                                ->placeholder('Ejemplo: 21')
                                                ->step(0.01)
                                                ->live()
                                                ->afterStateUpdated(function (callable $set, callable $get) {
                                                    static::calcularTiempo($set, $get);
                                                    static::calcularNumeroPeriodos($set, $get);
                                                    $set('campos_calculados', null);
                                                    $set('resultados_calculados', null);
                                                    $set('tabla_flujos', null);
                                                    $set('mensaje_calculado', null);
                                                }),

                                            TextInput::make('tiempo')
                                                ->label('Tiempo calculado')
                                                ->suffix('años')
                                                ->disabled(),
                                        ])
                                            ->columns(['default' => 1, 'md' => 2, 'xl' => 4])
                                            ->columnSpan(12),

                                        Fieldset::make('Frecuencia')->schema([
                                            Select::make('frecuencia_anios')
                                                ->label('Frecuencia de Pagos')
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
                                                ->default(12)
                                                ->searchable()
                                                ->visible(fn (callable $get) => $get('usar_select_frecuencia'))
                                                ->live()
                                                ->afterStateUpdated(function (callable $set, callable $get) {
                                                    static::calcularNumeroPeriodos($set, $get);
                                                    $set('campos_calculados', null);
                                                    $set('resultados_calculados', null);
                                                    $set('tabla_flujos', null);
                                                    $set('mensaje_calculado', null);
                                                }),

                                            TextInput::make('frecuencia_anios')
                                                ->rules(['nullable', 'integer', 'min:1'])
                                                ->validationMessages([
                                                    'min' => 'La frecuencia debe ser mayor o igual a 1',
                                                ])
                                                ->label('Frecuencia (numérica)')
                                                ->numeric()
                                                ->placeholder('12 para mensual')
                                                ->hint('Veces por año')
                                                ->default(12)
                                                ->visible(fn (callable $get) => ! $get('usar_select_frecuencia'))
                                                ->live()
                                                ->afterStateUpdated(function (callable $set, callable $get) {
                                                    static::calcularNumeroPeriodos($set, $get);
                                                    $set('campos_calculados', null);
                                                    $set('resultados_calculados', null);
                                                    $set('tabla_flujos', null);
                                                    $set('mensaje_calculado', null);
                                                }),

                                            Toggle::make('usar_select_frecuencia')
                                                ->label('Seleccionar frecuencia')
                                                ->default(true)
                                                ->live()
                                                ->extraAttributes(['class' => 'text-center items-center ml-12 mt-1'])
                                                ->inline(false)
                                                ->afterStateUpdated(function (callable $set) {
                                                    $set('campos_calculados', null);
                                                    $set('resultados_calculados', null);
                                                    $set('tabla_flujos', null);
                                                    $set('mensaje_calculado', null);
                                                }),
                                        ])
                                            ->columns(['default' => 1, 'md' => 2])
                                            ->columnSpan(12),

                                        TextEntry::make('Nota')
                                            ->columnSpan(12)
                                            ->icon('heroicon-o-information-circle')
                                            ->state('Se utilizan gran cantidad de decimales al calcular el tiempo para soportar altas frecuencias de períodos.'),
                                    ])->visible(fn (callable $get) => $get('modo_tiempo_pagos') === 'anios_frecuencia'),

                                    // MODO FECHAS + FRECUENCIA
                                    Grid::make(12)->schema([
                                        TextInput::make('numero_periodos')
                                            ->label('Número de Períodos Calculado')
                                            ->disabled()
                                            ->columnSpan(12)
                                            ->hint('Tiempo x Frecuencia de períodos'),

                                        Fieldset::make('Fechas')->schema([
                                            DatePicker::make('fecha_inicio')
                                                ->label('Fecha de Inicio')
                                                ->placeholder('Seleccione la fecha inicial')
                                                ->live()
                                                ->afterStateUpdated(function (callable $set, callable $get) {
                                                    static::calcularTiempoDesdeFechas($set, $get);
                                                    static::calcularNumeroPeriodos($set, $get);
                                                    $set('campos_calculados', null);
                                                    $set('resultados_calculados', null);
                                                    $set('tabla_flujos', null);
                                                    $set('mensaje_calculado', null);
                                                }),

                                            DatePicker::make('fecha_final')
                                                ->label('Fecha Final')
                                                ->placeholder('Seleccione la fecha final')
                                                ->live()
                                                ->afterStateUpdated(function (callable $set, callable $get) {
                                                    static::calcularTiempoDesdeFechas($set, $get);
                                                    static::calcularNumeroPeriodos($set, $get);
                                                    $set('campos_calculados', null);
                                                    $set('resultados_calculados', null);
                                                    $set('tabla_flujos', null);
                                                    $set('mensaje_calculado', null);
                                                }),

                                            TextInput::make('tiempo')
                                                ->label('Tiempo calculado')
                                                ->suffix('años')
                                                ->disabled(),
                                        ])
                                            ->columns(['default' => 1, 'lg' => 3])
                                            ->columnSpan(12),

                                        Fieldset::make('Frecuencia')->schema([
                                            Select::make('frecuencia_fechas')
                                                ->label('Frecuencia de Pagos')
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
                                                ->default(12)
                                                ->searchable()
                                                ->visible(fn (callable $get) => $get('usar_select_frecuencia'))
                                                ->live()
                                                ->afterStateUpdated(function (callable $set, callable $get) {
                                                    static::calcularNumeroPeriodos($set, $get);
                                                    $set('campos_calculados', null);
                                                    $set('resultados_calculados', null);
                                                    $set('tabla_flujos', null);
                                                    $set('mensaje_calculado', null);
                                                }),

                                            TextInput::make('frecuencia_fechas')
                                                ->rules(['nullable', 'integer', 'min:1'])
                                                ->validationMessages([
                                                    'min' => 'La frecuencia debe ser mayor o igual a 1',
                                                ])
                                                ->label('Frecuencia (numérica)')
                                                ->numeric()
                                                ->placeholder('12 para mensual')
                                                ->hint('Veces por año')
                                                ->default(12)
                                                ->visible(fn (callable $get) => ! $get('usar_select_frecuencia'))
                                                ->live()
                                                ->afterStateUpdated(function (callable $set, callable $get) {
                                                    static::calcularNumeroPeriodos($set, $get);
                                                    $set('campos_calculados', null);
                                                    $set('resultados_calculados', null);
                                                    $set('tabla_flujos', null);
                                                    $set('mensaje_calculado', null);
                                                }),

                                            Toggle::make('usar_select_frecuencia')
                                                ->label('Seleccionar frecuencia')
                                                ->default(true)
                                                ->live()
                                                ->extraAttributes(['class' => 'text-center items-center ml-12 mt-1'])
                                                ->inline(false)
                                                ->afterStateUpdated(function (callable $set) {
                                                    $set('campos_calculados', null);
                                                    $set('resultados_calculados', null);
                                                    $set('tabla_flujos', null);
                                                    $set('mensaje_calculado', null);
                                                }),
                                        ])
                                            ->columns(['default' => 1, 'md' => 2])
                                            ->columnSpan(12),

                                        TextEntry::make('Nota')
                                            ->columnSpan(12)
                                            ->icon('heroicon-o-information-circle')
                                            ->state('Se utilizan gran cantidad de decimales al calcular el tiempo para soportar altas frecuencias de períodos.'),
                                    ])->visible(fn (callable $get) => $get('modo_tiempo_pagos') === 'fechas_frecuencia'),
                                ])
                                ->visible(fn (callable $get) => $get('tipo_flujo') === 'constantes')
                                ->collapsible(),
                        ]),

                    // Paso 3: Tasa de Descuento
                    Step::make('Tasa de Descuento')
                        ->icon('heroicon-o-percent-badge')
                        ->completedIcon('heroicon-s-percent-badge')
                        ->schema([
                            Section::make('Configuración de Tasa de Descuento')
                                ->icon('heroicon-o-percent-badge')
                                ->description('Configure la tasa de descuento para el cálculo del VPN (dejar vacío para calcular TIR)')
                                ->schema([
                                    Grid::make(2)->schema([
                                        TextInput::make('tasa_descuento')
                                            ->label('Tasa de Descuento')
                                            ->suffix('%')
                                            ->step(0.01)
                                            ->placeholder('Ejemplo: 10.5 (dejar vacío para calcular TIR)')
                                            ->hint('Tasa de oportunidad (%)')
                                            ->helperText('Dejar vacío si deseas calcular la TIR')
                                            ->columnSpan(1)
                                            ->live(onBlur: true)
                                            ->rules([
                                                'nullable',
                                                'regex:/^\d+(\.\d{1,4})?$/', // Acepta decimales con hasta 4 dígitos
                                                'min:0',
                                            ])
                                            ->validationMessages([
                                                'regex' => 'La tasa de descuento debe ser un número válido con hasta 4 decimales',
                                                'min' => 'La tasa de descuento debe ser mayor o igual a 0',
                                            ])
                                            ->afterStateUpdated(function (callable $set) {
                                                $set('campos_calculados', null);
                                                $set('resultados_calculados', null);
                                                $set('tabla_flujos', null);
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
                            Section::make('Resumen del Análisis TIR/VPN')
                                ->collapsible()
                                ->icon('heroicon-o-chart-bar')
                                ->description('Resultados completos del análisis de inversión')
                                ->schema([
                                    Grid::make(1)->schema([
                                        Placeholder::make('_')
                                            ->label('')
                                            ->content(function (callable $get): Htmlable {
                                                return static::buildResultHtml($get);
                                            }),
                                    ]),
                                ]),

                            Section::make('Tabla de Flujos de Caja')
                                ->collapsible()
                                ->collapsed()
                                ->icon('heroicon-o-table-cells')
                                ->description('Detalle período por período de los flujos de caja y su valor presente')
                                ->schema([
                                    Grid::make(1)->schema([
                                        Placeholder::make('_')
                                            ->label('')
                                            ->content(function (callable $get): Htmlable {
                                                return static::buildTablaFlujosHtml($get);
                                            }),
                                    ]),
                                ]),
                        ]),
                ])->skippable()
                    ->startOnStep(1)
                    ->contained(false)
                    ->submitAction(new HtmlString(Blade::render(
                        <<<'BLADE'
                            <div class="items-center space-x-4">
                                <x-filament::button
                                    type="submit"
                                    color="primary"
                                    class="text-white"
                                >
                                    <x-slot:icon>
                                        <x-heroicon-o-calculator class="size-5 text-white" />
                                    </x-slot:icon>
                                    Calcular TIR/VPN
                                </x-filament::button>
                            </div>
                        BLADE
                    ))),
            ]);
    }

    private static function buildResultHtml(callable $get): Htmlable
    {
        $resultados = $get('resultados_calculados');
        $mensaje = $get('mensaje_calculado');
        $periodicidadTasa = $get('periodicidad_tasa') ?: 1;
        $tasaDescuento = $get('tasa_descuento');

        $resultadosArray = $resultados ? json_decode($resultados, true) : [];
        $camposCalculadosArray = $get('campos_calculados') ? json_decode($get('campos_calculados'), true) : [];

        if (empty($resultadosArray)) {
            return new HtmlString('
            <div class="text-center py-12 text-gray-500 dark:text-gray-400">
                <div class="text-5xl mb-4">💹</div>
                <h3 class="text-xl font-semibold mb-2">Complete los datos para calcular</h3>
                <p class="text-sm text-gray-400">Los resultados del análisis TIR/VPN aparecerán aquí</p>
            </div>
        ');
        }

        // Inicio HTML
        $html = '<div class="space-y-5">';

        // ============================================
        // HEADER - Tipo de Análisis
        // ============================================
        $tieneTasaDescuento = ! empty($tasaDescuento);
        $calculoTIR = in_array('tir', $camposCalculadosArray);
        $calculoVPN = in_array('vpn', $camposCalculadosArray);

        if ($calculoTIR && ! $tieneTasaDescuento) {
            $tipoAnalisis = ['titulo' => '📊 Análisis TIR', 'desc' => 'Tasa que hace el VPN igual a cero', 'color' => 'emerald'];
        } elseif ($calculoVPN && $tieneTasaDescuento) {
            $tipoAnalisis = ['titulo' => '💰 Análisis VPN', 'desc' => 'Valor presente de los flujos de caja', 'color' => 'green'];
        } else {
            $tipoAnalisis = ['titulo' => '💹 Análisis TIR y VPN', 'desc' => 'Evaluación completa del proyecto', 'color' => 'teal'];
        }

        $colorClass = $tipoAnalisis['color'];
        $html .= "
        <div class='bg-gradient-to-r from-{$colorClass}-50 to-{$colorClass}-100 dark:from-{$colorClass}-950/50 dark:to-{$colorClass}-800/50 rounded-xl p-5 border border-{$colorClass}-200 dark:border-{$colorClass}-800'>
            <div class='flex items-center gap-3'>
                <span class='text-3xl'>💹</span>
                <div>
                    <h3 class='text-lg font-bold text-{$colorClass}-900 dark:text-{$colorClass}-100'>{$tipoAnalisis['titulo']}</h3>
                    <p class='text-sm text-{$colorClass}-700 dark:text-{$colorClass}-300'>{$tipoAnalisis['desc']}</p>
                </div>
            </div>
        </div>
    ";

        // ============================================
        // BLOQUE 1: Indicadores Principales (TIR, TIRM, VPN)
        // ============================================
        $mostrarIndicadores = (isset($resultadosArray['tir']) || isset($resultadosArray['tirm']) || (isset($resultadosArray['vpn']) && $tieneTasaDescuento));

        if ($mostrarIndicadores) {
            $html .= '<div class="bg-gradient-to-br from-slate-50 to-slate-100 dark:from-slate-900/50 dark:to-slate-800/50 rounded-xl p-4 border border-slate-200 dark:border-slate-700">';
            $html .= '<h4 class="text-sm font-bold text-slate-700 dark:text-slate-300 mb-3 flex items-center gap-2">
                  <span>📈</span> INDICADORES DE RENTABILIDAD
                  </h4>';

            // Determinar grid: 2 o 3 columnas según qué tengamos
            $numIndicadores = 0;
            if (isset($resultadosArray['tir'])) {
                $numIndicadores++;
            }
            if (isset($resultadosArray['tirm'])) {
                $numIndicadores++;
            }
            if (isset($resultadosArray['vpn']) && $tieneTasaDescuento) {
                $numIndicadores++;
            }

            $gridCols = $numIndicadores === 3 ? 'grid-cols-3' : 'grid-cols-2';
            $html .= "<div class='grid {$gridCols} gap-3'>";

            // TIR
            if (isset($resultadosArray['tir'])) {
                $tirValor = $resultadosArray['tir'];
                $tirColor = $tirValor >= 0 ? ($tirValor === 0 ? 'amber' : 'green') : 'red';

                $html .= "
                <div class='rounded-lg p-4 border bg-gradient-to-br from-{$tirColor}-50 to-emerald-50 border-{$tirColor}-200 dark:from-{$tirColor}-950/50 dark:to-emerald-950/50 dark:border-{$tirColor}-700 shadow-md'>
                    <div class='flex items-center justify-between mb-2'>
                        <div class='flex items-center gap-2'>
                            <span class='text-xl'>💹</span>
                            <h5 class='font-bold text-{$tirColor}-900 dark:text-{$tirColor}-100 text-sm'>TIR</h5>
                        </div>
                        <span class='px-2 py-0.5 text-xs font-medium rounded-full bg-{$tirColor}-100 text-{$tirColor}-800 dark:bg-{$tirColor}-900/50 dark:text-{$tirColor}-200'>✨</span>
                    </div>
                    <p class='text-2xl font-bold text-{$tirColor}-900 dark:text-{$tirColor}-100'>".number_format($tirValor, 4)."%</p>
                    <p class='text-xs text-{$tirColor}-600 dark:text-{$tirColor}-400 mt-1'>Tasa de rentabilidad</p>
                </div>
            ";
            }

            // TIRM
            if (isset($resultadosArray['tirm'])) {
                $tirmValor = (float) $resultadosArray['tirm'] ?? 0;
                $tirmColor = $tirmValor >= 0 ? ($tirmValor === 0 ? 'amber' : 'green') : 'red';

                $html .= "
                <div class='rounded-lg p-4 border bg-gradient-to-br from-{$tirmColor}-50 to-teal-50 border-{$tirmColor}-200 dark:from-{$tirmColor}-950/50 dark:to-teal-950/50 dark:border-{$tirmColor}-700 shadow-md'>
                    <div class='flex items-center justify-between mb-2'>
                        <div class='flex items-center gap-2'>
                            <span class='text-xl'>📈</span>
                            <h5 class='font-bold text-{$tirmColor}-900 dark:text-{$tirmColor}-100 text-sm'>TIRM</h5>
                        </div>
                        <span class='px-2 py-0.5 text-xs font-medium rounded-full bg-{$tirmColor}-100 text-{$tirmColor}-800 dark:bg-{$tirmColor}-900/50 dark:text-{$tirmColor}-200'>✨</span>
                    </div>
                    <p class='text-2xl font-bold text-{$tirmColor}-900 dark:text-{$tirmColor}-100'>".number_format($tirmValor, 4)."%</p>
                    <p class='text-xs text-{$tirmColor}-600 dark:text-{$tirmColor}-400 mt-1'>TIR Modificada</p>
                </div>
            ";
            }

            // VPN
            if (isset($resultadosArray['vpn']) && $tieneTasaDescuento) {
                $vpnValor = $resultadosArray['vpn'];
                $vpnColor = $vpnValor >= 0 ? ($vpnValor === 0 ? 'yellow' : 'green') : 'red';

                $html .= "
                <div class='rounded-lg p-4 border bg-gradient-to-br from-{$vpnColor}-50 to-cyan-50 border-{$vpnColor}-200 dark:from-{$vpnColor}-950/50 dark:to-cyan-950/50 dark:border-{$vpnColor}-700 shadow-md'>
                    <div class='flex items-center justify-between mb-2'>
                        <div class='flex items-center gap-2'>
                            <span class='text-xl'>💰</span>
                            <h5 class='font-bold text-{$vpnColor}-900 dark:text-{$vpnColor}-100 text-sm'>VPN</h5>
                        </div>
                        <span class='px-2 py-0.5 text-xs font-medium rounded-full bg-{$vpnColor}-100 text-{$vpnColor}-800 dark:bg-{$vpnColor}-900/50 dark:text-{$vpnColor}-200'>✨</span>
                    </div>
                    <p class='text-2xl font-bold text-{$vpnColor}-900 dark:text-{$vpnColor}-100'>$".number_format($vpnValor, 2)."</p>
                    <p class='text-xs text-{$vpnColor}-600 dark:text-{$vpnColor}-400 mt-1'>Valor presente neto</p>
                </div>
            ";
            }

            $html .= '</div>';
            $html .= '</div>'; // Fin bloque indicadores principales
        }

        // ============================================
        // BLOQUE 2: Datos del Proyecto
        // ============================================
        $html .= '<div class="space-y-3">';
        $html .= '<h4 class="text-sm font-bold text-gray-700 dark:text-gray-300 flex items-center gap-2">
              <span>📊</span> DATOS DEL PROYECTO
              </h4>';

        $html .= '<div class="grid grid-cols-3 gap-3">';

        // Inversión Inicial
        if (isset($resultadosArray['inversion_inicial'])) {
            $html .= static::buildCard('Inversión', '💸', '$'.number_format($resultadosArray['inversion_inicial'], 2), 'Capital inicial', false, 'blue');
        }

        // Suma de Flujos
        if (isset($resultadosArray['suma_flujos'])) {
            $html .= static::buildCard('Total Flujos', '📊', '$'.number_format($resultadosArray['suma_flujos'], 2), 'Ingresos totales', false, 'cyan');
        }

        // Número de Períodos
        if (isset($resultadosArray['numero_periodos'])) {
            $html .= static::buildCard('Períodos', '🔢', $resultadosArray['numero_periodos'], 'Duración', false, 'purple');
        }

        $html .= '</div>';

        // Periodicidad en línea horizontal
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
                    <span class='text-lg'>🔄</span>
                    <span class='text-xs font-semibold text-indigo-900 dark:text-indigo-100'>Periodicidad</span>
                </div>
                <div class='text-right'>
                    <span class='font-bold text-sm text-indigo-900 dark:text-indigo-100'>{$periodicidadTexto}</span>
                    <span class='text-xs text-indigo-600 dark:text-indigo-400 ml-2'>({$periodicidadTasa}/año)</span>
                </div>
            </div>
        </div>
    ";

        $html .= '</div>'; // Fin datos del proyecto

        // ============================================
        // BLOQUE 3: Indicadores Complementarios
        // ============================================
        $mostrarComplementarios = isset($resultadosArray['roi']) || isset($resultadosArray['payback_period']) ||
            isset($resultadosArray['rentabilidad']) || isset($resultadosArray['tasa_descuento']);

        if ($mostrarComplementarios) {
            $html .= '<div class="space-y-3">';
            $html .= '<h4 class="text-sm font-bold text-gray-700 dark:text-gray-300 flex items-center gap-2">
                  <span>💎</span> INDICADORES COMPLEMENTARIOS
                  </h4>';

            $html .= '<div class="grid grid-cols-2 gap-3">';

            // ROI
            if (isset($resultadosArray['roi'])) {
                $roiValor = $resultadosArray['roi'];
                $roiColor = $roiValor > 0 ? 'green' : 'red';
                $html .= static::buildCard('ROI', '📈', number_format($roiValor, 2).'%', 'Retorno de inversión', false, $roiColor);
            }

            // Payback Period
            if (isset($resultadosArray['payback_period'])) {
                $html .= static::buildCard('Payback', '⏱️', number_format($resultadosArray['payback_period'], 2), 'Períodos de recuperación', false, 'amber');
            }

            // Rentabilidad
            if (isset($resultadosArray['rentabilidad']) && $tieneTasaDescuento) {
                $rentValor = $resultadosArray['rentabilidad'];
                $rentColor = $rentValor > 0 ? 'green' : 'red';
                $html .= static::buildCard('Rentabilidad', '💎', number_format($rentValor, 2).'%', 'Del proyecto', false, $rentColor);
            }

            // Tasa de Descuento
            if (isset($resultadosArray['tasa_descuento']) && $tieneTasaDescuento) {
                $html .= static::buildCard('Tasa Descuento', '📉', number_format($resultadosArray['tasa_descuento'], 4).'%', 'Costo oportunidad', false, 'orange');
            }

            $html .= '</div>';
            $html .= '</div>'; // Fin indicadores complementarios
        }

        // ============================================
        // BLOQUE 4: Tasas TIRM (si existen)
        // ============================================
        if (isset($resultadosArray['tasa_financiamiento']) || isset($resultadosArray['tasa_reinversion'])) {
            $html .= '<div class="space-y-3">';
            $html .= '<h4 class="text-sm font-bold text-gray-700 dark:text-gray-300 flex items-center gap-2">
                  <span>⚙️</span> PARÁMETROS TIRM
                  </h4>';

            $html .= '<div class="grid grid-cols-2 gap-3">';

            // Tasa de Financiamiento
            if (isset($resultadosArray['tasa_financiamiento'])) {
                $html .= static::buildCard('Tasa Financiamiento', '💳', number_format($resultadosArray['tasa_financiamiento'], 4).'%', 'Costo de capital', false, 'red');
            }

            // Tasa de Reinversión
            if (isset($resultadosArray['tasa_reinversion'])) {
                $html .= static::buildCard('Tasa Reinversión', '💰', number_format($resultadosArray['tasa_reinversion'], 4).'%', 'Tasa de reinversión', false, 'green');
            }

            $html .= '</div>';
            $html .= '</div>'; // Fin parámetros TIRM
        }

        // ============================================
        // BLOQUE 5: Decisión de Inversión (Destacado)
        // ============================================
        if (isset($resultadosArray['decision'])) {
            $decision = $resultadosArray['decision'];
            $decisionColor = $decision === 'Aceptar' ? 'green' : ($decision === 'Rechazar' ? 'red' : 'amber');
            $decisionIcon = $decision === 'Aceptar' ? '✅' : ($decision === 'Rechazar' ? '❌' : '⚠️');

            $html .= "
            <div class='bg-gradient-to-br from-{$decisionColor}-50 to-{$decisionColor}-100 dark:from-{$decisionColor}-950/50 dark:to-{$decisionColor}-900/50 rounded-xl p-4 border-2 border-{$decisionColor}-300 dark:border-{$decisionColor}-700'>
                <div class='flex items-start gap-3'>
                    <span class='text-3xl flex-shrink-0'>{$decisionIcon}</span>
                    <div class='flex-1'>
                        <h4 class='text-base font-bold text-{$decisionColor}-900 dark:text-{$decisionColor}-100 mb-1'>DECISIÓN: {$decision} el Proyecto</h4>
                        <p class='text-sm text-{$decisionColor}-700 dark:text-{$decisionColor}-300 leading-relaxed'>{$resultadosArray['justificacion']}</p>
                    </div>
                </div>
            </div>
        ";
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
                        <h4 class='font-bold text-blue-900 dark:text-blue-100 text-sm mb-1'>RESUMEN</h4>
                        <p class='text-sm text-blue-800 dark:text-blue-200 leading-relaxed'>{$mensaje}</p>
                    </div>
                </div>
            </div>
        ";
        }

        $html .= '</div>'; // Fin contenedor principal

        return new HtmlString($html);
    }

    private static function buildTablaFlujosHtml(callable $get): Htmlable
    {
        $tablaJson = $get('tabla_flujos');

        if (! $tablaJson) {
            return new HtmlString('
                <div class="text-center py-12 text-gray-500 dark:text-gray-400">
                    <div class="text-5xl mb-4">📊</div>
                    <h3 class="text-xl font-semibold mb-2">Tabla de flujos no disponible</h3>
                    <p class="text-sm text-gray-400">Realiza el cálculo para ver el detalle de flujos</p>
                </div>
            ');
        }

        $tabla = json_decode($tablaJson, true);
        if (empty($tabla)) {
            return new HtmlString('
                <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                    <p>No hay datos de flujos disponibles</p>
                </div>
            ');
        }

        $html = '<div class="overflow-x-auto rounded-lg shadow-lg">';
        $html .= '<table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">';

        // Header
        $html .= '
            <thead class="bg-gradient-to-r from-emerald-100 to-green-100 dark:from-emerald-900/50 dark:to-green-900/50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-bold text-emerald-900 dark:text-emerald-100 uppercase tracking-wider">Período</th>
                    <th class="px-4 py-3 text-right text-xs font-bold text-emerald-900 dark:text-emerald-100 uppercase tracking-wider">Flujo de Caja</th>
                    <th class="px-4 py-3 text-right text-xs font-bold text-emerald-900 dark:text-emerald-100 uppercase tracking-wider">Factor de Descuento</th>
                    <th class="px-4 py-3 text-right text-xs font-bold text-emerald-900 dark:text-emerald-100 uppercase tracking-wider">Valor Presente</th>
                    <th class="px-4 py-3 text-right text-xs font-bold text-emerald-900 dark:text-emerald-100 uppercase tracking-wider">VP Acumulado</th>
                </tr>
            </thead>
        ';

        // Body
        $html .= '<tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">';

        foreach ($tabla as $index => $fila) {
            $rowClass = $index % 2 === 0 ? 'bg-gray-50 dark:bg-gray-900/30' : 'bg-white dark:bg-gray-800';
            $highlightClass = $fila['periodo'] === 0 ? 'border-l-4 border-red-500' : '';
            if ($index === count($tabla) - 1 && $fila['periodo'] !== 0) {
                $highlightClass = 'border-l-4 border-green-500';
            }

            $flujoColor = $fila['flujo'] < 0 ? 'text-red-600 dark:text-red-400' : 'text-green-600 dark:text-green-400';
            $vpColor = $fila['valor_presente'] < 0 ? 'text-red-600 dark:text-red-400' : 'text-green-600 dark:text-green-400';
            $vpAcumColor = $fila['vp_acumulado'] < 0 ? 'text-red-600 dark:text-red-400' : 'text-green-600 dark:text-green-400';

            $html .= "<tr class='{$rowClass} {$highlightClass} hover:bg-gray-100 dark:hover:bg-gray-700/50 transition-colors'>";
            $html .= "<td class='px-4 py-3 text-sm font-semibold text-gray-900 dark:text-gray-100'>{$fila['periodo']}</td>";
            $html .= "<td class='px-4 py-3 text-sm text-right font-semibold {$flujoColor}'>\$".number_format($fila['flujo'], 2).'</td>';
            $html .= "<td class='px-4 py-3 text-sm text-right text-blue-600 dark:text-blue-400'>".number_format($fila['factor_descuento'], 6).'</td>';
            $html .= "<td class='px-4 py-3 text-sm text-right {$vpColor}'>\$".number_format($fila['valor_presente'], 2).'</td>';
            $html .= "<td class='px-4 py-3 text-sm text-right font-bold {$vpAcumColor}'>\$".number_format($fila['vp_acumulado'], 2).'</td>';
            $html .= '</tr>';
        }

        $html .= '</tbody>';

        // Footer con totales
        $totalFlujos = array_sum(array_column($tabla, 'flujo'));
        $totalVP = array_sum(array_column($tabla, 'valor_presente'));
        $vpFinal = end($tabla)['vp_acumulado'];

        $html .= '
            <tfoot class="bg-gradient-to-r from-emerald-200 to-green-200 dark:from-emerald-800/50 dark:to-green-800/50">
                <tr>
                    <td class="px-4 py-4 text-sm font-bold text-emerald-900 dark:text-emerald-100">TOTALES</td>
                    <td class="px-4 py-4 text-sm text-right font-bold text-emerald-900 dark:text-emerald-100">'.number_format($totalFlujos, 2).'</td>
                    <td class="px-4 py-4 text-sm text-right font-bold text-emerald-900 dark:text-emerald-100">--</td>
                    <td class="px-4 py-4 text-sm text-right font-bold text-emerald-900 dark:text-emerald-100">'.number_format($totalVP, 2).'</td>
                    <td class="px-4 py-4 text-sm text-right font-bold text-emerald-900 dark:text-emerald-100">'.number_format($vpFinal, 2).'</td>
                </tr>
            </tfoot>
    ';

        $html .= '</table>';
        $html .= '</div>';

        // Leyenda
        $html .= '
            <div class="mt-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div class="bg-red-50 dark:bg-red-950/30 rounded-lg p-4 border border-red-200 dark:border-red-800">
                    <div class="flex items-center gap-2 mb-2">
                        <div class="w-4 h-4 bg-red-500 rounded"></div>
                        <span class="font-semibold text-red-900 dark:text-red-100">Inversión Inicial</span>
                    </div>
                    <p class="text-sm text-red-700 dark:text-red-300">Desembolso inicial del proyecto (Período 0)</p>
                </div>

                <div class="bg-green-50 dark:bg-green-950/30 rounded-lg p-4 border border-green-200 dark:border-green-800">
                    <div class="flex items-center gap-2 mb-2">
                        <div class="w-4 h-4 bg-green-500 rounded"></div>
                        <span class="font-semibold text-green-900 dark:text-green-100">Último Período</span>
                    </div>
                    <p class="text-sm text-green-700 dark:text-green-300">Flujo final del proyecto</p>
                </div>

                <div class="bg-blue-50 dark:bg-blue-950/30 rounded-lg p-4 border border-blue-200 dark:border-blue-800">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-blue-600 dark:text-blue-400 font-bold">📊</span>
                        <span class="font-semibold text-blue-900 dark:text-blue-100">Flujo de Caja</span>
                    </div>
                    <p class="text-sm text-blue-700 dark:text-blue-300">Ingresos o egresos del período</p>
                </div>

                <div class="bg-indigo-50 dark:bg-indigo-950/30 rounded-lg p-4 border border-indigo-200 dark:border-indigo-800">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-indigo-600 dark:text-indigo-400 font-bold">🔢</span>
                        <span class="font-semibold text-indigo-900 dark:text-indigo-100">Factor de Descuento</span>
                    </div>
                    <p class="text-sm text-indigo-700 dark:text-indigo-300">1 / (1 + tasa)^período</p>
                </div>

                <div class="bg-purple-50 dark:bg-purple-950/30 rounded-lg p-4 border border-purple-200 dark:border-purple-800">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-purple-600 dark:text-purple-400 font-bold">💰</span>
                        <span class="font-semibold text-purple-900 dark:text-purple-100">Valor Presente</span>
                    </div>
                    <p class="text-sm text-purple-700 dark:text-purple-300">Flujo descontado al momento actual</p>
                </div>

                <div class="bg-emerald-50 dark:bg-emerald-950/30 rounded-lg p-4 border border-emerald-200 dark:border-emerald-800">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-emerald-600 dark:text-emerald-400 font-bold">💹</span>
                        <span class="font-semibold text-emerald-900 dark:text-emerald-100">VP Acumulado</span>
                    </div>
                    <p class="text-sm text-emerald-700 dark:text-emerald-300">Suma acumulada de valores presentes</p>
                </div>
            </div>
    ';

        return new HtmlString($html);
    }

    private static function buildCard(
        string $title,
        string $icon,
        string $value,
        string $subtitle,
        bool $isCalculated,
        string $color = 'gray',
        string $p = 'p-6'
    ): string {
        $colorClasses = match ($color) {
            'green' => [
                'bg' => 'bg-gradient-to-br from-green-50 to-emerald-50 border-green-300 dark:from-green-950/50 dark:to-emerald-950/50 dark:border-green-700',
                'text' => 'text-green-900 dark:text-green-100',
                'badge' => 'bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-200',
                'subtitle' => 'text-green-600 dark:text-green-400',
            ],
            'blue' => [
                'bg' => 'bg-gradient-to-br from-blue-50 to-cyan-50 border-blue-300 dark:from-blue-950/50 dark:to-cyan-950/50 dark:border-blue-700',
                'text' => 'text-blue-900 dark:text-blue-100',
                'badge' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-200',
                'subtitle' => 'text-blue-600 dark:text-blue-400',
            ],
            'purple' => [
                'bg' => 'bg-gradient-to-br from-purple-50 to-pink-50 border-purple-300 dark:from-purple-950/50 dark:to-pink-950/50 dark:border-purple-700',
                'text' => 'text-purple-900 dark:text-purple-100',
                'badge' => 'bg-purple-100 text-purple-800 dark:bg-purple-900/50 dark:text-purple-200',
                'subtitle' => 'text-purple-600 dark:text-purple-400',
            ],
            'red' => [
                'bg' => 'bg-gradient-to-br from-red-50 to-rose-50 border-red-300 dark:from-red-950/50 dark:to-rose-950/50 dark:border-red-700',
                'text' => 'text-red-900 dark:text-red-100',
                'badge' => 'bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-200',
                'subtitle' => 'text-red-600 dark:text-red-400',
            ],
            'orange' => [
                'bg' => 'bg-gradient-to-br from-orange-50 to-amber-50 border-orange-300 dark:from-orange-950/50 dark:to-amber-950/50 dark:border-orange-700',
                'text' => 'text-orange-900 dark:text-orange-100',
                'badge' => 'bg-orange-100 text-orange-800 dark:bg-orange-900/50 dark:text-orange-200',
                'subtitle' => 'text-orange-600 dark:text-orange-400',
            ],
            'cyan' => [
                'bg' => 'bg-gradient-to-br from-cyan-50 to-teal-50 border-cyan-300 dark:from-cyan-950/50 dark:to-teal-950/50 dark:border-cyan-700',
                'text' => 'text-cyan-900 dark:text-cyan-100',
                'badge' => 'bg-cyan-100 text-cyan-800 dark:bg-cyan-900/50 dark:text-cyan-200',
                'subtitle' => 'text-cyan-600 dark:text-cyan-400',
            ],
            'amber' => [
                'bg' => 'bg-gradient-to-br from-amber-50 to-yellow-50 border-amber-300 dark:from-amber-950/50 dark:to-yellow-950/50 dark:border-amber-700',
                'text' => 'text-amber-900 dark:text-amber-100',
                'badge' => 'bg-amber-100 text-amber-800 dark:bg-amber-900/50 dark:text-amber-200',
                'subtitle' => 'text-amber-600 dark:text-amber-400',
            ],
            default => [
                'bg' => 'bg-gray-50 border-gray-200 dark:bg-gray-900/50 dark:border-gray-700',
                'text' => 'text-gray-900 dark:text-gray-100',
                'badge' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-200',
                'subtitle' => 'text-gray-600 dark:text-gray-400',
            ]
        };

        if ($isCalculated) {
            $colorClasses = [
                'bg' => 'bg-gradient-to-br from-green-50 to-emerald-50 border-green-300 dark:from-green-950/50 dark:to-emerald-950/50 dark:border-green-700',
                'text' => 'text-green-900 dark:text-green-100',
                'badge' => 'bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-200',
                'subtitle' => 'text-green-600 dark:text-green-400',
            ];
        }

        $badgeHtml = $isCalculated
            ? "<span class='px-3 py-1 text-xs font-medium rounded-full {$colorClasses['badge']}'>✨ Calculado</span>"
            : "<span class='px-3 py-1 text-xs font-medium rounded-full {$colorClasses['badge']}'>📝 Ingresado</span>";

        return "
            <div class='rounded-xl $p border {$colorClasses['bg']} shadow-sm'>
                <div class='flex items-center justify-between mb-2'>
                    <h4 class='font-semibold {$colorClasses['text']} flex items-center gap-2 text-xs'>
                        <span>{$icon}</span>
                        {$title}
                    </h4>
                    {$badgeHtml}
                </div>
                <p class='text-lg font-bold {$colorClasses['text']} mb-1'>{$value}</p>
                <p class='text-xs {$colorClasses['subtitle']}'>{$subtitle}</p>
            </div>
        ";
    }

    /**
     * Calcula el tiempo desde fechas
     */
    private static function calcularTiempoDesdeFechas(callable $set, callable $get): void
    {
        $fechaInicio = $get('fecha_inicio');
        $fechaFinal = $get('fecha_final');

        if ($fechaInicio && $fechaFinal) {
            try {
                $inicio = Carbon::parse($fechaInicio);
                $final = Carbon::parse($fechaFinal);

                if ($final->greaterThanOrEqualTo($inicio)) {
                    $segundosTotales = $inicio->diffInSeconds($final);
                    $segundosEnUnAno = 365.25 * 24 * 60 * 60;
                    $anios = $segundosTotales / $segundosEnUnAno;
                    $set('tiempo', round($anios, 8));
                } else {
                    $set('tiempo', null);
                }
            } catch (\Exception $e) {
                $set('tiempo', null);
            }
        } else {
            $set('tiempo', null);
        }
    }

    /**
     * Calcula el tiempo desde años, meses y días
     */
    private static function calcularTiempo(callable $set, callable $get): void
    {
        $anio = $get('anio') ?: 0;
        $mes = $get('mes') ?: 0;
        $dia = $get('dia') ?: 0;
        $anioConvertido = $anio + ($mes / 12) + ($dia / 365.25);
        $set('tiempo', number_format($anioConvertido, 8));
    }

    /**
     * Calcula el número de períodos desde tiempo y frecuencia
     */
    private static function calcularNumeroPeriodos(callable $set, callable $get): void
    {
        $tiempo = $get('tiempo');
        $modoTiempo = $get('modo_tiempo_pagos');

        if ($modoTiempo === 'anios_frecuencia') {
            $frecuencia = $get('frecuencia_anios') ?: 12;
        } elseif ($modoTiempo === 'fechas_frecuencia') {
            $frecuencia = $get('frecuencia_fechas') ?: 12;
        } else {
            return;
        }

        if ($tiempo && $frecuencia) {
            $numeroPeriodos = round($tiempo * $frecuencia);
            $set('numero_periodos', $numeroPeriodos);
            if ($modoTiempo === 'anios_frecuencia') {
                $set('numero_periodos_calculado_anios', $numeroPeriodos);
            }
        } else {
            $set('numero_periodos', null);
            if ($modoTiempo === 'anios_frecuencia') {
                $set('numero_periodos_calculado_anios', null);
            }
        }
    }
}
