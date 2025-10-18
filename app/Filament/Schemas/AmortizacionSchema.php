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

class AmortizacionSchema
{
    public static function configure(Schema $schema, bool $showSaveButton = false): Schema
    {
        return $schema
            ->schema([
                // Campos ocultos para almacenar resultados
                Hidden::make('campos_calculados'),
                Hidden::make('resultados_calculados'),
                Hidden::make('tabla_amortizacion'),
                Hidden::make('mensaje_calculado'),
                Hidden::make('numero_pagos'),
                Hidden::make('tiempo'),

                // Wizard con los diferentes pasos
                Wizard::make([
                    // Paso 1: Información básica
                    Step::make('Información Básica')
                        ->icon('heroicon-o-banknotes')
                        ->completedIcon('heroicon-s-banknotes')
                        ->schema([
                            Section::make('Información Básica del Sistema de Amortización')
                                ->icon('heroicon-o-banknotes')
                                ->description('Seleccione el sistema de amortización y complete los datos del préstamo.')
                                ->schema([
                                    Grid::make(2)->schema([
                                        Select::make('sistema_amortizacion')
                                            ->label('Sistema de Amortización')
                                            ->options([
                                                'frances' => '🇫🇷 Sistema Francés (Cuota Fija)',
                                                'aleman' => '🇩🇪 Sistema Alemán (Amortización Constante)',
                                                'americano' => '🇺🇸 Sistema Americano (Solo Intereses)',
                                            ])
                                            ->required()
                                            ->default('frances')
                                            ->columnSpan(2)
                                            ->searchable()
                                            ->helperText('Seleccione el método de amortización del préstamo')
                                            ->live()
                                            ->afterStateUpdated(function (callable $set) {
                                                $set('campos_calculados', null);
                                                $set('resultados_calculados', null);
                                                $set('tabla_amortizacion', null);
                                                $set('mensaje_calculado', null);
                                            }),
                                        TextInput::make('monto_prestamo')
                                            ->rules(['nullable', 'numeric', 'min:0'])
                                            ->validationMessages([
                                                'min' => 'El monto del préstamo debe ser mayor o igual a 0',
                                            ])
                                            ->label('Monto del Préstamo')
                                            ->numeric()
                                            ->prefix('$')
                                            ->placeholder('Ejemplo: 100000')
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(function (callable $set) {
                                                $set('campos_calculados', null);
                                                $set('resultados_calculados', null);
                                                $set('tabla_amortizacion', null);
                                                $set('mensaje_calculado', null);
                                            }),

                                        TextInput::make('cuota_fija')
                                            ->rules(['nullable', 'numeric', 'min:0'])
                                            ->validationMessages([
                                                'min' => 'La cuota fija debe ser mayor o igual a 0',
                                            ])
                                            ->label('Cuota Fija')
                                            ->numeric()
                                            ->prefix('$')
                                            ->placeholder('Ejemplo: 402.11')
                                            ->hint('Solo sistema Francés')
                                            ->live(onBlur: true)

                                            ->afterStateUpdated(function (callable $set) {
                                                $set('campos_calculados', null);
                                                $set('resultados_calculados', null);
                                                $set('tabla_amortizacion', null);
                                                $set('mensaje_calculado', null);
                                            }),
                                    ])->visible(fn (callable $get) => $get('sistema_amortizacion') === 'frances'),

                                    Grid::make(2)->schema([
                                        Select::make('sistema_amortizacion')
                                            ->label('Sistema de Amortización')
                                            ->options([
                                                'frances' => '🇫🇷 Sistema Francés (Cuota Fija)',
                                                'aleman' => '🇩🇪 Sistema Alemán (Amortización Constante)',
                                                'americano' => '🇺🇸 Sistema Americano (Solo Intereses)',
                                            ])
                                            ->required()
                                            ->default('frances')
                                            ->searchable()
                                            ->helperText('Seleccione el método de amortización del préstamo')
                                            ->live()
                                            ->columnSpan(2)
                                            ->afterStateUpdated(function (callable $set) {
                                                $set('campos_calculados', null);
                                                $set('resultados_calculados', null);
                                                $set('tabla_amortizacion', null);
                                                $set('mensaje_calculado', null);
                                            }),

                                        TextInput::make('monto_prestamo')
                                            ->rules(['nullable', 'numeric', 'min:0'])
                                            ->validationMessages([
                                                'min' => 'El monto del préstamo debe ser mayor o igual a 0',
                                            ])
                                            ->label('Monto del Préstamo')
                                            ->numeric()
                                            ->prefix('$')
                                            ->placeholder('Ejemplo: 100000')
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(function (callable $set) {
                                                $set('campos_calculados', null);
                                                $set('resultados_calculados', null);
                                                $set('tabla_amortizacion', null);
                                                $set('mensaje_calculado', null);
                                            }),

                                        TextInput::make('cuota_inicial')
                                            ->rules(['nullable', 'numeric', 'min:0'])
                                            ->validationMessages([
                                                'min' => 'La cuota inicial debe ser mayor o igual a 0',
                                            ])
                                            ->label('Cuota Inicial')
                                            ->numeric()
                                            ->prefix('$')
                                            ->placeholder('Ejemplo: 1500')
                                            ->hint('Solo sistema Alemán')
                                            ->live(onBlur: true)
                                            ->visible(fn (callable $get) => $get('sistema_amortizacion') === 'aleman')
                                            ->afterStateUpdated(function (callable $set) {
                                                $set('campos_calculados', null);
                                                $set('resultados_calculados', null);
                                                $set('tabla_amortizacion', null);
                                                $set('mensaje_calculado', null);
                                            }),

                                        TextInput::make('cuota_periodica')
                                            ->rules(['nullable', 'numeric', 'min:0'])
                                            ->validationMessages([
                                                'min' => 'La cuota periódica debe ser mayor o igual a 0',
                                            ])
                                            ->label('Cuota Periódica de Interés')
                                            ->numeric()
                                            ->prefix('$')
                                            ->placeholder('Ejemplo: 500')
                                            ->hint('Solo sistema Americano')
                                            ->live(onBlur: true)
                                            ->visible(fn (callable $get) => $get('sistema_amortizacion') === 'americano')
                                            ->afterStateUpdated(function (callable $set) {
                                                $set('campos_calculados', null);
                                                $set('resultados_calculados', null);
                                                $set('tabla_amortizacion', null);
                                                $set('mensaje_calculado', null);
                                            }),
                                    ])->visible(fn (callable $get) => $get('sistema_amortizacion') !== 'frances'),
                                ]),
                        ]),

                    // Paso 2: Tasa de interés (igual que anualidad)
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
                                                $set('campos_calculados', null);
                                                $set('resultados_calculados', null);
                                                $set('tabla_amortizacion', null);
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
                                                $set('tabla_amortizacion', null);
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
                                                $set('tabla_amortizacion', null);
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
                                                $set('tabla_amortizacion', null);
                                                $set('mensaje_calculado', null);
                                            }),
                                    ]),
                                ]),
                        ]),

                    // Paso 3: Configuración de tiempo y pagos (igual que anualidad)
                    Step::make('Numero de pagos')
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
                                        ->visible(fn (callable $get) => $get('modo_tiempo_pagos') === null)
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
                                            $set('tabla_amortizacion', null);
                                            $set('mensaje_calculado', null);
                                        }),

                                    // MODO MANUAL
                                    Grid::make(2)->schema([
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
                                                $set('tabla_amortizacion', null);
                                                $set('mensaje_calculado', null);
                                            }),

                                        TextInput::make('numero_pagos')
                                            ->rules(['nullable', 'integer', 'min:1'])
                                            ->validationMessages([
                                                'min' => 'El número de pagos debe ser mayor o igual a 1',
                                            ])
                                            ->label('Número de Pagos (n)')
                                            ->numeric()
                                            ->placeholder('Ejemplo: 60')
                                            ->hint(fn (callable $get) => $get('sistema_amortizacion') === 'americano'
                                                ? 'Obligatorio en sistema Americano'
                                                : 'Total de pagos a realizar')
                                            ->required(fn (callable $get) => $get('sistema_amortizacion') === 'americano')
                                            ->visible(fn (callable $get) => $get('modo_tiempo_pagos') === 'manual')
                                            ->live()
                                            ->afterStateUpdated(function (callable $set, callable $get) {
                                                $set('campos_calculados', null);
                                                $set('resultados_calculados', null);
                                                $set('tabla_amortizacion', null);
                                                $set('mensaje_calculado', null);
                                            }),
                                    ])->visible(fn (callable $get) => $get('modo_tiempo_pagos') === 'manual'),

                                    // MODO AÑOS + FRECUENCIA
                                    Grid::make(12)->schema([
                                        Select::make('modo_tiempo_pagos')
                                            ->label('Método para determinar número de pagos')
                                            ->options([
                                                'manual' => 'Ingresar número de pagos directamente',
                                                'anios_frecuencia' => 'Calcular con tiempo y frecuencia',
                                                'fechas_frecuencia' => 'Calcular desde fechas y frecuencia',
                                            ])
                                            ->default('manual')
                                            ->live()
                                            ->columnSpan(6)
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
                                                $set('tabla_amortizacion', null);
                                                $set('mensaje_calculado', null);
                                            }),

                                        TextInput::make('numero_pagos_calculado_anios')
                                            ->label('Número de Pagos Calculado')
                                            ->disabled()
                                            ->columnSpan(6)
                                            ->visible(fn (callable $get) => $get('modo_tiempo_pagos') === 'anios_frecuencia')
                                            ->hint('Tiempo x Frecuencia de pagos'),

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
                                                ->step(0.01)
                                                ->visible(fn (callable $get) => ! $get('usar_fechas_tiempo'))
                                                ->live()
                                                ->afterStateUpdated(function (callable $set, callable $get) {
                                                    static::calcularTiempo($set, $get);
                                                    calcularNumeroPagosDesdeTiempo($set, $get);
                                                    $set('campos_calculados', null);
                                                    $set('resultados_calculados', null);
                                                    $set('tabla_amortizacion', null);
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
                                                ->visible(fn (callable $get) => ! $get('usar_fechas_tiempo'))
                                                ->live()
                                                ->afterStateUpdated(function (callable $set, callable $get) {
                                                    static::calcularTiempo($set, $get);
                                                    calcularNumeroPagosDesdeTiempo($set, $get);
                                                    $set('campos_calculados', null);
                                                    $set('resultados_calculados', null);
                                                    $set('tabla_amortizacion', null);
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
                                                ->step(0.01)
                                                ->visible(fn (callable $get) => ! $get('usar_fechas_tiempo'))
                                                ->live()
                                                ->afterStateUpdated(function (callable $set, callable $get) {
                                                    static::calcularTiempo($set, $get);
                                                    calcularNumeroPagosDesdeTiempo($set, $get);
                                                    $set('campos_calculados', null);
                                                    $set('resultados_calculados', null);
                                                    $set('tabla_amortizacion', null);
                                                    $set('mensaje_calculado', null);
                                                }),

                                            TextInput::make('tiempo')
                                                ->label('Tiempo calculado')
                                                ->suffix('años')
                                                ->disabled(),
                                        ])
                                            ->columns([
                                                'default' => 1,
                                                'md' => 2,
                                                'xl' => 4,
                                            ])->columnSpan(12),

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
                                                ->visible(fn (callable $get) => $get('modo_tiempo_pagos') === 'anios_frecuencia')
                                                ->live()
                                                ->afterStateUpdated(function (callable $set, callable $get) {
                                                    static::calcularTiempo($set, $get);
                                                    calcularNumeroPagosDesdeTiempo($set, $get);
                                                    $set('campos_calculados', null);
                                                    $set('resultados_calculados', null);
                                                    $set('tabla_amortizacion', null);
                                                    $set('mensaje_calculado', null);
                                                })->visible(fn (callable $get) => $get('modo_tiempo_pagos') === 'anios_frecuencia' && $get('usar_select_frecuencia')),

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
                                                    static::calcularTiempo($set, $get);
                                                    calcularNumeroPagosDesdeTiempo($set, $get);
                                                    $set('campos_calculados', null);
                                                    $set('resultados_calculados', null);
                                                    $set('tabla_amortizacion', null);
                                                    $set('mensaje_calculado', null);
                                                })->visible(fn (callable $get) => $get('modo_tiempo_pagos') === 'anios_frecuencia' && ! $get('usar_select_frecuencia')),

                                            Toggle::make('usar_select_frecuencia')
                                                ->label('Seleccionar frecuencia')
                                                ->default(true)
                                                ->live()
                                                ->extraAttributes(['class' => 'text-center items-center ml-12 mt-1'])
                                                ->inline(false)
                                                ->afterStateUpdated(function (callable $set) {
                                                    $set('campos_calculados', null);
                                                    $set('resultados_calculados', null);
                                                    $set('tabla_amortizacion', null);
                                                    $set('mensaje_calculado', null);
                                                }),
                                        ])
                                            ->columns([
                                                'default' => 1,
                                                'md' => 2,
                                            ])->columnSpan(12),

                                        TextEntry::make('Nota')
                                            ->columnSpan(12)
                                            ->icon('heroicon-o-information-circle')
                                            ->state('Se utilizan gran cantidad de decimales al calcular el tiempo para soportar altas frecuencias de pagos.')
                                            ->visible(fn (callable $get) => $get('modo_tiempo_pagos') === 'anios_frecuencia'),
                                    ])->visible(fn (callable $get) => $get('modo_tiempo_pagos') === 'anios_frecuencia'),

                                    // MODO FECHAS + FRECUENCIA
                                    Grid::make(12)->schema([
                                        Select::make('modo_tiempo_pagos')
                                            ->label('Método para determinar número de pagos')
                                            ->options([
                                                'manual' => 'Ingresar número de pagos directamente',
                                                'anios_frecuencia' => 'Calcular con tiempo y frecuencia',
                                                'fechas_frecuencia' => 'Calcular desde fechas y frecuencia',
                                            ])
                                            ->default('manual')
                                            ->columnSpan(6)
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
                                                $set('tabla_amortizacion', null);
                                                $set('mensaje_calculado', null);
                                            }),

                                        TextInput::make('numero_pagos')
                                            ->label('Número de Pagos Calculado')
                                            ->disabled()
                                            ->columnSpan(6)
                                            ->visible(fn (callable $get) => $get('modo_tiempo_pagos') === 'fechas_frecuencia')
                                            ->hint('Tiempo x Frecuencia de pagos'),

                                        FieldSet::make('Fechas')->schema([
                                            DatePicker::make('fecha_inicio')
                                                ->label('Fecha de Inicio')
                                                ->placeholder('Seleccione la fecha inicial')
                                                ->visible(fn (callable $get) => $get('modo_tiempo_pagos') === 'fechas_frecuencia')
                                                ->live()
                                                ->afterStateUpdated(function (callable $set, callable $get) {
                                                    static::calcularTiempoDesdeFechas($set, $get);
                                                    calcularNumeroPagosDesdeTiempo($set, $get);
                                                    $set('campos_calculados', null);
                                                    $set('resultados_calculados', null);
                                                    $set('tabla_amortizacion', null);
                                                    $set('mensaje_calculado', null);
                                                })->visible(fn (callable $get) => ! $get('usar_select_frecuencia')),

                                            Toggle::make('usar_select_frecuencia')
                                                ->label('Seleccionar frecuencia')
                                                ->default(true)
                                                ->live()
                                                ->extraAttributes(['class' => 'text-center items-center ml-12 mt-1'])
                                                ->inline(false)
                                                ->afterStateUpdated(function (callable $set) {
                                                    $set('campos_calculados', null);
                                                    $set('resultados_calculados', null);
                                                    $set('tabla_amortizacion', null);
                                                    $set('mensaje_calculado', null);
                                                }),
                                        ])
                                            ->columns([
                                                'default' => 1,
                                                'md' => 2,
                                            ])->columnSpan(12),

                                        TextEntry::make('Nota')
                                            ->columnSpan(12)
                                            ->icon('heroicon-o-information-circle')
                                            ->state('Se utilizan gran cantidad de decimales al calcular el tiempo para soportar altas frecuencias de pagos.')
                                            ->visible(fn (callable $get) => $get('modo_tiempo_pagos') === 'fechas_frecuencia'),
                                    ])->visible(fn (callable $get) => $get('modo_tiempo_pagos') === 'fechas_frecuencia'),
                                ]),
                        ]),

                    // Paso 4: Resultados
                    Step::make('Resultados')
                        ->icon('heroicon-o-chart-bar')
                        ->completedIcon('heroicon-s-chart-bar')
                        ->schema([
                            Section::make('Resumen del Sistema de Amortización')
                                ->collapsible()
                                ->icon('heroicon-o-chart-bar')
                                ->description('Resumen completo de los valores calculados del sistema de amortización')
                                ->schema([
                                    Grid::make(1)->schema([
                                        Placeholder::make('_')
                                            ->label('')
                                            ->content(function (callable $get): Htmlable {
                                                return static::buildResultHtml($get);
                                            }),
                                    ]),
                                ]),

                            Section::make('Tabla de Amortización')
                                ->collapsible()
                                ->collapsed()
                                ->icon('heroicon-o-table-cells')
                                ->description('Detalle período por período de la amortización del préstamo')
                                ->schema([
                                    Grid::make(1)->schema([
                                        Placeholder::make('_')
                                            ->label('')
                                            ->content(function (callable $get): Htmlable {
                                                return static::buildTablaAmortizacionHtml($get);
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
     * Construye el HTML para mostrar los resultados del sistema de amortización
     */
    private static function buildResultHtml(callable $get): Htmlable
    {
        $montoPrestamo = $get('monto_prestamo');
        $tasaInteres = $get('tasa_interes');
        $numeroPagos = $get('numero_pagos');
        $sistemaAmortizacion = $get('sistema_amortizacion');

        $camposCalculados = $get('campos_calculados');
        $resultados = $get('resultados_calculados');
        $mensaje = $get('mensaje_calculado');
        $periodicidadTasa = $get('periodicidad_tasa') ?: 12;

        $camposCalculadosArray = $camposCalculados ? json_decode($camposCalculados, true) : [];
        $resultadosArray = $resultados ? json_decode($resultados, true) : [];

        // Validaciones iniciales
        if (empty($montoPrestamo) && empty($tasaInteres) && empty($numeroPagos) && empty($sistemaAmortizacion)) {
            return new HtmlString('
            <div class="text-center py-12 text-gray-500 dark:text-gray-400">
                <div class="text-5xl mb-4">🏦</div>
                <h3 class="text-xl font-semibold mb-2">Complete los campos para calcular</h3>
                <p class="text-sm text-gray-400">Los resultados del sistema de amortización aparecerán aquí</p>
            </div>
        ');
        }

        if (empty($sistemaAmortizacion)) {
            return new HtmlString('
            <div class="text-center py-12">
                <div class="bg-gradient-to-r from-amber-50 to-orange-50 dark:from-amber-950/50 dark:to-orange-950/50 rounded-xl p-8 border border-amber-200 dark:border-amber-800">
                    <div class="text-6xl mb-4">⚠️</div>
                    <h3 class="text-xl font-bold text-amber-900 dark:text-amber-100 mb-3">Sistema no seleccionado</h3>
                    <p class="text-amber-700 dark:text-amber-300 text-lg">Por favor, selecciona un sistema de amortización</p>
                </div>
            </div>
        ');
        }

        if (empty($resultadosArray)) {
            return new HtmlString('
            <div class="text-center py-12 text-gray-500 dark:text-gray-400">
                <div class="text-5xl mb-4">⏳</div>
                <h3 class="text-xl font-semibold mb-2">Listo para calcular</h3>
                <p class="text-sm text-gray-400">Presiona el botón "Calcular" para ver los resultados</p>
            </div>
        ');
        }

        // Inicio HTML
        $html = '<div class="space-y-5">';

        // ============================================
        // HEADER - Sistema Seleccionado
        // ============================================
        $sistemaInfo = match ($sistemaAmortizacion) {
            'frances' => ['titulo' => '🇫🇷 Sistema Francés', 'desc' => 'Cuota fija durante todo el período', 'color' => 'purple'],
            'aleman' => ['titulo' => '🇩🇪 Sistema Alemán', 'desc' => 'Amortización constante, cuota decreciente', 'color' => 'blue'],
            'americano' => ['titulo' => '🇺🇸 Sistema Americano', 'desc' => 'Solo intereses, capital al final', 'color' => 'indigo'],
            default => ['titulo' => 'Sistema de Amortización', 'desc' => 'Sistema seleccionado', 'color' => 'gray']
        };

        $colorClass = $sistemaInfo['color'];
        $html .= "
        <div class='bg-gradient-to-r from-{$colorClass}-50 to-{$colorClass}-100 dark:from-{$colorClass}-950/50 dark:to-{$colorClass}-800/50 rounded-xl p-5 border border-{$colorClass}-200 dark:border-{$colorClass}-800'>
            <div class='flex items-center gap-3'>
                <span class='text-3xl'>🏦</span>
                <div>
                    <h3 class='text-lg font-bold text-{$colorClass}-900 dark:text-{$colorClass}-100'>{$sistemaInfo['titulo']}</h3>
                    <p class='text-sm text-{$colorClass}-700 dark:text-{$colorClass}-300'>{$sistemaInfo['desc']}</p>
                </div>
            </div>
        </div>
    ";

        // ============================================
        // BLOQUE 1: Parámetros Base (Layout Horizontal Compacto)
        // ============================================
        $html .= '<div class="bg-gradient-to-br from-slate-50 to-slate-100 dark:from-slate-900/50 dark:to-slate-800/50 rounded-xl p-4 border border-slate-200 dark:border-slate-700">';
        $html .= '<h4 class="text-sm font-bold text-slate-700 dark:text-slate-300 mb-3 flex items-center gap-2">
              <span>📋</span> PARÁMETROS DEL PRÉSTAMO
              </h4>';

        // Grid de 3 columnas para datos base
        $html .= '<div class="grid grid-cols-3 gap-3 mb-3">';

        // Monto
        $isCalculated = in_array('monto_prestamo', $camposCalculadosArray);
        $displayValue = isset($resultadosArray['monto_prestamo'])
            ? '$'.number_format($resultadosArray['monto_prestamo'], 2)
            : (is_numeric($montoPrestamo) ? '$'.number_format($montoPrestamo, 2) : '--');
        $html .= static::buildCard('Monto', '💰', $displayValue, 'Capital', $isCalculated);

        // Tasa
        $isCalculated = in_array('tasa_interes', $camposCalculadosArray);
        $displayValue = isset($resultadosArray['tasa_interes'])
            ? ($resultadosArray['tasa_interes'].'%')
            : (is_numeric($tasaInteres) ? $tasaInteres.'%' : '--');
        $html .= static::buildCard('Tasa', '📈', $displayValue, 'Nominal', $isCalculated);

        // Pagos
        $isCalculated = in_array('numero_pagos', $camposCalculadosArray);
        $displayValue = isset($resultadosArray['numero_pagos'])
            ? $resultadosArray['numero_pagos']
            : (is_numeric($numeroPagos) ? $numeroPagos : '--');
        $html .= static::buildCard('Pagos', '🔢', $displayValue, 'Cuotas', $isCalculated);

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
                    <span class='text-lg'>📊</span>
                    <span class='text-xs font-semibold text-indigo-900 dark:text-indigo-100'>Periodicidad</span>
                </div>
                <div class='text-right'>
                    <span class='font-bold text-sm text-indigo-900 dark:text-indigo-100'>{$periodicidadTexto}</span>
                    <span class='text-xs text-indigo-600 dark:text-indigo-400 ml-2'>({$periodicidadTasa}/año)</span>
                </div>
            </div>
        </div>
    ";

        $html .= '</div>'; // Fin bloque parámetros

        // ============================================
        // BLOQUE 2: Estructura de Pagos (Según Sistema)
        // ============================================
        $html .= '<div class="space-y-3">';
        $html .= '<h4 class="text-sm font-bold text-gray-700 dark:text-gray-300 flex items-center gap-2">
              <span>💳</span> ESTRUCTURA DE PAGOS
              </h4>';

        if ($sistemaAmortizacion === 'frances' && isset($resultadosArray['cuota_fija'])) {
            // Sistema Francés: Una sola cuota destacada + detalles de primera y última cuota
            $isCalculated = in_array('cuota_fija', $camposCalculadosArray);
            $displayValue = '$'.number_format($resultadosArray['cuota_fija'], 2);

            $html .= '<div class="grid grid-cols-1 gap-3">';
            $html .= static::buildCard('Cuota Fija', '💳', $displayValue, 'Pago constante (capital + interés)', $isCalculated, 'green');
            $html .= '</div>';

            // Detalles de amortización e intereses en 2 columnas
            if (isset($resultadosArray['amortizacion_inicial']) || isset($resultadosArray['interes_inicial'])) {
                $html .= '<div class="grid grid-cols-2 gap-3 mt-3">';

                if (isset($resultadosArray['amortizacion_inicial'])) {
                    $html .= static::buildCard('Amortización Inicial', '📉', '$'.number_format($resultadosArray['amortizacion_inicial'], 2), 'Capital en cuota 1', true, 'cyan');
                }

                if (isset($resultadosArray['amortizacion_final'])) {
                    $html .= static::buildCard('Amortización Final', '📈', '$'.number_format($resultadosArray['amortizacion_final'], 2), 'Capital en última cuota', true, 'cyan');
                }

                if (isset($resultadosArray['interes_inicial'])) {
                    $html .= static::buildCard('Interés Inicial', '💵', '$'.number_format($resultadosArray['interes_inicial'], 2), 'Interés en cuota 1', true, 'orange');
                }

                if (isset($resultadosArray['interes_final'])) {
                    $html .= static::buildCard('Interés Final', '💵', '$'.number_format($resultadosArray['interes_final'], 2), 'Interés en última cuota', true, 'orange');
                }

                $html .= '</div>';
            }
        }

        if ($sistemaAmortizacion === 'aleman') {
            // Sistema Alemán: 3 columnas compactas + amortización constante destacada
            $html .= '<div class="grid grid-cols-1 gap-3">';

            if (isset($resultadosArray['amortizacion_constante'])) {
                $displayValue = '$'.number_format($resultadosArray['amortizacion_constante'], 2);
                $html .= static::buildCard('Amortización Constante', '📊', $displayValue, 'Abono fijo a capital en cada período', true, 'green');
            }

            $html .= '</div>';

            $html .= '<div class="grid grid-cols-3 gap-3 mt-3">';

            if (isset($resultadosArray['cuota_inicial'])) {
                $isCalculated = in_array('cuota_inicial', $camposCalculadosArray);
                $displayValue = '$'.number_format($resultadosArray['cuota_inicial'], 2);
                $html .= static::buildCard('Cuota Inicial', '💳', $displayValue, 'Primera (máx)', $isCalculated, 'blue');
            }

            if (isset($resultadosArray['interes_inicial'])) {
                $html .= static::buildCard('Interés Inicial', '💵', '$'.number_format($resultadosArray['interes_inicial'], 2), 'En cuota 1', true, 'orange');
            }

            if (isset($resultadosArray['cuota_final'])) {
                $displayValue = '$'.number_format($resultadosArray['cuota_final'], 2);
                $html .= static::buildCard('Cuota Final', '💳', $displayValue, 'Última (mín)', true, 'cyan');
            }

            $html .= '</div>';

            // Interés final en su propia fila si existe
            if (isset($resultadosArray['interes_final'])) {
                $html .= '<div class="grid grid-cols-1 gap-3 mt-3">';
                $html .= static::buildCard('Interés Final', '💵', '$'.number_format($resultadosArray['interes_final'], 2), 'Interés en última cuota', true, 'orange');
                $html .= '</div>';
            }
        }

        if ($sistemaAmortizacion === 'americano') {
            // Sistema Americano: 2 columnas
            $html .= '<div class="grid grid-cols-2 gap-3">';

            if (isset($resultadosArray['amortizacion_inicial'])) {
                $html .= static::buildCard('Amortización Inicial', '📉', '$'.number_format($resultadosArray['amortizacion_inicial'], 2), 'Capital en cuota 1', true, 'cyan');
            }

            if (isset($resultadosArray['amortizacion_final'])) {
                $html .= static::buildCard('Amortización Final', '📈', '$'.number_format($resultadosArray['amortizacion_final'], 2), 'Capital en última cuota', true, 'cyan');
            }

            if (isset($resultadosArray['cuota_interes_periodica'])) {
                $isCalculated = in_array('cuota_periodica', $camposCalculadosArray);
                $displayValue = '$'.number_format($resultadosArray['cuota_interes_periodica'], 2);
                $html .= static::buildCard('Cuota Periódica', '💸', $displayValue, 'Solo interés (períodos 1 al n-1)', $isCalculated, 'amber');
            }

            if (isset($resultadosArray['cuota_final'])) {
                $displayValue = '$'.number_format($resultadosArray['cuota_final'], 2);
                $html .= static::buildCard('Pago Final', '💰', $displayValue, 'Capital + último interés', true, 'purple');
            }

            $html .= '</div>';
        }

        $html .= '</div>'; // Fin estructura de pagos

        // ============================================
        // BLOQUE 3: Resumen Financiero Total (Destacado)
        // ============================================
        $html .= '<div class="bg-gradient-to-br from-purple-50 to-pink-50 dark:from-purple-950/50 dark:to-pink-950/50 rounded-xl p-4 border-2 border-purple-300 dark:border-purple-700">';
        $html .= '<h4 class="text-sm font-bold text-purple-900 dark:text-purple-100 mb-3 flex items-center gap-2">
              <span>💎</span> RESUMEN FINANCIERO
              </h4>';

        $html .= '<div class="grid grid-cols-2 gap-3">';

        if (isset($resultadosArray['total_intereses'])) {
            $html .= static::buildCard('Total Intereses', '💸', '$'.number_format($resultadosArray['total_intereses'], 2), 'Costo financiero total', true, 'red');
        }

        if (isset($resultadosArray['total_pagado'])) {
            $html .= static::buildCard('Total a Pagar', '💎', '$'.number_format($resultadosArray['total_pagado'], 2), 'Capital + Intereses', true, 'purple');
        }

        $html .= '</div>';
        $html .= '</div>'; // Fin resumen financiero

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

    /**
     * Construye el HTML para la tabla de amortización
     */
    private static function buildTablaAmortizacionHtml(callable $get): Htmlable
    {
        $tablaJson = $get('tabla_amortizacion');

        if (! $tablaJson) {
            return new HtmlString('
                <div class="text-center py-12 text-gray-500 dark:text-gray-400">
                    <div class="text-5xl mb-4">📊</div>
                    <h3 class="text-xl font-semibold mb-2">Tabla de amortización no disponible</h3>
                    <p class="text-sm text-gray-400">Realiza el cálculo para ver el detalle período por período</p>
                </div>
            ');
        }

        $tabla = json_decode($tablaJson, true);

        if (empty($tabla)) {
            return new HtmlString('
                <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                    <p>No hay datos de amortización disponibles</p>
                </div>
            ');
        }

        $html = '<div class="overflow-x-auto rounded-lg shadow-lg">';
        $html .= '<table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">';

        // Header de la tabla
        $html .= '
            <thead class="bg-gradient-to-r from-purple-100 to-indigo-100 dark:from-purple-900/50 dark:to-indigo-900/50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-bold text-purple-900 dark:text-purple-100 uppercase tracking-wider">Período</th>
                    <th class="px-4 py-3 text-right text-xs font-bold text-purple-900 dark:text-purple-100 uppercase tracking-wider">Saldo Inicial</th>
                    <th class="px-4 py-3 text-right text-xs font-bold text-purple-900 dark:text-purple-100 uppercase tracking-wider">Cuota</th>
                    <th class="px-4 py-3 text-right text-xs font-bold text-purple-900 dark:text-purple-100 uppercase tracking-wider">Interés</th>
                    <th class="px-4 py-3 text-right text-xs font-bold text-purple-900 dark:text-purple-100 uppercase tracking-wider">Amortización</th>
                    <th class="px-4 py-3 text-right text-xs font-bold text-purple-900 dark:text-purple-100 uppercase tracking-wider">Saldo Final</th>
                </tr>
            </thead>
        ';

        // Body de la tabla
        $html .= '<tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">';

        foreach ($tabla as $index => $fila) {
            $rowClass = $index % 2 === 0
                ? 'bg-gray-50 dark:bg-gray-900/30'
                : 'bg-white dark:bg-gray-800';

            // Destacar primera y última fila
            $highlightClass = '';
            if ($index === 0) {
                $highlightClass = 'border-l-4 border-green-500';
            } elseif ($index === count($tabla) - 1) {
                $highlightClass = 'border-l-4 border-purple-500';
            }

            $html .= "<tr class='{$rowClass} {$highlightClass} hover:bg-gray-100 dark:hover:bg-gray-700/50 transition-colors'>
                <td class='px-4 py-3 text-sm font-semibold text-gray-900 dark:text-gray-100'>{$fila['periodo']}</td>
                <td class='px-4 py-3 text-sm text-right text-gray-700 dark:text-gray-300'>\$".number_format($fila['saldo_inicial'], 2)."</td>
                <td class='px-4 py-3 text-sm text-right font-semibold text-blue-700 dark:text-blue-400'>\$".number_format($fila['cuota'], 2)."</td>
                <td class='px-4 py-3 text-sm text-right text-orange-600 dark:text-orange-400'>\$".number_format($fila['interes'], 2)."</td>
                <td class='px-4 py-3 text-sm text-right text-green-600 dark:text-green-400'>\$".number_format($fila['amortizacion'], 2)."</td>
                <td class='px-4 py-3 text-sm text-right font-semibold text-purple-700 dark:text-purple-400'>\$".number_format($fila['saldo_final'], 2).'</td>
            </tr>';
        }

        $html .= '</tbody>';

        // Footer con totales
        $totalCuotas = array_sum(array_column($tabla, 'cuota'));
        $totalIntereses = array_sum(array_column($tabla, 'interes'));
        $totalAmortizacion = array_sum(array_column($tabla, 'amortizacion'));

        $html .= '
            <tfoot class="bg-gradient-to-r from-purple-200 to-indigo-200 dark:from-purple-800/50 dark:to-indigo-800/50">
                <tr>
                    <td class="px-4 py-4 text-sm font-bold text-purple-900 dark:text-purple-100" colspan="2">TOTALES</td>
                    <td class="px-4 py-4 text-sm text-right font-bold text-purple-900 dark:text-purple-100">$'.number_format($totalCuotas, 2).'</td>
                    <td class="px-4 py-4 text-sm text-right font-bold text-purple-900 dark:text-purple-100">$'.number_format($totalIntereses, 2).'</td>
                    <td class="px-4 py-4 text-sm text-right font-bold text-purple-900 dark:text-purple-100">$'.number_format($totalAmortizacion, 2).'</td>
                    <td class="px-4 py-4 text-sm text-right font-bold text-purple-900 dark:text-purple-100">$0.00</td>
                </tr>
            </tfoot>
        ';

        $html .= '</table>';
        $html .= '</div>';

        // Leyenda
        $html .= '
            <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-green-50 dark:bg-green-950/30 rounded-lg p-4 border border-green-200 dark:border-green-800">
                    <div class="flex items-center gap-2 mb-2">
                        <div class="w-4 h-4 bg-green-500 rounded"></div>
                        <span class="font-semibold text-green-900 dark:text-green-100">Primera Cuota</span>
                    </div>
                    <p class="text-sm text-green-700 dark:text-green-300">Inicio del plan de pagos</p>
                </div>

                <div class="bg-purple-50 dark:bg-purple-950/30 rounded-lg p-4 border border-purple-200 dark:border-purple-800">
                    <div class="flex items-center gap-2 mb-2">
                        <div class="w-4 h-4 bg-purple-500 rounded"></div>
                        <span class="font-semibold text-purple-900 dark:text-purple-100">Última Cuota</span>
                    </div>
                    <p class="text-sm text-purple-700 dark:text-purple-300">Finalización del préstamo</p>
                </div>

                <div class="bg-blue-50 dark:bg-blue-950/30 rounded-lg p-4 border border-blue-200 dark:border-blue-800">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-blue-600 dark:text-blue-400 font-bold">💳</span>
                        <span class="font-semibold text-blue-900 dark:text-blue-100">Cuota</span>
                    </div>
                    <p class="text-sm text-blue-700 dark:text-blue-300">Pago total del período</p>
                </div>

                <div class="bg-orange-50 dark:bg-orange-950/30 rounded-lg p-4 border border-orange-200 dark:border-orange-800">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-orange-600 dark:text-orange-400 font-bold">💵</span>
                        <span class="font-semibold text-orange-900 dark:text-orange-100">Interés</span>
                    </div>
                    <p class="text-sm text-orange-700 dark:text-orange-300">Costo financiero del período</p>
                </div>

                <div class="bg-green-50 dark:bg-green-950/30 rounded-lg p-4 border border-green-200 dark:border-green-800">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-green-600 dark:text-green-400 font-bold">📉</span>
                        <span class="font-semibold text-green-900 dark:text-green-100">Amortización</span>
                    </div>
                    <p class="text-sm text-green-700 dark:text-green-300">Abono a capital</p>
                </div>

                <div class="bg-purple-50 dark:bg-purple-950/30 rounded-lg p-4 border border-purple-200 dark:border-purple-800">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-purple-600 dark:text-purple-400 font-bold">💰</span>
                        <span class="font-semibold text-purple-900 dark:text-purple-100">Saldo</span>
                    </div>
                    <p class="text-sm text-purple-700 dark:text-purple-300">Capital pendiente</p>
                </div>
            </div>
        ';

        return new HtmlString($html);
    }

    /**
     * Helper para construir tarjetas de resultados
     */
    private static function buildCard(
        string $title,
        string $icon,
        string $value,
        string $subtitle,
        bool $isCalculated,
        string $color = 'gray'
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
            'amber' => [
                'bg' => 'bg-gradient-to-br from-amber-50 to-yellow-50 border-amber-300 dark:from-amber-950/50 dark:to-yellow-950/50 dark:border-amber-700',
                'text' => 'text-amber-900 dark:text-amber-100',
                'badge' => 'bg-amber-100 text-amber-800 dark:bg-amber-900/50 dark:text-amber-200',
                'subtitle' => 'text-amber-600 dark:text-amber-400',
            ],
            'orange' => [
                'bg' => 'bg-gradient-to-br from-orange-50 to-red-50 border-orange-300 dark:from-orange-950/50 dark:to-red-950/50 dark:border-orange-700',
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
            <div class='rounded-xl p-6 border {$colorClasses['bg']} shadow-sm'>
                <div class='flex items-center justify-between mb-3'>
                    <h4 class='font-semibold {$colorClasses['text']} flex items-center gap-2'>
                        <span>{$icon}</span>
                        {$title}
                    </h4>
                    {$badgeHtml}
                </div>
                <p class='text-2xl font-bold {$colorClasses['text']} mb-2'>{$value}</p>
                <p class='text-sm {$colorClasses['subtitle']}'>{$subtitle}</p>
            </div>
        ";
    }

    /**
     * Método helper para calcular tiempo desde fechas
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
                    // Usar diferencia en segundos para máxima precisión
                    $segundosTotales = $inicio->diffInSeconds($final);
                    $segundosEnUnAno = 365.25 * 24 * 60 * 60; // 31,557,600 segundos
                    $anios = $segundosTotales / $segundosEnUnAno;

                    // Redondear a 8 decimales para soportar altas frecuencias
                    $aniosPreciso = round($anios, 8);

                    $set('tiempo', $aniosPreciso);
                } else {
                    $set('tiempo', null);
                }
            } catch (\Exception $e) {
                $set('tiempo', null);
            }
        } else {
            $set('tiempo', null);
        }

        // Recalcular número de pagos después de actualizar el tiempo
        calcularNumeroPagosDesdeTiempo($set, $get);
    }

    /**
     * Método helper para calcular tiempo desde años, meses y días
     */
    private static function calcularTiempo(callable $set, callable $get): void
    {
        $anio = $get('anio');
        $mes = $get('mes');
        $dia = $get('dia');

        $anioConvertido = $anio + ($mes / 12) + ($dia / 365.25);
        $set('tiempo', number_format($anioConvertido, 8));
    }
}
