<?php

namespace App\Filament\Schemas;

use Blade;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
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

class GradientesSchema
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Hidden::make('campos_calculados'),
                Hidden::make('resultados_calculados'),
                Hidden::make('tabla_gradiente'),
                Hidden::make('mensaje_calculado'),
                Hidden::make('numero_pagos'),
                Hidden::make('tiempo'),

                Wizard::make([
                    // Paso 1: Información básica
                    Step::make('Información Básica')
                        ->icon('heroicon-o-arrow-trending-up')
                        ->completedIcon('heroicon-s-arrow-trending-up')
                        ->schema([
                            Section::make('Información Básica del Gradiente')
                                ->icon('heroicon-o-arrow-trending-up')
                                ->description('Seleccione el tipo de gradiente y complete los datos básicos.')
                                ->schema([
                                    Grid::make(2)->schema([
                                        Select::make('tipo_gradiente')
                                            ->label('Tipo de Gradiente')
                                            ->options([
                                                'aritmetico_anticipado' => '📊 Gradiente Aritmético Anticipado',
                                                'aritmetico_vencido' => '📊 Gradiente Aritmético Vencido',
                                                'geometrico_anticipado' => '📈 Gradiente Geométrico Anticipado',
                                                'geometrico_vencido' => '📈 Gradiente Geométrico Vencido',
                                            ])
                                            ->required()
                                            ->default('aritmetico_anticipado')
                                            ->columnSpan(2)
                                            ->searchable()
                                            ->helperText('Seleccione el tipo de variación de los pagos')
                                            ->live()
                                            ->afterStateUpdated(function (callable $set) {
                                                $set('campos_calculados', null);
                                                $set('resultados_calculados', null);
                                                $set('tabla_gradiente', null);
                                                $set('mensaje_calculado', null);
                                            }),

                                        TextInput::make('valor_presente')
                                            ->rules(['nullable', 'numeric'])
                                            ->label('Valor Presente (VP)')
                                            ->numeric()
                                            ->prefix('$')
                                            ->placeholder('Ejemplo: 100000')
                                            ->hint('Valor actual de la serie')
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(function (callable $set) {
                                                $set('campos_calculados', null);
                                                $set('resultados_calculados', null);
                                                $set('tabla_gradiente', null);
                                                $set('mensaje_calculado', null);
                                            }),

                                        TextInput::make('valor_futuro')
                                            ->rules(['nullable', 'numeric'])
                                            ->label('Valor Futuro (VF)')
                                            ->numeric()
                                            ->prefix('$')
                                            ->placeholder('Ejemplo: 150000')
                                            ->hint('Valor final de la serie')
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(function (callable $set) {
                                                $set('campos_calculados', null);
                                                $set('resultados_calculados', null);
                                                $set('tabla_gradiente', null);
                                                $set('mensaje_calculado', null);
                                            }),

                                        TextInput::make('anualidad_inicial')
                                            ->rules(['nullable', 'numeric'])
                                            ->label('Anualidad Inicial (A₁)')
                                            ->numeric()
                                            ->prefix('$')
                                            ->placeholder('Ejemplo: 5000')
                                            ->hint('Primer pago de la serie')
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(function (callable $set) {
                                                $set('campos_calculados', null);
                                                $set('resultados_calculados', null);
                                                $set('tabla_gradiente', null);
                                                $set('mensaje_calculado', null);
                                            }),

                                        TextInput::make('gradiente_aritmetico')
                                            ->rules(['nullable', 'numeric'])
                                            ->label('Gradiente Aritmético (G)')
                                            ->numeric()
                                            ->prefix('$')
                                            ->placeholder('Ejemplo: 500 o -500')
                                            ->helperText('Incremento (+) o Decremento (-) constante')
                                            ->visible(fn (callable $get) => $get('tipo_gradiente') === 'aritmetico_anticipado' || $get('tipo_gradiente') === 'aritmetico_vencido')
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(function (callable $set) {
                                                $set('campos_calculados', null);
                                                $set('resultados_calculados', null);
                                                $set('tabla_gradiente', null);
                                                $set('mensaje_calculado', null);
                                            }),

                                        TextInput::make('gradiente_geometrico')
                                            ->rules(['nullable', 'numeric'])
                                            ->label('Gradiente Geométrico (g)')
                                            ->numeric()
                                            ->suffix('%')
                                            ->placeholder('Ejemplo: 5 o -5')
                                            ->step(0.01)
                                            ->helperText('Porcentaje de incremento (+) o decremento (-)')
                                            ->visible(fn (callable $get) => $get('tipo_gradiente') === 'geometrico_anticipado' || $get('tipo_gradiente') === 'geometrico_vencido')
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(function (callable $set) {
                                                $set('campos_calculados', null);
                                                $set('resultados_calculados', null);
                                                $set('tabla_gradiente', null);
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
                                ->icon('heroicon-o-percent-badge')
                                ->description('Configure la tasa de interés y su periodicidad')
                                ->schema([
                                    Grid::make(12)->schema([
                                        TextInput::make('tasa_interes')
                                            ->rules(['nullable', 'numeric', 'min:0'])
                                            ->validationMessages([
                                                'min' => 'La tasa de interés debe ser mayor o igual a 0',
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
                                                $set('campos_calculados', null);
                                                $set('resultados_calculados', null);
                                                $set('tabla_gradiente', null);
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
                                                $set('campos_calculados', null);
                                                $set('resultados_calculados', null);
                                                $set('tabla_gradiente', null);
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
                                            ->default(12)
                                            ->searchable()
                                            ->columnSpan(5)
                                            ->visible(fn (callable $get) => $get('usar_select_periodicidad_tasa'))
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(function (callable $set) {
                                                $set('campos_calculados', null);
                                                $set('resultados_calculados', null);
                                                $set('tabla_gradiente', null);
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
                                                $set('campos_calculados', null);
                                                $set('resultados_calculados', null);
                                                $set('tabla_gradiente', null);
                                                $set('mensaje_calculado', null);
                                            }),
                                    ]),
                                ]),
                        ]),

                    // Paso 3: Configuración de tiempo y pagos
                    Step::make('Número de Pagos')
                        ->icon('heroicon-o-clock')
                        ->completedIcon('heroicon-s-clock')
                        ->schema([
                            Section::make('Configuración de Tiempo y Pagos')
                                ->icon('heroicon-o-clock')
                                ->description('Configure el período de tiempo y el número de pagos')
                                ->schema([
                                    Select::make('modo_tiempo_pagos')
                                        ->label('Método para determinar número de pagos')
                                        ->options([
                                            'manual' => 'Ingresar número de pagos directamente',
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
                                            $set('numero_pagos', null);
                                            $set('campos_calculados', null);
                                            $set('resultados_calculados', null);
                                            $set('tabla_gradiente', null);
                                            $set('mensaje_calculado', null);
                                        }),

                                    // MODO MANUAL
                                    TextInput::make('numero_pagos')
                                        ->rules(['nullable', 'integer', 'min:1'])
                                        ->validationMessages([
                                            'min' => 'El número de pagos debe ser mayor o igual a 1',
                                        ])
                                        ->label('Número de Pagos (n)')
                                        ->numeric()
                                        ->placeholder('Ejemplo: 60')
                                        ->hint('Total de pagos a realizar')
                                        ->visible(fn (callable $get) => $get('modo_tiempo_pagos') === 'manual')
                                        ->live()
                                        ->afterStateUpdated(function (callable $set) {
                                            $set('campos_calculados', null);
                                            $set('resultados_calculados', null);
                                            $set('tabla_gradiente', null);
                                            $set('mensaje_calculado', null);
                                        }),

                                    // MODO AÑOS + FRECUENCIA
                                    Grid::make(12)->schema([
                                        TextInput::make('numero_pagos_calculado_anios')
                                            ->label('Número de Pagos Calculado')
                                            ->disabled()
                                            ->columnSpan(12)
                                            ->hint('Tiempo x Frecuencia de pagos'),

                                        FieldSet::make('Tiempo')->schema([
                                            TextInput::make('anio')
                                                ->rules(['nullable', 'numeric', 'min:0'])
                                                ->label('Años')
                                                ->numeric()
                                                ->suffix('años')
                                                ->placeholder('Ejemplo: 5')
                                                ->step(0.01)
                                                ->live()
                                                ->afterStateUpdated(function (callable $set, callable $get) {
                                                    static::calcularTiempo($set, $get);
                                                    static::calcularNumeroPagos($set, $get);
                                                    $set('campos_calculados', null);
                                                    $set('resultados_calculados', null);
                                                    $set('tabla_gradiente', null);
                                                    $set('mensaje_calculado', null);
                                                }),

                                            TextInput::make('mes')
                                                ->rules(['nullable', 'numeric', 'min:0'])
                                                ->label('Meses')
                                                ->numeric()
                                                ->suffix('meses')
                                                ->placeholder('Ejemplo: 7')
                                                ->step(0.01)
                                                ->live()
                                                ->afterStateUpdated(function (callable $set, callable $get) {
                                                    static::calcularTiempo($set, $get);
                                                    static::calcularNumeroPagos($set, $get);
                                                    $set('campos_calculados', null);
                                                    $set('resultados_calculados', null);
                                                    $set('tabla_gradiente', null);
                                                    $set('mensaje_calculado', null);
                                                }),

                                            TextInput::make('dia')
                                                ->rules(['nullable', 'numeric', 'min:0'])
                                                ->label('Dias')
                                                ->numeric()
                                                ->suffix('dias')
                                                ->placeholder('Ejemplo: 21')
                                                ->step(0.01)
                                                ->live()
                                                ->afterStateUpdated(function (callable $set, callable $get) {
                                                    static::calcularTiempo($set, $get);
                                                    static::calcularNumeroPagos($set, $get);
                                                    $set('campos_calculados', null);
                                                    $set('resultados_calculados', null);
                                                    $set('tabla_gradiente', null);
                                                    $set('mensaje_calculado', null);
                                                }),

                                            TextInput::make('tiempo')
                                                ->label('Tiempo calculado')
                                                ->suffix('años')
                                                ->disabled(),
                                        ])
                                            ->columns(['default' => 1, 'md' => 2, 'xl' => 4])
                                            ->columnSpan(12),

                                        FieldSet::make('Frecuencia')->schema([
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
                                                    static::calcularNumeroPagos($set, $get);
                                                    $set('campos_calculados', null);
                                                    $set('resultados_calculados', null);
                                                    $set('tabla_gradiente', null);
                                                    $set('mensaje_calculado', null);
                                                }),

                                            TextInput::make('frecuencia_anios')
                                                ->rules(['nullable', 'integer', 'min:1'])
                                                ->label('Frecuencia (numérica)')
                                                ->numeric()
                                                ->placeholder('12 para mensual')
                                                ->hint('Veces por año')
                                                ->default(12)
                                                ->visible(fn (callable $get) => ! $get('usar_select_frecuencia'))
                                                ->live()
                                                ->afterStateUpdated(function (callable $set, callable $get) {
                                                    static::calcularNumeroPagos($set, $get);
                                                    $set('campos_calculados', null);
                                                    $set('resultados_calculados', null);
                                                    $set('tabla_gradiente', null);
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
                                                    $set('tabla_gradiente', null);
                                                    $set('mensaje_calculado', null);
                                                }),
                                        ])
                                            ->columns(['default' => 1, 'md' => 2])
                                            ->columnSpan(12),

                                        TextEntry::make('Nota')
                                            ->columnSpan(12)
                                            ->icon('heroicon-o-information-circle')
                                            ->state('Se utilizan gran cantidad de decimales al calcular el tiempo para soportar altas frecuencias de pagos.'),
                                    ])->visible(fn (callable $get) => $get('modo_tiempo_pagos') === 'anios_frecuencia'),

                                    // MODO FECHAS + FRECUENCIA
                                    Grid::make(12)->schema([
                                        TextInput::make('numero_pagos')
                                            ->label('Número de Pagos Calculado')
                                            ->disabled()
                                            ->columnSpan(12)
                                            ->hint('Tiempo x Frecuencia de pagos'),

                                        FieldSet::make('Fechas')->schema([
                                            DatePicker::make('fecha_inicio')
                                                ->label('Fecha de Inicio')
                                                ->placeholder('Seleccione la fecha inicial')
                                                ->live()
                                                ->afterStateUpdated(function (callable $set, callable $get) {
                                                    static::calcularTiempoDesdeFechas($set, $get);
                                                    static::calcularNumeroPagos($set, $get);
                                                    $set('campos_calculados', null);
                                                    $set('resultados_calculados', null);
                                                    $set('tabla_gradiente', null);
                                                    $set('mensaje_calculado', null);
                                                }),

                                            DatePicker::make('fecha_final')
                                                ->label('Fecha Final')
                                                ->placeholder('Seleccione la fecha final')
                                                ->live()
                                                ->afterStateUpdated(function (callable $set, callable $get) {
                                                    static::calcularTiempoDesdeFechas($set, $get);
                                                    static::calcularNumeroPagos($set, $get);
                                                    $set('campos_calculados', null);
                                                    $set('resultados_calculados', null);
                                                    $set('tabla_gradiente', null);
                                                    $set('mensaje_calculado', null);
                                                }),

                                            TextInput::make('tiempo')
                                                ->label('Tiempo calculado')
                                                ->suffix('años')
                                                ->disabled(),
                                        ])
                                            ->columns(['default' => 1, 'md' => 2])
                                            ->columnSpan(12),

                                        FieldSet::make('Frecuencia')->schema([
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
                                                    static::calcularNumeroPagos($set, $get);
                                                    $set('campos_calculados', null);
                                                    $set('resultados_calculados', null);
                                                    $set('tabla_gradiente', null);
                                                    $set('mensaje_calculado', null);
                                                }),

                                            TextInput::make('frecuencia_fechas')
                                                ->rules(['nullable', 'integer', 'min:1'])
                                                ->label('Frecuencia (numérica)')
                                                ->numeric()
                                                ->placeholder('12 para mensual')
                                                ->hint('Veces por año')
                                                ->default(12)
                                                ->visible(fn (callable $get) => ! $get('usar_select_frecuencia'))
                                                ->live()
                                                ->afterStateUpdated(function (callable $set, callable $get) {
                                                    static::calcularNumeroPagos($set, $get);
                                                    $set('campos_calculados', null);
                                                    $set('resultados_calculados', null);
                                                    $set('tabla_gradiente', null);
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
                                                    $set('tabla_gradiente', null);
                                                    $set('mensaje_calculado', null);
                                                }),
                                        ])
                                            ->columns(['default' => 1, 'md' => 2])
                                            ->columnSpan(12),

                                        TextEntry::make('Nota')
                                            ->columnSpan(12)
                                            ->icon('heroicon-o-information-circle')
                                            ->state('Se utilizan gran cantidad de decimales al calcular el tiempo para soportar altas frecuencias de pagos.'),
                                    ])->visible(fn (callable $get) => $get('modo_tiempo_pagos') === 'fechas_frecuencia'),
                                ]),
                        ]),

                    // Paso 4: Resultados
                    Step::make('Resultados')
                        ->icon('heroicon-o-chart-bar')
                        ->completedIcon('heroicon-s-chart-bar')
                        ->schema([
                            Section::make('Resumen del Gradiente')
                                ->collapsible()
                                ->icon('heroicon-o-chart-bar')
                                ->description('Resumen completo de los valores calculados del gradiente')
                                ->schema([
                                    Grid::make(1)->schema([
                                        Placeholder::make('_')
                                            ->label('')
                                            ->content(function (callable $get): Htmlable {
                                                return static::buildResultHtml($get);
                                            }),
                                    ]),
                                ]),

                            Section::make('Tabla de Flujo de Caja')
                                ->collapsible()
                                ->collapsed()
                                ->icon('heroicon-o-table-cells')
                                ->description('Detalle período por período de los flujos de caja del gradiente')
                                ->schema([
                                    Grid::make(1)->schema([
                                        Placeholder::make('_')
                                            ->label('')
                                            ->content(function (callable $get): Htmlable {
                                                return static::buildTablaGradienteHtml($get);
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
                                    Calcular
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
        $tipoGradiente = $get('tipo_gradiente');
        $periodicidadTasa = $get('periodicidad_tasa') ?: 12;

        $resultadosArray = $resultados ? json_decode($resultados, true) : [];
        $camposCalculadosArray = $get('campos_calculados') ? json_decode($get('campos_calculados'), true) : [];

        if (! empty($resultadosArray)) {
            switch ($tipoGradiente) {
                case 'aritmetico_vencido':
                    $gradienteTexto = 'Gradiente Aritmético Vencido';
                    $gradienteDesc = 'Pagos al final de cada período con incremento o decremento constante.';
                    $colorFrom = 'from-blue-50';
                    $colorTo = 'to-cyan-100';
                    $colorDarkFrom = 'from-blue-950/50';
                    $colorDarkTo = 'to-cyan-700/50';
                    $borderColor = 'border-blue-200 dark:border-blue-800';
                    $titleColor = 'text-blue-900 dark:text-blue-100';
                    $descColor = 'text-blue-600 dark:text-blue-300';
                    break;

                case 'aritmetico_anticipado':
                    $gradienteTexto = 'Gradiente Aritmético Anticipado';
                    $gradienteDesc = 'Pagos al inicio de cada período con incremento o decremento constante.';
                    $colorFrom = 'from-amber-50';
                    $colorTo = 'to-yellow-100';
                    $colorDarkFrom = 'from-amber-950/50';
                    $colorDarkTo = 'to-yellow-700/50';
                    $borderColor = 'border-amber-200 dark:border-amber-800';
                    $titleColor = 'text-amber-900 dark:text-amber-100';
                    $descColor = 'text-amber-600 dark:text-amber-300';
                    break;

                case 'geometrico_vencido':
                    $gradienteTexto = 'Gradiente Geométrico Vencido';
                    $gradienteDesc = 'Pagos al final de cada período con incremento o decremento porcentual.';
                    $colorFrom = 'from-blue-50';
                    $colorTo = 'to-cyan-100';
                    $colorDarkFrom = 'from-blue-950/50';
                    $colorDarkTo = 'to-cyan-700/50';
                    $borderColor = 'border-blue-200 dark:border-blue-800';
                    $titleColor = 'text-blue-900 dark:text-blue-100';
                    $descColor = 'text-blue-600 dark:text-blue-300';
                    break;

                case 'geometrico_anticipado':
                    $gradienteTexto = 'Gradiente Geométrico Anticipado';
                    $gradienteDesc = 'Pagos al inicio de cada período con incremento o decremento porcentual.';
                    $colorFrom = 'from-amber-50';
                    $colorTo = 'to-yellow-100';
                    $colorDarkFrom = 'from-amber-950/50';
                    $colorDarkTo = 'to-yellow-700/50';
                    $borderColor = 'border-amber-200 dark:border-amber-800';
                    $titleColor = 'text-amber-900 dark:text-amber-100';
                    $descColor = 'text-amber-600 dark:text-amber-300';
                    break;
            }

            $html = '<div class="space-y-6">';
            $html .= '<div class="bg-gradient-to-r '.$colorFrom.' '.$colorTo.' dark:'.$colorDarkFrom.' dark:'.$colorDarkTo.' rounded-xl p-6 border '.$borderColor.'">';
            $html .= '<h3 class="text-xl font-bold '.$titleColor.' flex items-center gap-3">';
            $html .= '<span class="text-3xl">📈</span><div><div>'.$gradienteTexto.'</div>';
            $html .= '<div class="text-sm font-normal '.$descColor.'">'.$gradienteDesc.'</div></div></h3></div>';

            $html .= '<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">';

            foreach ($resultadosArray as $key => $value) {
                $isCalculated = in_array($key, $camposCalculadosArray);
                $config = match ($key) {
                    'valor_presente' => ['título' => 'Valor Presente', 'icono' => '💰', 'formato' => 'moneda', 'sub' => 'Valor actual', 'color' => 'green'],
                    'valor_futuro' => ['título' => 'Valor Futuro', 'icono' => '💎', 'formato' => 'moneda', 'sub' => 'Valor final', 'color' => 'purple'],
                    'anualidad_inicial' => ['título' => 'Anualidad Inicial', 'icono' => '💵', 'formato' => 'moneda', 'sub' => 'Primer pago', 'color' => 'blue'],
                    'anualidad_final' => ['título' => 'Anualidad Final', 'icono' => '💳', 'formato' => 'moneda', 'sub' => 'Último pago', 'color' => 'orange'],
                    'tasa_interes' => ['título' => 'Tasa de Interés', 'icono' => '📈', 'formato' => 'porcentaje', 'sub' => 'Tasa nominal', 'color' => 'gray'],
                    'numero_pagos' => ['título' => 'Número de Pagos', 'icono' => '🔢', 'formato' => 'numero', 'sub' => 'Total períodos', 'color' => 'gray'],
                    default => null
                };

                if ($config) {
                    $displayValue = match ($config['formato']) {
                        'moneda' => '$'.number_format($value, 2),
                        'porcentaje' => $value.'%',
                        'numero' => $value,
                        default => $value
                    };
                    $html .= static::buildCard($config['título'], $config['icono'], $displayValue, $config['sub'], $isCalculated, $config['color']);
                }
            }
            $html .= '</div>';

            $html .= '<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">';

            foreach ($resultadosArray as $key => $value) {
                $isCalculated = in_array($key, $camposCalculadosArray);
                $config = match ($key) {
                    'gradiente_aritmetico' => ['título' => 'Gradiente Aritmético', 'icono' => '📊', 'formato' => 'moneda', 'sub' => $value >= 0 ? '(Incremento)' : '(Decremento)', 'color' => 'cyan'],
                    'gradiente_geometrico' => ['título' => 'Gradiente Geométrico', 'icono' => '📈', 'formato' => 'porcentaje', 'sub' => $value >= 0 ? '(Incremento)' : '(Decremento)', 'color' => 'cyan'],
                    'total_pagos' => ['título' => 'Total de Pagos', 'icono' => '💸', 'formato' => 'moneda', 'sub' => 'Suma total', 'color' => 'red'],
                    default => null
                };

                if ($config) {
                    $displayValue = match ($config['formato']) {
                        'moneda' => '$'.number_format($value, 2),
                        'porcentaje' => $value.'%',
                        'numero' => $value,
                        default => $value
                    };
                    $html .= static::buildCard($config['título'], $config['icono'], $displayValue, $config['sub'], $isCalculated, $config['color'], p: 'p-4');
                }
            }

            $periodicidadTexto = match ((int) $periodicidadTasa) {
                1 => 'Anual', 2 => 'Semestral', 4 => 'Trimestral', 6 => 'Bimestral',
                12 => 'Mensual', 24 => 'Quincenal', 52 => 'Semanal',
                360 => 'Diaria Comercial', 365 => 'Diaria',
                default => $periodicidadTasa.' veces/año'
            };

            $html .= "<div class='rounded-lg p-4 border bg-indigo-50 border-indigo-200 dark:bg-indigo-950/50 dark:border-indigo-700 shadow-sm'>";
            $html .= "<div class='flex items-center gap-2 mb-2'><span class='text-indigo-600 dark:text-indigo-400'>📊</span>";
            $html .= "<h4 class='font-semibold text-indigo-900 dark:text-indigo-100 text-sm'>Periodicidad</h4></div>";
            $html .= "<p class='text-xl font-bold text-indigo-900 dark:text-indigo-100'>{$periodicidadTexto}</p>";
            $html .= "<p class='text-xs text-indigo-600 dark:text-indigo-400'>{$periodicidadTasa} períodos/año</p></div>";

            $html .= '</div>';

            if ($mensaje) {
                $html .= "<div class='bg-gradient-to-r from-blue-50 to-purple-50 dark:from-blue-950/50 dark:to-purple-950/50 rounded-xl p-6 border border-blue-200 dark:border-blue-700 shadow-sm'>";
                $html .= "<div class='flex items-start gap-4'><div class='flex-shrink-0 text-3xl'>🎯</div><div>";
                $html .= "<h4 class='font-bold text-blue-900 dark:text-blue-100 mb-2 text-lg'>Resultado del Cálculo</h4>";
                $html .= "<p class='text-blue-800 dark:text-blue-200 leading-relaxed'>{$mensaje}</p></div></div></div>";
            }

            $html .= '</div>';

            return new HtmlString($html);
        }

        if (empty($tipoGradiente)) {
            return new HtmlString('<div class="text-center py-12"><div class="bg-gradient-to-r from-amber-50 to-orange-50 dark:from-amber-950/50 dark:to-orange-950/50 rounded-xl p-8 border border-amber-200 dark:border-amber-800">
                <div class="text-6xl mb-4">⚠️</div><h3 class="text-xl font-bold text-amber-900 dark:text-amber-100 mb-3">Tipo de gradiente no seleccionado</h3>
                <p class="text-amber-700 dark:text-amber-300 text-lg">Por favor, selecciona el tipo de gradiente (Aritmético o Geométrico)</p></div></div>');
        }

        return new HtmlString('<div class="text-center py-12 text-gray-500 dark:text-gray-400">
            <div class="text-5xl mb-4">⏳</div><h3 class="text-xl font-semibold mb-2">Listo para calcular</h3>
            <p class="text-sm text-gray-400">Presiona el botón "Calcular" para ver los resultados</p></div>');
    }

    private static function buildTablaGradienteHtml(callable $get): Htmlable
    {
        $tablaJson = $get('tabla_gradiente');

        if (! $tablaJson) {
            return new HtmlString('<div class="text-center py-12 text-gray-500 dark:text-gray-400">
                <div class="text-5xl mb-4">📊</div><h3 class="text-xl font-semibold mb-2">Tabla de flujo de caja no disponible</h3>
                <p class="text-sm text-gray-400">Realiza el cálculo para ver el detalle período por período</p></div>');
        }

        $tabla = json_decode($tablaJson, true);
        if (empty($tabla)) {
            return new HtmlString('<div class="text-center py-8 text-gray-500 dark:text-gray-400"><p>No hay datos de flujo de caja disponibles</p></div>');
        }

        $html = '<div class="overflow-x-auto rounded-lg shadow-lg"><table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">';
        $html .= '<thead class="bg-gradient-to-r from-blue-100 to-cyan-100 dark:from-blue-900/50 dark:to-cyan-900/50"><tr>';
        $html .= '<th class="px-4 py-3 text-left text-xs font-bold text-blue-900 dark:text-blue-100 uppercase tracking-wider">Período</th>';
        $html .= '<th class="px-4 py-3 text-right text-xs font-bold text-blue-900 dark:text-blue-100 uppercase tracking-wider">Pago</th>';
        $html .= '<th class="px-4 py-3 text-right text-xs font-bold text-blue-900 dark:text-blue-100 uppercase tracking-wider">Incremento</th>';
        $html .= '<th class="px-4 py-3 text-right text-xs font-bold text-blue-900 dark:text-blue-100 uppercase tracking-wider">Valor Presente</th>';
        $html .= '<th class="px-4 py-3 text-right text-xs font-bold text-blue-900 dark:text-blue-100 uppercase tracking-wider">Valor Futuro</th>';
        $html .= '</tr></thead><tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">';

        foreach ($tabla as $index => $fila) {
            $rowClass = $index % 2 === 0 ? 'bg-gray-50 dark:bg-gray-900/30' : 'bg-white dark:bg-gray-800';
            $highlightClass = '';
            if ($index === 0) {
                $highlightClass = 'border-l-4 border-green-500';
            } elseif ($index === count($tabla) - 1) {
                $highlightClass = 'border-l-4 border-blue-500';
            }

            $incremento = isset($fila['incremento']) ? '$'.number_format($fila['incremento'], 2) : '--';
            if (isset($fila['incremento_porcentual'])) {
                $incremento = number_format($fila['incremento_porcentual'], 2).'%';
            }

            $html .= "<tr class='{$rowClass} {$highlightClass} hover:bg-gray-100 dark:hover:bg-gray-700/50 transition-colors'>";
            $html .= "<td class='px-4 py-3 text-sm font-semibold text-gray-900 dark:text-gray-100'>{$fila['periodo']}</td>";
            $html .= "<td class='px-4 py-3 text-sm text-right font-semibold text-blue-700 dark:text-blue-400'>\$".number_format($fila['pago'], 2).'</td>';
            $html .= "<td class='px-4 py-3 text-sm text-right text-cyan-600 dark:text-cyan-400'>{$incremento}</td>";
            $html .= "<td class='px-4 py-3 text-sm text-right text-green-600 dark:text-green-400'>\$".number_format($fila['valor_presente'], 2).'</td>';
            $html .= "<td class='px-4 py-3 text-sm text-right text-purple-600 dark:text-purple-400'>\$".number_format($fila['valor_futuro'], 2).'</td>';
            $html .= '</tr>';
        }

        $totalPagos = array_sum(array_column($tabla, 'pago'));
        $totalVP = array_sum(array_column($tabla, 'valor_presente'));
        $totalVF = array_sum(array_column($tabla, 'valor_futuro'));

        $html .= '</tbody><tfoot class="bg-gradient-to-r from-blue-200 to-cyan-200 dark:from-blue-800/50 dark:to-cyan-800/50"><tr>';
        $html .= '<td class="px-4 py-4 text-sm font-bold text-blue-900 dark:text-blue-100">TOTALES</td>';
        $html .= '<td class="px-4 py-4 text-sm text-right font-bold text-blue-900 dark:text-blue-100">$'.number_format($totalPagos, 2).'</td>';
        $html .= '<td class="px-4 py-4 text-sm text-right font-bold text-blue-900 dark:text-blue-100">--</td>';
        $html .= '<td class="px-4 py-4 text-sm text-right font-bold text-blue-900 dark:text-blue-100">$'.number_format($totalVP, 2).'</td>';
        $html .= '<td class="px-4 py-4 text-sm text-right font-bold text-blue-900 dark:text-blue-100">$'.number_format($totalVF, 2).'</td>';
        $html .= '</tr></tfoot></table></div>';

        $html .= '<div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">';
        $html .= '<div class="bg-green-50 dark:bg-green-950/30 rounded-lg p-4 border border-green-200 dark:border-green-800">';
        $html .= '<div class="flex items-center gap-2 mb-2"><div class="w-4 h-4 bg-green-500 rounded"></div>';
        $html .= '<span class="font-semibold text-green-900 dark:text-green-100">Primer Período</span></div>';
        $html .= '<p class="text-sm text-green-700 dark:text-green-300">Inicio de la serie de pagos</p></div>';

        $html .= '<div class="bg-blue-50 dark:bg-blue-950/30 rounded-lg p-4 border border-blue-200 dark:border-blue-800">';
        $html .= '<div class="flex items-center gap-2 mb-2"><div class="w-4 h-4 bg-blue-500 rounded"></div>';
        $html .= '<span class="font-semibold text-blue-900 dark:text-blue-100">Último Período</span></div>';
        $html .= '<p class="text-sm text-blue-700 dark:text-blue-300">Finalización de la serie</p></div>';

        $html .= '<div class="bg-blue-50 dark:bg-blue-950/30 rounded-lg p-4 border border-blue-200 dark:border-blue-800">';
        $html .= '<div class="flex items-center gap-2 mb-2"><span class="text-blue-600 dark:text-blue-400 font-bold">💳</span>';
        $html .= '<span class="font-semibold text-blue-900 dark:text-blue-100">Pago</span></div>';
        $html .= '<p class="text-sm text-blue-700 dark:text-blue-300">Flujo de caja del período</p></div>';

        $html .= '<div class="bg-cyan-50 dark:bg-cyan-950/30 rounded-lg p-4 border border-cyan-200 dark:border-cyan-800">';
        $html .= '<div class="flex items-center gap-2 mb-2"><span class="text-cyan-600 dark:text-cyan-400 font-bold">📊</span>';
        $html .= '<span class="font-semibold text-cyan-900 dark:text-cyan-100">Incremento</span></div>';
        $html .= '<p class="text-sm text-cyan-700 dark:text-cyan-300">Variación respecto al período anterior</p></div>';

        $html .= '<div class="bg-green-50 dark:bg-green-950/30 rounded-lg p-4 border border-green-200 dark:border-green-800">';
        $html .= '<div class="flex items-center gap-2 mb-2"><span class="text-green-600 dark:text-green-400 font-bold">💰</span>';
        $html .= '<span class="font-semibold text-green-900 dark:text-green-100">Valor Presente</span></div>';
        $html .= '<p class="text-sm text-green-700 dark:text-green-300">Valor actual del flujo</p></div>';

        $html .= '<div class="bg-purple-50 dark:bg-purple-950/30 rounded-lg p-4 border border-purple-200 dark:border-purple-800">';
        $html .= '<div class="flex items-center gap-2 mb-2"><span class="text-purple-600 dark:text-purple-400 font-bold">💎</span>';
        $html .= '<span class="font-semibold text-purple-900 dark:text-purple-100">Valor Futuro</span></div>';
        $html .= '<p class="text-sm text-purple-700 dark:text-purple-300">Valor al final de la serie</p></div></div>';

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
            default => ['bg' => 'bg-gray-50 border-gray-200 dark:bg-gray-900/50 dark:border-gray-700',
                'text' => 'text-gray-900 dark:text-gray-100', 'badge' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-200',
                'subtitle' => 'text-gray-600 dark:text-gray-400']
        };

        if ($isCalculated) {
            $colorClasses = ['bg' => 'bg-gradient-to-br from-green-50 to-emerald-50 border-green-300 dark:from-green-950/50 dark:to-emerald-950/50 dark:border-green-700',
                'text' => 'text-green-900 dark:text-green-100', 'badge' => 'bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-200',
                'subtitle' => 'text-green-600 dark:text-green-400'];
        }

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

    private static function calcularTiempo(callable $set, callable $get): void
    {
        $anio = $get('anio') ?: 0;
        $mes = $get('mes') ?: 0;
        $dia = $get('dia') ?: 0;
        $anioConvertido = $anio + ($mes / 12) + ($dia / 365.25);
        $set('tiempo', number_format($anioConvertido, 8));
    }

    private static function calcularNumeroPagos(callable $set, callable $get): void
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
            $numeroPagos = round($tiempo * $frecuencia);
            $set('numero_pagos', $numeroPagos);
            if ($modoTiempo === 'anios_frecuencia') {
                $set('numero_pagos_calculado_anios', $numeroPagos);
            }
        } else {
            $set('numero_pagos', null);
            if ($modoTiempo === 'anios_frecuencia') {
                $set('numero_pagos_calculado_anios', null);
            }
        }
    }
}
