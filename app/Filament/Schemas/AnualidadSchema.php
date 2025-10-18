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

class AnualidadSchema
{
    public static function configure(Schema $schema, bool $showSaveButton = false): Schema
    {
        return $schema
            ->schema([
                // Campos ocultos para almacenar resultados
                Hidden::make('campos_calculados'),
                Hidden::make('resultados_calculados'),
                Hidden::make('interes_generado_calculado_VP'),
                Hidden::make('interes_generado_calculado_VF'),
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
                            Section::make('Información Básica de Anualidad (Ordinaria)')
                                ->icon('heroicon-o-banknotes')
                                ->description('Complete los campos conocidos. Pueden calcularse 1 o 2 campos automáticamente según las fórmulas.')
                                ->schema([
                                    Grid::make(3)->schema([
                                        TextInput::make('pago_periodico')
                                            ->rules(['nullable', 'numeric', 'min:0'])
                                            ->validationMessages([
                                                'min' => 'El pago periódico debe ser mayor o igual a 0',
                                            ])
                                            ->label('Pago Periódico (PMT)')
                                            ->numeric()
                                            ->prefix('$')
                                            ->placeholder('Ejemplo: 1000')
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(function (callable $set) {
                                                $set('campos_calculados', null);
                                                $set('resultados_calculados', null);
                                                $set('interes_generado_calculado_VP', null);
                                                $set('interes_generado_calculado_VF', null);
                                                $set('mensaje_calculado', null);
                                            }),

                                        TextInput::make('valor_presente')
                                            ->rules(['nullable', 'numeric', 'min:0'])
                                            ->validationMessages([
                                                'min' => 'El valor presente debe ser mayor o igual a 0',
                                            ])
                                            ->label('Valor Presente (VP)')
                                            ->numeric()
                                            ->prefix('$')
                                            ->placeholder('Ejemplo: 50000')
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(function (callable $set) {
                                                $set('campos_calculados', null);
                                                $set('resultados_calculados', null);
                                                $set('interes_generado_calculado_VP', null);
                                                $set('interes_generado_calculado_VF', null);
                                                $set('mensaje_calculado', null);
                                            }),

                                        TextInput::make('valor_futuro')
                                            ->rules(['nullable', 'numeric', 'min:0'])
                                            ->validationMessages([
                                                'min' => 'El valor futuro debe ser mayor o igual a 0',
                                            ])
                                            ->label('Valor Futuro (VF)')
                                            ->numeric()
                                            ->prefix('$')
                                            ->placeholder('Ejemplo: 100000')
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(function (callable $set) {
                                                $set('campos_calculados', null);
                                                $set('resultados_calculados', null);
                                                $set('interes_generado_calculado_VP', null);
                                                $set('interes_generado_calculado_VF', null);
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
                                                $set('interes_generado_calculado_VP', null);
                                                $set('interes_generado_calculado_VF', null);
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
                                                $set('interes_generado_calculado_VP', null);
                                                $set('interes_generado_calculado_VF', null);
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
                                                $set('campos_calculados', null);
                                                $set('resultados_calculados', null);
                                                $set('interes_generado_calculado_VP', null);
                                                $set('interes_generado_calculado_VF', null);
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
                                                $set('interes_generado_calculado_VP', null);
                                                $set('interes_generado_calculado_VF', null);
                                                $set('mensaje_calculado', null);
                                            }),
                                    ]),
                                ]),
                        ]),

                    // Paso 3: Configuración de tiempo y pagos
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
                                            $set('interes_generado_calculado_VP', null);
                                            $set('interes_generado_calculado_VF', null);
                                            $set('mensaje_calculado', null);
                                        }),
                                    // MODO MANUAL: Ingresar número de pagos directamente
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
                                                $set('interes_generado_calculado_VP', null);
                                                $set('interes_generado_calculado_VF', null);
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
                                            ->hint('Total de pagos a realizar')
                                            ->visible(fn (callable $get) => $get('modo_tiempo_pagos') === 'manual')
                                            ->live()
                                            ->afterStateUpdated(function (callable $set, callable $get) {
                                                $set('campos_calculados', null);
                                                $set('resultados_calculados', null);
                                                $set('interes_generado_calculado_VP', null);
                                                $set('interes_generado_calculado_VF', null);
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
                                                $set('interes_generado_calculado_VP', null);
                                                $set('interes_generado_calculado_VF', null);
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
                                                    $set('campo_calculado', null);
                                                    $set('resultado_calculado', null);
                                                    $set('interes_generado_calculado_VP', null);
                                                    $set('interes_generado_calculado_VF', null);
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
                                                    $set('campo_calculado', null);
                                                    $set('resultado_calculado', null);
                                                    $set('interes_generado_calculado_VP', null);
                                                    $set('interes_generado_calculado_VF', null);
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
                                                    $set('campo_calculado', null);
                                                    $set('resultado_calculado', null);
                                                    $set('interes_generado_calculado_VP', null);
                                                    $set('interes_generado_calculado_VF', null);
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
                                            // Nuevo campo de frecuencia para modo años
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
                                                ->default(1)
                                                ->searchable()
                                                ->visible(fn (callable $get) => $get('modo_tiempo_pagos') === 'anios_frecuencia')
                                                ->live()
                                                ->afterStateUpdated(function (callable $set, callable $get) {
                                                    static::calcularTiempo($set, $get);
                                                    calcularNumeroPagosDesdeTiempo($set, $get);
                                                    $set('campos_calculados', null);
                                                    $set('resultados_calculados', null);
                                                    $set('interes_generado_calculado_VP', null);
                                                    $set('interes_generado_calculado_VF', null);
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
                                                ->default(1)
                                                ->visible(fn (callable $get) => ! $get('usar_select_frecuencia'))
                                                ->live()
                                                ->afterStateUpdated(function (callable $set, callable $get) {
                                                    static::calcularTiempo($set, $get);
                                                    calcularNumeroPagosDesdeTiempo($set, $get);
                                                    $set('campo_calculado', null);
                                                    $set('resultado_calculado', null);
                                                    $set('interes_generado_calculado_VP', null);
                                                    $set('interes_generado_calculado_VF', null);
                                                    $set('mensaje_calculado', null);
                                                })->visible(fn (callable $get) => $get('modo_tiempo_pagos') === 'anios_frecuencia' && ! $get('usar_select_frecuencia')),

                                            Toggle::make('usar_select_frecuencia')
                                                ->label('Seleccionar frecuencia')
                                                ->default(true)
                                                ->live()
                                                ->extraAttributes(['class' => 'text-center items-center ml-12 mt-1'])
                                                ->inline(false)
                                                ->afterStateUpdated(function (callable $set) {
                                                    $set('campo_calculado', null);
                                                    $set('resultado_calculado', null);
                                                    $set('interes_generado_calculado_VP', null);
                                                    $set('interes_generado_calculado_VF', null);
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
                                            ->state('Se utilizan gran cantidad de decimales al caluclar el tiempo para soportar altas frecuencias de pagos.')
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
                                                $set('interes_generado_calculado_VP', null);
                                                $set('interes_generado_calculado_VF', null);
                                                $set('mensaje_calculado', null);
                                            }),

                                        TextInput::make('numero_pagos')
                                            ->label('Número de Pagos Calculado')
                                            ->disabled()
                                            ->columnSpan(6)
                                            ->visible(fn (callable $get) => $get('modo_tiempo_pagos') === 'fechas_frecuencia')
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
                                                    $set('interes_generado_calculado_VP', null);
                                                    $set('interes_generado_calculado_VF', null);
                                                    $set('mensaje_calculado', null);
                                                }),

                                            DatePicker::make('fecha_final')
                                                ->label('Fecha Final')
                                                ->placeholder('Seleccione la fecha final')
                                                ->visible(fn (callable $get) => $get('modo_tiempo_pagos') === 'fechas_frecuencia')
                                                ->live()
                                                ->afterStateUpdated(function (callable $set, callable $get) {
                                                    static::calcularTiempoDesdeFechas($set, $get);
                                                    calcularNumeroPagosDesdeTiempo($set, $get);
                                                    $set('campos_calculados', null);
                                                    $set('resultados_calculados', null);
                                                    $set('interes_generado_calculado_VP', null);
                                                    $set('interes_generado_calculado_VF', null);
                                                    $set('mensaje_calculado', null);
                                                }),

                                            TextInput::make('tiempo')
                                                ->label('Tiempo Calculado')
                                                ->suffix('años')
                                                ->disabled()
                                                ->visible(fn (callable $get) => $get('modo_tiempo_pagos') === 'fechas_frecuencia'),
                                        ])->columns([
                                            'default' => 1,
                                            'lg' => 3,
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
                                                ->default(1)
                                                ->searchable()
                                                ->live()
                                                ->afterStateUpdated(function (callable $set, callable $get) {
                                                    static::calcularTiempoDesdeFechas($set, $get);
                                                    calcularNumeroPagosDesdeTiempo($set, $get);
                                                    $set('campos_calculados', null);
                                                    $set('resultados_calculados', null);
                                                    $set('interes_generado_calculado_VP', null);
                                                    $set('interes_generado_calculado_VF', null);
                                                    $set('mensaje_calculado', null);
                                                })->visible(fn (callable $get) => $get('usar_select_frecuencia')),

                                            TextInput::make('frecuencia_anios')
                                                ->rules(['nullable', 'integer', 'min:1'])
                                                ->validationMessages([
                                                    'min' => 'La frecuencia debe ser mayor o igual a 1',
                                                ])
                                                ->label('Frecuencia (numérica)')
                                                ->numeric()
                                                ->placeholder('12 para mensual')
                                                ->hint('Veces por año')
                                                ->default(1)
                                                ->live()
                                                ->afterStateUpdated(function (callable $set, callable $get) {
                                                    static::calcularTiempoDesdeFechas($set, $get);
                                                    calcularNumeroPagosDesdeTiempo($set, $get);
                                                    $set('campo_calculado', null);
                                                    $set('resultado_calculado', null);
                                                    $set('interes_generado_calculado_VP', null);
                                                    $set('interes_generado_calculado_VF', null);
                                                    $set('mensaje_calculado', null);
                                                })->visible(fn (callable $get) => ! $get('usar_select_frecuencia')),

                                            Toggle::make('usar_select_frecuencia')
                                                ->label('Seleccionar frecuencia')
                                                ->default(true)
                                                ->live()
                                                ->extraAttributes(['class' => 'text-center items-center ml-12 mt-1'])
                                                ->inline(false)
                                                ->afterStateUpdated(function (callable $set) {
                                                    $set('campo_calculado', null);
                                                    $set('resultado_calculado', null);
                                                    $set('interes_generado_calculado_VP', null);
                                                    $set('interes_generado_calculado_VF', null);
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
                                            ->state('Se utilizan gran cantidad de decimales al caluclar el tiempo para soportar altas frecuencias de pagos.')
                                            ->visible(fn (callable $get) => $get('modo_tiempo_pagos') === 'fechas_frecuencia'),

                                    ])->visible(fn (callable $get) => $get('modo_tiempo_pagos') === 'fechas_frecuencia'),
                                ]),
                        ]),

                    // Paso 4: Resultados
                    Step::make('Resultados')
                        ->icon('heroicon-o-chart-bar')
                        ->completedIcon('heroicon-s-chart-bar')
                        ->schema([
                            Section::make('Detalles del Cálculo')
                                ->collapsible()
                                ->icon('heroicon-o-chart-bar')
                                ->description('Resumen completo de los valores calculados de anualidades')
                                ->schema([
                                    Grid::make(1)->schema([
                                        Placeholder::make('_')
                                            ->label('')
                                            ->content(function (callable $get): Htmlable {
                                                // Obtener valores de campos principales
                                                $pagoPeriodicoInput = $get('pago_periodico');
                                                $valorPresenteInput = $get('valor_presente');
                                                $valorFuturoInput = $get('valor_futuro');
                                                $tasaInteresInput = $get('tasa_interes');
                                                $numeroPagosInput = $get('numero_pagos');

                                                // Obtener valores de resultados calculados (campos ocultos)
                                                $camposCalculados = $get('campos_calculados');
                                                $resultados = $get('resultados_calculados');
                                                $interesGeneradoVP = $get('interes_generado_calculado_VP');
                                                $interesGeneradoVF = $get('interes_generado_calculado_VF');
                                                $mensaje = $get('mensaje_calculado');

                                                $periodicidadTasa = $get('periodicidad_tasa') ?: 12;

                                                // Decodificar resultados si existen
                                                $camposCalculadosArray = $camposCalculados ? json_decode($camposCalculados, true) : [];
                                                $resultadosArray = $resultados ? json_decode($resultados, true) : [];

                                                // Contar campos vacíos (solo los principales)
                                                $emptyFields = [];
                                                $fieldsToCheck = ['pago_periodico', 'valor_presente', 'valor_futuro', 'tasa_interes'];
                                                foreach ($fieldsToCheck as $field) {
                                                    $value = $get($field);
                                                    if ($value === null || $value === '' || $value === 0) {
                                                        $emptyFields[] = $field;
                                                    }
                                                }

                                                // Verificar si el número de pagos está vacío
                                                if (! $numeroPagosInput) {
                                                    $emptyFields[] = 'numero_pagos';
                                                }

                                                // Si hay un cálculo exitoso, mostrar resultados
                                                if (! empty($camposCalculadosArray) && ! empty($resultadosArray)) {
                                                    return static::buildResultHtml(
                                                        $pagoPeriodicoInput, $valorPresenteInput, $valorFuturoInput,
                                                        $tasaInteresInput, $numeroPagosInput,
                                                        $periodicidadTasa, $camposCalculadosArray,
                                                        $resultadosArray, $interesGeneradoVP, $interesGeneradoVF, $mensaje
                                                    );
                                                }

                                                // Si no hay datos suficientes, mostrar mensaje inicial
                                                if (empty($pagoPeriodicoInput) && empty($valorPresenteInput) && empty($valorFuturoInput) &&
                                                    empty($tasaInteresInput) && empty($numeroPagosInput)) {
                                                    return new HtmlString('
                                                        <div class="text-center py-12 text-gray-500 dark:text-gray-400">
                                                            <div class="text-5xl mb-4">💰</div>
                                                            <h3 class="text-xl font-semibold mb-2">Complete los campos para ver los detalles</h3>
                                                            <p class="text-sm text-gray-400">Los resultados de anualidades aparecerán aquí después del cálculo</p>
                                                        </div>
                                                    ');
                                                }

                                                // Validación: debe haber entre 1 y 2 campos vacíos
                                                if (count($emptyFields) === 0 || count($emptyFields) > 2) {
                                                    $errorMessage = count($emptyFields) === 0
                                                        ? 'Debes dejar entre 1 y 2 campos vacíos para calcular anualidades.'
                                                        : 'Solo puedes dejar máximo 2 campos vacíos. Actualmente hay '.count($emptyFields).' campos vacíos.';

                                                    return new HtmlString('
                                                        <div class="text-center py-12">
                                                            <div class="bg-gradient-to-r from-red-50 to-orange-50 dark:from-red-950/50 dark:to-orange-950/50 rounded-xl p-8 border border-red-200 dark:border-red-800">
                                                                <div class="text-6xl mb-4">⚠️</div>
                                                                <h3 class="text-xl font-bold text-red-900 dark:text-red-100 mb-3">Error de Validación</h3>
                                                                <p class="text-red-700 dark:text-red-300 mb-4 text-lg">'.$errorMessage.'</p>
                                                                <div class="bg-red-100 dark:bg-red-900/50 rounded-lg p-4 border border-red-300 dark:border-red-700">
                                                                    <p class="text-sm text-red-800 dark:text-red-200">
                                                                        <strong>Instrucciones:</strong><br>
                                                                        • Completa al menos 3 campos conocidos<br>
                                                                        • Deja vacíos 1 o 2 campos que deseas calcular<br>
                                                                        • Las anualidades pueden calcular múltiples valores automáticamente<br>
                                                                        • Presiona el botón "Calcular" para obtener los resultados
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    ');
                                                }

                                                // Si hay entre 1 y 2 campos vacíos pero aún no se ha calculado
                                                return new HtmlString('
                                                    <div class="text-center py-12 text-gray-500 dark:text-gray-400">
                                                        <div class="text-5xl mb-4">⏳</div>
                                                        <h3 class="text-xl font-semibold mb-2">Listo para calcular anualidades</h3>
                                                        <p class="text-sm text-gray-400">Presiona el botón "Calcular" para ver los resultados</p>
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
     * Construye el HTML para mostrar los resultados de anualidades
     */
    private static function buildResultHtml(
        $pagoPeriodicoInput, $valorPresenteInput, $valorFuturoInput,
        $tasaInteresInput, $numeroPagosInput, $periodicidadTasa,
        $camposCalculadosArray, $resultadosArray, $interesGeneradoVP, $interesGeneradoVF, $mensaje
    ): Htmlable {

        // Inicio HTML
        $html = '<div class="space-y-5">';

        // ============================================
        // HEADER - Anualidades
        // ============================================
        $html .= '
        <div class="bg-gradient-to-r from-yellow-50 to-yellow-100 dark:from-yellow-950/50 dark:to-yellow-800/50 rounded-xl p-5 border border-yellow-200 dark:border-yellow-800">
            <div class="flex items-center gap-3">
                <span class="text-3xl">💰</span>
                <div>
                    <h3 class="text-lg font-bold text-yellow-900 dark:text-yellow-100">Cálculo de Anualidades</h3>
                    <p class="text-sm text-yellow-700 dark:text-yellow-300">Análisis de flujos periódicos constantes</p>
                </div>
            </div>
        </div>
    ';

        // ============================================
        // BLOQUE 1: Parámetros Básicos
        // ============================================
        $html .= '<div class="bg-gradient-to-br from-slate-50 to-slate-100 dark:from-slate-900/50 dark:to-slate-800/50 rounded-xl p-4 border border-slate-200 dark:border-slate-700">';
        $html .= '<h4 class="text-sm font-bold text-slate-700 dark:text-slate-300 mb-3 flex items-center gap-2">
              <span>📋</span> PARÁMETROS BÁSICOS
              </h4>';

        $html .= '<div class="grid grid-cols-3 gap-3">';

        // Pago Periódico
        $isCalculated = in_array('pago_periodico', $camposCalculadosArray);
        $displayValue = $isCalculated
            ? '$'.number_format($resultadosArray['pago_periodico'] ?? 0, 2)
            : (is_numeric($pagoPeriodicoInput) ? '$'.number_format($pagoPeriodicoInput, 2) : '--');
        $html .= static::buildCard('Pago', '💳', $displayValue, 'Por período', $isCalculated);

        // Tasa de Interés
        $isCalculated = in_array('tasa_interes', $camposCalculadosArray);
        $displayValue = $isCalculated
            ? $resultadosArray['tasa_interes'].'%'
            : (is_numeric($tasaInteresInput) ? $tasaInteresInput.'%' : '--');
        $html .= static::buildCard('Tasa', '📈', $displayValue, 'Nominal', $isCalculated);

        // Número de Pagos
        $isCalculated = in_array('numero_pagos', $camposCalculadosArray);
        $displayValue = $isCalculated
            ? $resultadosArray['numero_pagos'] ?? 0
            : (is_numeric($numeroPagosInput) ? $numeroPagosInput : '--');
        $html .= static::buildCard('Pagos', '🔢', $displayValue, 'Períodos', $isCalculated);

        $html .= '</div>';

        // Periodicidad en línea horizontal
        if ($periodicidadTasa) {
            $periodicidadTexto = match ((int) $periodicidadTasa) {
                1 => 'Anual', 2 => 'Semestral', 4 => 'Trimestral', 6 => 'Bimestral',
                12 => 'Mensual', 24 => 'Quincenal', 52 => 'Semanal',
                360 => 'Diaria Comercial', 365 => 'Diaria',
                default => $periodicidadTasa.' veces/año'
            };

            $html .= "
            <div class='bg-indigo-50/70 dark:bg-indigo-950/30 rounded-lg p-2.5 border border-indigo-200 dark:border-indigo-800 mt-3'>
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
        }

        $html .= '</div>'; // Fin parámetros básicos

        // ============================================
        // BLOQUE 2: Valores Temporales (VP y VF)
        // ============================================
        $html .= '<div class="space-y-3">';
        $html .= '<h4 class="text-sm font-bold text-gray-700 dark:text-gray-300 flex items-center gap-2">
              <span>💰</span> VALORES TEMPORALES
              </h4>';

        $html .= '<div class="grid grid-cols-2 gap-3">';

        // Valor Presente
        $isCalculated = in_array('valor_presente', $camposCalculadosArray);
        $displayValue = $isCalculated
            ? '$'.number_format($resultadosArray['valor_presente'] ?? 0, 2)
            : (is_numeric($valorPresenteInput) ? '$'.number_format($valorPresenteInput, 2) : '--');

        if ($isCalculated || is_numeric($valorPresenteInput)) {
            $bgColor = $isCalculated ? 'from-green-50 to-emerald-50 border-green-200 dark:from-green-950/50 dark:to-emerald-950/50 dark:border-green-700' : 'from-blue-50 to-cyan-50 border-blue-200 dark:from-blue-950/50 dark:to-cyan-950/50 dark:border-blue-700';
            $textColor = $isCalculated ? 'text-green-900 dark:text-green-100' : 'text-blue-900 dark:text-blue-100';
            $subColor = $isCalculated ? 'text-green-600 dark:text-green-400' : 'text-blue-600 dark:text-blue-400';
            $badgeColor = $isCalculated ? 'bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-200' : 'bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-200';

            $html .= "
            <div class='rounded-lg p-4 border bg-gradient-to-br {$bgColor} shadow-md'>
                <div class='flex items-center justify-between mb-2'>
                    <div class='flex items-center gap-2'>
                        <span class='text-xl'>📊</span>
                        <h5 class='font-bold {$textColor} text-sm'>Valor Presente</h5>
                    </div>
                    ".($isCalculated ? "<span class='px-2 py-0.5 text-xs font-medium rounded-full {$badgeColor}'>✨ Calculado</span>" : "<span class='px-2 py-0.5 text-xs font-medium rounded-full {$badgeColor}'>📝</span>")."
                </div>
                <p class='text-2xl font-bold {$textColor}'>{$displayValue}</p>
                <p class='text-xs {$subColor} mt-1'>Valor actual neto</p>
            </div>
        ";
        }

        // Valor Futuro
        $isCalculated = in_array('valor_futuro', $camposCalculadosArray);
        $displayValue = $isCalculated
            ? '$'.number_format($resultadosArray['valor_futuro'] ?? 0, 2)
            : (is_numeric($valorFuturoInput) ? '$'.number_format($valorFuturoInput, 2) : '--');

        if ($isCalculated || is_numeric($valorFuturoInput)) {
            $bgColor = $isCalculated ? 'from-purple-50 to-pink-50 border-purple-200 dark:from-purple-950/50 dark:to-pink-950/50 dark:border-purple-700' : 'from-blue-50 to-cyan-50 border-blue-200 dark:from-blue-950/50 dark:to-cyan-950/50 dark:border-blue-700';
            $textColor = $isCalculated ? 'text-purple-900 dark:text-purple-100' : 'text-blue-900 dark:text-blue-100';
            $subColor = $isCalculated ? 'text-purple-600 dark:text-purple-400' : 'text-blue-600 dark:text-blue-400';
            $badgeColor = $isCalculated ? 'bg-purple-100 text-purple-800 dark:bg-purple-900/50 dark:text-purple-200' : 'bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-200';

            $html .= "
            <div class='rounded-lg p-4 border bg-gradient-to-br {$bgColor} shadow-md'>
                <div class='flex items-center justify-between mb-2'>
                    <div class='flex items-center gap-2'>
                        <span class='text-xl'>🎯</span>
                        <h5 class='font-bold {$textColor} text-sm'>Valor Futuro</h5>
                    </div>
                    ".($isCalculated ? "<span class='px-2 py-0.5 text-xs font-medium rounded-full {$badgeColor}'>✨ Calculado</span>" : "<span class='px-2 py-0.5 text-xs font-medium rounded-full {$badgeColor}'>📝</span>")."
                </div>
                <p class='text-2xl font-bold {$textColor}'>{$displayValue}</p>
                <p class='text-xs {$subColor} mt-1'>Valor acumulado final</p>
            </div>
        ";
        }

        $html .= '</div>';
        $html .= '</div>'; // Fin valores temporales

        // ============================================
        // BLOQUE 3: Análisis Financiero (Intereses)
        // ============================================
        if ($interesGeneradoVP || $interesGeneradoVF) {
            $html .= '<div class="space-y-3">';
            $html .= '<h4 class="text-sm font-bold text-gray-700 dark:text-gray-300 flex items-center gap-2">
                  <span>💎</span> ANÁLISIS FINANCIERO
                  </h4>';

            $html .= '<div class="grid grid-cols-2 gap-3">';

            // Costo Financiero (VP)
            if ($interesGeneradoVP) {
                $html .= static::buildCard('Costo Financiero', '💸', '$'.number_format($interesGeneradoVP, 2), 'Interés total pagado', true, 'slate');
            }

            // Rendimiento (VF)
            if ($interesGeneradoVF) {
                $html .= static::buildCard('Rendimiento', '💎', '$'.number_format($interesGeneradoVF, 2), 'Ganancia total', true, 'amber');
            }

            $html .= '</div>';
            $html .= '</div>'; // Fin análisis financiero
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
    private static function calcularTiempoDesdeFechas(callable $set, callable $get): void
    {
        $fechaInicio = $get('fecha_inicio');
        $fechaFinal = $get('fecha_final');

        if ($fechaInicio && $fechaFinal) {
            try {
                $inicio = Carbon::parse($fechaInicio);
                $final = Carbon::parse($fechaFinal);

                if ($final->greaterThanOrEqualTo($inicio)) {
                    // Método 1: Usando diferencia en segundos para máxima precisión
                    $segundosTotales = $inicio->diffInSeconds($final);
                    $segundosEnUnAno = 365.25 * 24 * 60 * 60; // 31,557,600 segundos
                    $anios = $segundosTotales / $segundosEnUnAno;

                    // Método 2: Alternativo usando microsegundos si necesitas aún más precisión
                    // $microSegundosTotales = $inicio->diffInMicroseconds($final);
                    // $microSegundosEnUnAno = 365.25 * 24 * 60 * 60 * 1000000;
                    // $anios = $microSegundosTotales / $microSegundosEnUnAno;

                    // Redondear a un número apropiado de decimales (ej: 8 decimales)
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

    private static function calcularTiempo(callable $set, callable $get): void
    {
        $anio = $get('anio');
        $mes = $get('mes');
        $dia = $get('dia');

        $anioConvertido = $anio + ($mes / 12) + ($dia / 365.25);
        $set('tiempo', number_format($anioConvertido, 8));
    }

    // Función auxiliar para formateo si necesitas mostrar el valor
    private static function formatearTiempoPreciso($tiempo, $decimales = 6): string
    {
        return number_format($tiempo, $decimales, '.', '');
    }
}
