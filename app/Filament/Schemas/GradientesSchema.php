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
    public static function configure(Schema $schema, bool $showSaveButton = false, bool $showUpdateButton = false): Schema
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

                            @if($showUpdate)
                            <x-filament::button
                                wire:click="updateCredito"
                                color="success"
                                class="text-white"
                            >
                                <x-slot:icon>
                                    <x-heroicon-o-check class="size-5 text-white" />
                                </x-slot:icon>
                                Actualizar Crédito
                            </x-filament::button>
                            @endif
                        </div>
                    BLADE, ['showSave' => $showSaveButton, 'showUpdate' => $showUpdateButton]))),
            ]);
    }

    public static function buildResultHtml(callable|array $data): Htmlable
    {
        // Soporte mixto: callable $get o array $data
        $get = is_callable($data)
            ? $data
            : fn (string $key) => $data[$key] ?? null;

        $resultados = $get('resultados_calculados');
        $mensaje = $get('mensaje_calculado');
        $tipoGradiente = $get('tipo_gradiente');
        $periodicidadTasa = $get('periodicidad_tasa') ?: 12;

        $resultadosArray = $resultados ? json_decode($resultados, true) : [];
        $camposCalculadosArray = $get('campos_calculados')
            ? json_decode($get('campos_calculados'), true)
            : [];

        // Validación: Tipo de gradiente no seleccionado
        if (empty($tipoGradiente)) {
            return new HtmlString('
            <div class="text-center py-12">
                <div class="bg-gradient-to-r from-amber-50 to-orange-50 dark:from-amber-950/50 dark:to-orange-950/50 rounded-xl p-8 border border-amber-200 dark:border-amber-800">
                    <div class="text-6xl mb-4">⚠️</div>
                    <h3 class="text-xl font-bold text-amber-900 dark:text-amber-100 mb-3">Tipo de gradiente no seleccionado</h3>
                    <p class="text-amber-700 dark:text-amber-300 text-lg">Por favor, selecciona el tipo de gradiente</p>
                </div>
            </div>
        ');
        }

        // Validación: Sin resultados
        if (empty($resultadosArray)) {
            return new HtmlString('
            <div class="text-center py-12 text-gray-500 dark:text-gray-400">
                <div class="text-5xl mb-4">⏳</div>
                <h3 class="text-xl font-semibold mb-2">Listo para calcular</h3>
                <p class="text-sm text-gray-400">Presiona el botón "Calcular" para ver los resultados</p>
            </div>
        ');
        }

        // Configuración según tipo de gradiente
        $gradienteConfig = match ($tipoGradiente) {
            'aritmetico_vencido' => [
                'titulo' => 'Gradiente Aritmético Vencido',
                'desc' => 'Pagos al final con incremento/decremento constante',
                'color' => 'blue',
                'icono' => '📊',
            ],
            'aritmetico_anticipado' => [
                'titulo' => 'Gradiente Aritmético Anticipado',
                'desc' => 'Pagos al inicio con incremento/decremento constante',
                'color' => 'amber',
                'icono' => '📋',
            ],
            'geometrico_vencido' => [
                'titulo' => 'Gradiente Geométrico Vencido',
                'desc' => 'Pagos al final con incremento/decremento porcentual',
                'color' => 'emerald',
                'icono' => '📈',
            ],
            'geometrico_anticipado' => [
                'titulo' => 'Gradiente Geométrico Anticipado',
                'desc' => 'Pagos al inicio con incremento/decremento porcentual',
                'color' => 'orange',
                'icono' => '📉',
            ],
            default => [
                'titulo' => 'Gradiente',
                'desc' => 'Análisis de gradiente',
                'color' => 'gray',
                'icono' => '📊',
            ]
        };

        $colorClass = $gradienteConfig['color'];

        // Inicio HTML
        $html = '<div class="space-y-5">';

        // ============================================
        // HEADER - Tipo de Gradiente
        // ============================================
        $html .= "
        <div class='bg-gradient-to-r from-{$colorClass}-50 to-{$colorClass}-100 dark:from-{$colorClass}-950/50 dark:to-{$colorClass}-800/50 rounded-xl p-5 border border-{$colorClass}-200 dark:border-{$colorClass}-800'>
            <div class='flex items-center gap-3'>
                <span class='text-3xl'>{$gradienteConfig['icono']}</span>
                <div>
                    <h3 class='text-lg font-bold text-{$colorClass}-900 dark:text-{$colorClass}-100'>{$gradienteConfig['titulo']}</h3>
                    <p class='text-sm text-{$colorClass}-700 dark:text-{$colorClass}-300'>{$gradienteConfig['desc']}</p>
                </div>
            </div>
        </div>
    ";

        // ============================================
        // BLOQUE 1: Valores Principales (VP y VF)
        // ============================================
        $html .= '<div class="bg-gradient-to-br from-slate-50 to-slate-100 dark:from-slate-900/50 dark:to-slate-800/50 rounded-xl p-4 border border-slate-200 dark:border-slate-700">';
        $html .= '<h4 class="text-sm font-bold text-slate-700 dark:text-slate-300 mb-3 flex items-center gap-2">
              <span>💰</span> VALORES TEMPORALES
              </h4>';

        $html .= '<div class="grid grid-cols-2 gap-3">';

        // Valor Presente
        if (isset($resultadosArray['valor_presente'])) {
            $isCalculated = in_array('valor_presente', $camposCalculadosArray);
            $displayValue = '$'.number_format($resultadosArray['valor_presente'], 2);

            $html .= "
            <div class='rounded-lg p-4 border bg-gradient-to-br from-green-50 to-emerald-50 border-green-200 dark:from-green-950/50 dark:to-emerald-950/50 dark:border-green-700 shadow-md'>
                <div class='flex items-center justify-between mb-2'>
                    <div class='flex items-center gap-2'>
                        <span class='text-xl'>💰</span>
                        <h5 class='font-bold text-green-900 dark:text-green-100 text-sm'>Valor Presente</h5>
                    </div>
                    ".($isCalculated ? "<span class='px-2 py-0.5 text-xs font-medium rounded-full bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-200'>✨</span>" : '')."
                </div>
                <p class='text-2xl font-bold text-green-900 dark:text-green-100'>{$displayValue}</p>
                <p class='text-xs text-green-600 dark:text-green-400 mt-1'>Valor actual</p>
            </div>
        ";
        }

        // Valor Futuro
        if (isset($resultadosArray['valor_futuro'])) {
            $isCalculated = in_array('valor_futuro', $camposCalculadosArray);
            $displayValue = '$'.number_format($resultadosArray['valor_futuro'], 2);

            $html .= "
            <div class='rounded-lg p-4 border bg-gradient-to-br from-purple-50 to-pink-50 border-purple-200 dark:from-purple-950/50 dark:to-pink-950/50 dark:border-purple-700 shadow-md'>
                <div class='flex items-center justify-between mb-2'>
                    <div class='flex items-center gap-2'>
                        <span class='text-xl'>💎</span>
                        <h5 class='font-bold text-purple-900 dark:text-purple-100 text-sm'>Valor Futuro</h5>
                    </div>
                    ".($isCalculated ? "<span class='px-2 py-0.5 text-xs font-medium rounded-full bg-purple-100 text-purple-800 dark:bg-purple-900/50 dark:text-purple-200'>✨</span>" : '')."
                </div>
                <p class='text-2xl font-bold text-purple-900 dark:text-purple-100'>{$displayValue}</p>
                <p class='text-xs text-purple-600 dark:text-purple-400 mt-1'>Valor final</p>
            </div>
        ";
        }

        $html .= '</div>';
        $html .= '</div>'; // Fin valores temporales

        // ============================================
        // BLOQUE 2: Anualidades (Inicial y Final)
        // ============================================
        if (isset($resultadosArray['anualidad_inicial']) || isset($resultadosArray['anualidad_final'])) {
            $html .= '<div class="space-y-3">';
            $html .= '<h4 class="text-sm font-bold text-gray-700 dark:text-gray-300 flex items-center gap-2">
                  <span>💳</span> FLUJOS DE PAGO
                  </h4>';

            $html .= '<div class="grid grid-cols-2 gap-3">';

            // Anualidad Inicial
            if (isset($resultadosArray['anualidad_inicial'])) {
                $isCalculated = in_array('anualidad_inicial', $camposCalculadosArray);
                $displayValue = '$'.number_format($resultadosArray['anualidad_inicial'], 2);
                $html .= static::buildCard('Pago Inicial', '💵', $displayValue, 'Primer pago', $isCalculated, 'blue');
            }

            // Anualidad Final
            if (isset($resultadosArray['anualidad_final'])) {
                $isCalculated = in_array('anualidad_final', $camposCalculadosArray);
                $displayValue = '$'.number_format($resultadosArray['anualidad_final'], 2);
                $html .= static::buildCard('Pago Final', '💳', $displayValue, 'Último pago', $isCalculated, 'orange');
            }

            $html .= '</div>';
            $html .= '</div>'; // Fin anualidades
        }

        // ============================================
        // BLOQUE 3: Parámetros del Gradiente
        // ============================================
        $html .= '<div class="space-y-3">';
        $html .= '<h4 class="text-sm font-bold text-gray-700 dark:text-gray-300 flex items-center gap-2">
              <span>⚙️</span> PARÁMETROS DEL GRADIENTE
              </h4>';

        $html .= '<div class="grid grid-cols-3 gap-3">';

        // Gradiente Aritmético o Geométrico
        if (isset($resultadosArray['gradiente_aritmetico'])) {
            $isCalculated = in_array('gradiente_aritmetico', $camposCalculadosArray);
            $valorGradiente = $resultadosArray['gradiente_aritmetico'];
            $displayValue = '$'.number_format($valorGradiente, 2);
            $subTexto = $valorGradiente >= 0 ? 'Incremento' : 'Decremento';
            $html .= static::buildCard('Gradiente', '📊', $displayValue, $subTexto, $isCalculated, 'cyan');
        }

        if (isset($resultadosArray['gradiente_geometrico'])) {
            $isCalculated = in_array('gradiente_geometrico', $camposCalculadosArray);
            $valorGradiente = $resultadosArray['gradiente_geometrico'];
            $displayValue = $valorGradiente.'%';
            $subTexto = $valorGradiente >= 0 ? 'Incremento' : 'Decremento';
            $html .= static::buildCard('Gradiente', '📈', $displayValue, $subTexto, $isCalculated, 'cyan');
        }

        // Tasa de Interés
        if (isset($resultadosArray['tasa_interes'])) {
            $isCalculated = in_array('tasa_interes', $camposCalculadosArray);
            $displayValue = $resultadosArray['tasa_interes'].'%';
            $html .= static::buildCard('Tasa', '📈', $displayValue, 'Nominal', $isCalculated);
        }

        // Número de Pagos
        if (isset($resultadosArray['numero_pagos'])) {
            $isCalculated = in_array('numero_pagos', $camposCalculadosArray);
            $displayValue = $resultadosArray['numero_pagos'];
            $html .= static::buildCard('Pagos', '🔢', $displayValue, 'Períodos', $isCalculated);
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

        $html .= '</div>'; // Fin parámetros

        // ============================================
        // BLOQUE 4: Resumen Total (si existe)
        // ============================================
        if (isset($resultadosArray['total_pagos'])) {
            $html .= '<div class="bg-gradient-to-br from-purple-50 to-pink-50 dark:from-purple-950/50 dark:to-pink-950/50 rounded-xl p-4 border-2 border-purple-300 dark:border-purple-700">';
            $html .= '<h4 class="text-sm font-bold text-purple-900 dark:text-purple-100 mb-3 flex items-center gap-2">
                  <span>💎</span> RESUMEN TOTAL
                  </h4>';

            $html .= '<div class="grid grid-cols-1 gap-3">';

            $displayValue = '$'.number_format($resultadosArray['total_pagos'], 2);
            $isCalculated = in_array('total_pagos', $camposCalculadosArray);
            $html .= static::buildCard('Total de Pagos', '💸', $displayValue, 'Suma de todos los flujos', $isCalculated, 'red');

            $html .= '</div>';
            $html .= '</div>'; // Fin resumen total
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

    public static function buildPagosHtml(array $data): Htmlable
    {
        $html = '<div class="space-y-5">';

        // HEADER
        $tipoGradiente = $data['tipo_gradiente'] ?? 'aritmetico_anticipado';
        $tablaModificada = $data['tabla_modificada'] ?? false;

        $tipoGradienteTexto = match ($tipoGradiente) {
            'aritmetico_vencido' => 'Gradiente Aritmético Vencido',
            'aritmetico_anticipado' => 'Gradiente Aritmético Anticipado',
            'geometrico_vencido' => 'Gradiente Geométrico Vencido',
            'geometrico_anticipado' => 'Gradiente Geométrico Anticipado',
            default => 'Gradiente',
        };

        $html .= '
    <div class="bg-gradient-to-r from-blue-50 to-indigo-100 dark:from-blue-950/50 dark:to-indigo-800/50 rounded-xl p-5 border border-indigo-200 dark:border-indigo-800">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <span class="text-3xl">📈</span>
                <div>
                    <h3 class="text-lg font-bold text-indigo-900 dark:text-indigo-100">Tabla de Flujos de Gradiente</h3>
                    <p class="text-sm text-indigo-700 dark:text-indigo-300">'.$tipoGradienteTexto.'</p>
                </div>
            </div>';

        if ($tablaModificada) {
            $flujosOriginal = $data['numero_pagos_original'] ?? 0;
            $flujosActual = $data['numero_pagos_actual'] ?? 0;
            $html .= '
            <div class="bg-yellow-100 dark:bg-yellow-900/50 text-yellow-800 dark:text-yellow-200 px-3 py-2 rounded-lg text-xs font-semibold">
                <div class="flex items-center gap-2">
                    <span>⚠️</span>
                    <div>
                        <div>Tabla Recalculada</div>
                        <div class="text-[10px]">'.$flujosOriginal.' → '.$flujosActual.' flujos</div>
                    </div>
                </div>
            </div>';
        }

        $html .= '
        </div>
    </div>';

        // RESUMEN DEL CRÉDITO
        $html .= '<div class="bg-gradient-to-br from-slate-50 to-slate-100 dark:from-slate-900/50 dark:to-slate-800/50 rounded-xl p-4 border border-slate-200 dark:border-slate-700">';
        $html .= '<h4 class="text-sm font-bold text-slate-700 dark:text-slate-300 mb-3 flex items-center gap-2"><span>💰</span> RESUMEN DEL CRÉDITO</h4>';
        $html .= '<div class="grid grid-cols-2 md:grid-cols-4 gap-3">';

        $anualidadInicial = $data['anualidad_inicial'] ?? 0;
        $html .= static::buildCard('Flujo Inicial', '💳', '$'.number_format($anualidadInicial, 2), 'Primer pago', true, 'blue');

        $numeroFlujos = $data['numero_pagos_actual'] ?? 0;
        $html .= static::buildCard('Total Flujos', '🔢', (string) $numeroFlujos, 'Número de pagos', false, 'indigo');

        $totalConAnticipos = $data['total_con_anticipos'] ?? 0;
        $html .= static::buildCard('Total a Pagar', '💼', '$'.number_format($totalConAnticipos, 2), 'Flujos + Anticipos', true, 'purple');

        $saldoRestante = $data['saldo_restante'] ?? 0;
        $html .= static::buildCard('Saldo Restante', '💵', '$'.number_format($saldoRestante, 2), 'Por pagar', true, $saldoRestante > 0 ? 'red' : 'green');

        $html .= '</div>';

        // Mostrar desglose de totales si hay anticipos
        $totalAnticipos = $data['total_anticipos_generados'] ?? 0;
        if ($totalAnticipos > 0.01) {
            $totalFlujos = $data['total_flujos_ajustados'] ?? 0;
            $html .= '<div class="mt-3 grid grid-cols-3 gap-2 text-xs">';
            $html .= '<div class="bg-blue-100 dark:bg-blue-900/30 rounded p-2 text-center">';
            $html .= '<div class="text-blue-600 dark:text-blue-400 font-semibold">Flujos Ajustados</div>';
            $html .= '<div class="text-blue-900 dark:text-blue-100 font-bold">$'.number_format($totalFlujos, 2).'</div>';
            $html .= '</div>';
            $html .= '<div class="bg-green-100 dark:bg-green-900/30 rounded p-2 text-center">';
            $html .= '<div class="text-green-600 dark:text-green-400 font-semibold">Anticipos</div>';
            $html .= '<div class="text-green-900 dark:text-green-100 font-bold">+$'.number_format($totalAnticipos, 2).'</div>';
            $html .= '</div>';
            $html .= '<div class="bg-purple-100 dark:bg-purple-900/30 rounded p-2 text-center">';
            $html .= '<div class="text-purple-600 dark:text-purple-400 font-semibold">Total</div>';
            $html .= '<div class="text-purple-900 dark:text-purple-100 font-bold">$'.number_format($totalConAnticipos, 2).'</div>';
            $html .= '</div>';
            $html .= '</div>';
        }

        $html .= '</div>';

        // PROGRESO
        $pagosPagados = $data['pagos_realizados'] ?? 0;
        $pagosRestantes = $data['pagos_restantes'] ?? 0;
        $porcentajePagado = $data['porcentaje_pagado'] ?? 0;
        $totalPagado = $data['total_pagado'] ?? 0;

        $html .= '<div class="bg-gradient-to-br from-emerald-50 to-green-50 dark:from-emerald-950/50 dark:to-green-950/50 rounded-xl p-4 border-2 border-emerald-300 dark:border-emerald-700">';
        $html .= '<h4 class="text-sm font-bold text-emerald-900 dark:text-emerald-100 mb-3 flex items-center gap-2"><span>📈</span> PROGRESO DE PAGOS</h4>';
        $html .= '<div class="grid grid-cols-2 md:grid-cols-4 gap-3">';

        $html .= static::buildCard('Flujos Pagados', '✅', "$pagosPagados/$numeroFlujos", 'Completados', false, 'emerald');
        $html .= static::buildCard('Flujos Restantes', '⏳', (string) $pagosRestantes, 'Pendientes', false, 'yellow');
        $html .= static::buildCard('Progreso', '📊', number_format($porcentajePagado, 1).'%', 'Del total', true, 'green');
        $html .= static::buildCard('Total Pagado', '💵', '$'.number_format($totalPagado, 2), 'Acumulado', false, 'teal');

        $html .= '</div></div>';

        // DESGLOSE VALORES
        $capitalPagado = $data['capital_pagado'] ?? 0;
        $capitalPendiente = $data['capital_pendiente'] ?? 0;
        $vpRecalculado = $data['valor_presente_total'] ?? 0;
        $vfRecalculado = $data['valor_futuro_total'] ?? 0;

        $html .= '<div class="bg-gradient-to-br from-amber-50 to-yellow-50 dark:from-amber-950/50 dark:to-yellow-950/50 rounded-xl p-4 border border-amber-300 dark:border-amber-700">';
        $html .= '<h4 class="text-sm font-bold text-amber-900 dark:text-amber-100 mb-3 flex items-center gap-2"><span>💎</span> ANÁLISIS DE VALORES</h4>';
        $html .= '<div class="grid grid-cols-2 md:grid-cols-4 gap-3">';

        $html .= static::buildCard('Capital Pagado', '🏦', '$'.number_format($capitalPagado, 2), 'Acumulado', false, 'amber');
        $html .= static::buildCard('Capital Pendiente', '📊', '$'.number_format($capitalPendiente, 2), 'Por pagar', false, 'yellow');
        $html .= static::buildCard('VP Recalculado', '📈', '$'.number_format($vpRecalculado, 2), 'Valor presente', false, 'green');
        $html .= static::buildCard('VF Recalculado', '📊', '$'.number_format($vfRecalculado, 2), 'Valor futuro', false, 'orange');

        $html .= '</div></div>';

        // PRÓXIMO FLUJO
        $flujoActual = $data['cuota_siguiente'] ?? null;
        if ($flujoActual && ! ($flujoActual['pagado'] ?? false)) {
            $html .= '<div class="bg-gradient-to-r from-blue-50 to-cyan-50 dark:from-blue-950/50 dark:to-cyan-950/50 rounded-xl p-4 border-2 border-blue-300 dark:border-blue-700">';
            $html .= '<h4 class="text-sm font-bold text-blue-900 dark:text-blue-100 mb-3 flex items-center gap-2">
              <span>🎯</span> PRÓXIMO FLUJO
              </h4>';

            $anticipoAcum = $flujoActual['anticipo_acumulado'] ?? 0;

            $colsLg = ($anticipoAcum > 0.01) ? 'lg:grid-cols-5' : 'lg:grid-cols-4';

            $html .= '<div class="grid grid-cols-2 md:grid-cols-3 '.$colsLg.' gap-3">';

            // Período
            $html .= "
        <div class='rounded-lg p-3 bg-white dark:bg-gray-800 border border-blue-200 dark:border-blue-800'>
            <div class='text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1'>Período</div>
            <div class='text-lg font-bold text-blue-900 dark:text-blue-100'>".($flujoActual['periodo'] ?? '-').'</div>
        </div>
        ';

            // Flujo Ajustado (LO REAL A PAGAR)
            $flujoAjustado = $flujoActual['flujo_ajustado'] ?? $flujoActual['pago'] ?? 0;
            $html .= "
        <div class='rounded-lg p-3 bg-white dark:bg-gray-800 border border-blue-200 dark:border-blue-800'>
            <div class='text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1'>Flujo a Pagar</div>
            <div class='text-lg font-bold text-blue-900 dark:text-blue-100'>\$".number_format($flujoAjustado, 2).'</div>
        </div>
        ';

            // Anticipo Acumulado
            if ($anticipoAcum > 0.01) {
                $html .= "
            <div class='rounded-lg p-3 bg-white dark:bg-gray-800 border border-purple-200 dark:border-purple-800'>
                <div class='text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1'>Anticipo Aplicado</div>
                <div class='text-lg font-bold text-purple-900 dark:text-purple-100'>-\$".number_format($anticipoAcum, 2).'</div>
            </div>
            ';
            }

            // Valor Presente
            $vpCuota = $flujoActual['valor_presente'] ?? 0;
            $html .= "
        <div class='rounded-lg p-3 bg-white dark:bg-gray-800 border border-green-200 dark:border-green-800'>
            <div class='text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1'>Valor Presente</div>
            <div class='text-lg font-bold text-green-900 dark:text-green-100'>\$".number_format($vpCuota, 2).'</div>
        </div>
        ';

            // Valor Futuro
            $vfCuota = $flujoActual['valor_futuro'] ?? 0;
            $html .= "
        <div class='rounded-lg p-3 bg-white dark:bg-gray-800 border border-orange-200 dark:border-orange-800'>
            <div class='text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1'>Valor Futuro</div>
            <div class='text-lg font-bold text-orange-900 dark:text-orange-100'>\$".number_format($vfCuota, 2).'</div>
        </div>
        ';

            $html .= '</div>';
            $html .= '</div>';
        }

        // TABLA DE FLUJOS
        $tablaGradientes = $data['tabla_gradientes'] ?? [];
        if (! empty($tablaGradientes)) {
            $html .= '<div class="bg-white dark:bg-slate-900 rounded-xl p-4 border border-slate-200 dark:border-slate-700 overflow-x-auto">';
            $html .= '<h4 class="text-sm font-bold text-slate-700 dark:text-slate-300 mb-4 flex items-center gap-2"><span>📋</span> DETALLE DE FLUJOS</h4>';
            $html .= '<table class="w-full text-xs md:text-sm">';
            $html .= '<thead class="bg-slate-100 dark:bg-slate-800 sticky top-0">';
            $html .= '<tr>';
            $html .= '<th class="px-2 md:px-3 py-2 text-right font-bold">Período</th>';
            $html .= '<th class="px-2 md:px-3 py-2 text-right font-bold">Flujo Ajustado</th>';
            $html .= '<th class="px-2 md:px-3 py-2 text-right font-bold">Anticipo</th>';
            $html .= '<th class="px-2 md:px-3 py-2 text-right font-bold">Total (Flujo + Anticipo)</th>';
            $html .= '<th class="px-2 md:px-3 py-2 text-right font-bold">VP</th>';
            $html .= '<th class="px-2 md:px-3 py-2 text-right font-bold">VF</th>';
            $html .= '<th class="px-2 md:px-3 py-2 text-center font-bold">Estado</th>';
            $html .= '<th class="px-2 md:px-3 py-2 text-center font-bold">Tipo</th>';
            $html .= '</tr></thead><tbody class="divide-y divide-slate-200 dark:divide-slate-700">';

            foreach ($tablaGradientes as $index => $flujo) {
                $pagado = $flujo['pagado'] ?? false;
                $tipoPago = $flujo['tipo_pago'] ?? null;

                $rowClass = 'bg-white dark:bg-slate-900';
                if ($pagado) {
                    $rowClass = match ($tipoPago) {
                        'abono_extra' => 'bg-green-50 dark:bg-green-900/20',
                        'pago_parcial' => 'bg-yellow-50 dark:bg-yellow-900/20',
                        'liquidacion' => 'bg-blue-50 dark:bg-blue-900/20',
                        'cubierto_anticipo' => 'bg-purple-50 dark:bg-purple-900/20',
                        default => 'bg-emerald-50 dark:bg-emerald-900/20',
                    };
                }

                $flujoAjustado = $flujo['flujo_ajustado'] ?? 0;
                $anticipoGenerado = $flujo['anticipo_generado'] ?? 0;
                $totalFlujo = $flujoAjustado + $anticipoGenerado;

                $html .= "<tr class='$rowClass hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors'>";
                $html .= '<td class="px-2 md:px-3 py-2 font-semibold">'.$flujo['periodo'].'</td>';
                $html .= '<td class="px-2 md:px-3 py-2 text-right font-bold text-blue-600 dark:text-blue-400">$'.number_format($flujoAjustado, 2).'</td>';

                // Mostrar anticipo (positivo cuando se genera)
                if ($anticipoGenerado > 0.01) {
                    $html .= '<td class="px-2 md:px-3 py-2 text-right text-green-600 dark:text-green-400 font-bold">+$'.number_format($anticipoGenerado, 2).'</td>';
                } else {
                    $html .= '<td class="px-2 md:px-3 py-2 text-right text-gray-400">--</td>';
                }

                // Total (flujo + anticipo)
                $html .= '<td class="px-2 md:px-3 py-2 text-right font-bold text-indigo-600 dark:text-indigo-400">$'.number_format($totalFlujo, 2).'</td>';

                $html .= '<td class="px-2 md:px-3 py-2 text-right text-green-600 dark:text-green-400">$'.number_format($flujo['valor_presente'] ?? 0, 2).'</td>';
                $html .= '<td class="px-2 md:px-3 py-2 text-right text-orange-600 dark:text-orange-400">$'.number_format($flujo['valor_futuro'] ?? 0, 2).'</td>';

                // Estado
                $html .= '<td class="px-2 md:px-3 py-2 text-center">';
                if ($pagado) {
                    $fechaPago = $flujo['fecha_pago'] ?? 'N/A';
                    if ($tipoPago === 'cubierto_anticipo') {
                        $html .= '<span class="inline-flex flex-col items-center gap-1 px-2 py-1 bg-purple-100 dark:bg-purple-900/50 text-purple-700 dark:text-purple-300 rounded-full text-xs font-semibold">';
                        $html .= '<span>💜 Cubierto</span><span class="text-[10px]">Por anticipo</span></span>';
                    } else {
                        $html .= '<span class="inline-flex flex-col items-center gap-1 px-2 py-1 bg-green-100 dark:bg-green-900/50 text-green-700 dark:text-green-300 rounded-full text-xs font-semibold">';
                        $html .= '<span>✅ Pagado</span><span class="text-[10px]">'.$fechaPago.'</span></span>';
                    }
                } else {
                    $html .= '<span class="inline-flex items-center px-2 py-1 bg-yellow-100 dark:bg-yellow-900/50 text-yellow-700 dark:text-yellow-300 rounded-full text-xs font-semibold">⏳ Pendiente</span>';
                }
                $html .= '</td>';

                // Tipo
                $html .= '<td class="px-2 md:px-3 py-2 text-center">';
                if ($pagado) {
                    $tipoBadge = match ($tipoPago) {
                        'normal' => '<span class="inline-block px-2 py-1 bg-blue-100 dark:bg-blue-900/50 text-blue-700 dark:text-blue-300 rounded text-[10px] font-semibold">Normal</span>',
                        'abono_extra' => '<span class="inline-block px-2 py-1 bg-green-100 dark:bg-green-900/50 text-green-700 dark:text-green-300 rounded text-[10px] font-semibold">Abono Extra</span>',
                        'pago_parcial' => '<span class="inline-block px-2 py-1 bg-yellow-100 dark:bg-yellow-900/50 text-yellow-700 dark:text-yellow-300 rounded text-[10px] font-semibold">Parcial</span>',
                        'liquidacion' => '<span class="inline-block px-2 py-1 bg-purple-100 dark:bg-purple-900/50 text-purple-700 dark:text-purple-300 rounded text-[10px] font-semibold">Liquidación</span>',
                        'cubierto_anticipo' => '<span class="inline-block px-2 py-1 bg-purple-100 dark:bg-purple-900/50 text-purple-700 dark:text-purple-300 rounded text-[10px] font-semibold">Cubierto</span>',
                        default => '<span class="inline-block px-2 py-1 bg-gray-100 dark:bg-gray-900/50 text-gray-700 dark:text-gray-300 rounded text-[10px]">-</span>',
                    };
                    $html .= $tipoBadge;
                } else {
                    $html .= '<span class="text-gray-400 text-xs">-</span>';
                }
                $html .= '</td>';

                $html .= '</tr>';
            }

            $html .= '</tbody><tfoot class="bg-gradient-to-r from-blue-200 to-cyan-200 dark:from-blue-800/50 dark:to-cyan-800/50"><tr>';

            // Calcular totales para mostrar en 3 columnas
            $totalFlujosAjustados = 0;
            $totalAnticipos = 0;
            $totalVP = 0;
            $totalVF = 0;

            foreach ($tablaGradientes as $flujo) {
                $totalFlujosAjustados += $flujo['flujo_ajustado'] ?? 0;
                $totalAnticipos += $flujo['anticipo_generado'] ?? 0;
                $totalVP += $flujo['valor_presente'] ?? 0;
                $totalVF += $flujo['valor_futuro'] ?? 0;
            }

            $totalConAnticipos = $totalFlujosAjustados + $totalAnticipos;

            $html .= '<td class="px-4 py-4 text-sm font-bold text-blue-900 dark:text-blue-100">TOTALES</td>';
            $html .= '<td class="px-4 py-4 text-sm text-right font-bold text-blue-600 dark:text-blue-400">$'.number_format($totalFlujosAjustados, 2).'</td>';
            $html .= '<td class="px-4 py-4 text-sm text-right font-bold text-green-600 dark:text-green-400">+$'.number_format($totalAnticipos, 2).'</td>';
            $html .= '<td class="px-4 py-4 text-sm text-right font-bold text-indigo-900 dark:text-indigo-100">$'.number_format($totalConAnticipos, 2).'</td>';
            $html .= '<td class="px-4 py-4 text-sm text-right font-bold text-green-600 dark:text-green-400">$'.number_format($totalVP, 2).'</td>';
            $html .= '<td class="px-4 py-4 text-sm text-right font-bold text-orange-600 dark:text-orange-400">$'.number_format($totalVF, 2).'</td>';
            $html .= '<td colspan="2"></td>';
            $html .= '</tr></tfoot></table></div>';

            // Leyenda
            $html .= '<div class="mt-3 space-y-2">';
            $html .= '<div class="flex flex-wrap gap-2 text-xs">';
            $html .= '<div class="flex items-center gap-1"><span class="w-3 h-3 bg-blue-100 dark:bg-blue-900/50 rounded"></span>Normal: Pago exacto del flujo</div>';
            $html .= '<div class="flex items-center gap-1"><span class="w-3 h-3 bg-green-100 dark:bg-green-900/50 rounded"></span>Abono Extra: Genera anticipo (+)</div>';
            $html .= '<div class="flex items-center gap-1"><span class="w-3 h-3 bg-yellow-100 dark:bg-yellow-900/50 rounded"></span>Parcial: Pago menor al flujo</div>';
            $html .= '<div class="flex items-center gap-1"><span class="w-3 h-3 bg-purple-100 dark:bg-purple-900/50 rounded"></span>Cubierto: Pagado con anticipo anterior</div>';
            $html .= '</div>';

            // Nota sobre valores recalculados
            $valorOriginal = $data['total_pagos_esperado_original'] ?? 0;
            $totalFlujosData = $data['total_flujos_ajustados'] ?? 0;
            $totalAnticiposData = $data['total_anticipos_generados'] ?? 0;
            $totalConAnticiposData = $data['total_con_anticipos'] ?? 0;

            if (abs($valorOriginal - $totalConAnticiposData) > 0.01 || $totalAnticiposData > 0.01) {
                $html .= '<div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-3 text-xs">';
                $html .= '<div class="flex items-start gap-2">';
                $html .= '<span class="text-blue-600 dark:text-blue-400">ℹ️</span>';
                $html .= '<div class="flex-1">';
                $html .= '<p class="font-semibold text-blue-900 dark:text-blue-100 mb-1">Cálculo de Totales con Anticipos</p>';
                $html .= '<p class="text-blue-800 dark:text-blue-200">';
                $html .= '<strong>Flujos ajustados:</strong> $'.number_format($totalFlujosData, 2).' + ';
                $html .= '<strong>Anticipos generados:</strong> $'.number_format($totalAnticiposData, 2).' = ';
                $html .= '<strong>Total:</strong> $'.number_format($totalConAnticiposData, 2);

                if (abs($valorOriginal - $totalConAnticiposData) < 0.01) {
                    $html .= ' ✅ <span class="text-green-700 dark:text-green-300">(Equivale al total original)</span>';
                }
                $html .= '</p>';
                $html .= '</div></div></div>';
            }

            // Nota sobre VP/VF recalculados
            $vpOriginal = $data['valor_presente_original'] ?? 0;
            $vpRecalculado = $data['valor_presente_total'] ?? 0;

            if (abs($vpOriginal - $vpRecalculado) > 0.01) {
                $html .= '<div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-lg p-3 text-xs">';
                $html .= '<div class="flex items-start gap-2">';
                $html .= '<span class="text-amber-600 dark:text-amber-400">💡</span>';
                $html .= '<div class="flex-1">';
                $html .= '<p class="font-semibold text-amber-900 dark:text-amber-100 mb-1">Valores Presente y Futuro Recalculados</p>';
                $html .= '<p class="text-amber-800 dark:text-amber-200">';
                $html .= 'Los VP y VF se recalcularon para reflejar los pagos reales (incluyendo anticipos). ';
                $html .= '<strong>VP original:</strong> $'.number_format($vpOriginal, 2).' → ';
                $html .= '<strong>VP recalculado:</strong> $'.number_format($vpRecalculado, 2);
                $html .= '</p>';
                $html .= '</div></div></div>';
            }

            $html .= '</div>';
        }

        // MENSAJE FINAL
        $mensajeFinal = $data['mensaje_final'] ?? null;
        if ($mensajeFinal) {
            $html .= "
        <div class='mt-4 bg-gradient-to-r from-blue-50 to-cyan-50 dark:from-blue-950/50 dark:to-cyan-950/50 rounded-xl p-4 border border-blue-300 dark:border-blue-700'>
            <div class='flex items-start gap-3'>
                <span class='text-2xl flex-shrink-0'>💬</span>
                <div class='flex-1'>
                    <h4 class='font-bold text-blue-900 dark:text-blue-100 text-sm mb-1'>RESUMEN</h4>
                    <p class='text-sm text-blue-800 dark:text-blue-200 leading-relaxed'>{$mensajeFinal}</p>
                </div>
            </div>
        </div>";
        }

        $html .= '</div>';

        return new HtmlString($html);
    }

    public static function buildTablaGradienteHtml(callable|array $data): Htmlable
    {
        // Soporte mixto: callable $get o array $data
        $get = is_callable($data)
            ? $data
            : fn (string $key) => $data[$key] ?? null;

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
