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
    public static function configure(Schema $schema): Schema
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
                                                $set('interes_generado_calculado_VF', null);;
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
                                            ->visible(fn(callable $get) => !$get('usar_select_periodicidad_tasa'))
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
                                            ->visible(fn(callable $get) => $get('usar_select_periodicidad_tasa'))
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
                                                ->visible(fn(callable $get) => !$get('usar_fechas_tiempo'))
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
                                                ->visible(fn(callable $get) => !$get('usar_fechas_tiempo'))
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
                                                ->visible(fn(callable $get) => !$get('usar_fechas_tiempo'))
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
                                                ->visible(fn(callable $get) => !$get('usar_select_frecuencia'))
                                                ->live()
                                                ->afterStateUpdated(function (callable $set, callable $get) {
                                                    static::calcularTiempo($set, $get);
                                                    calcularNumeroPagosDesdeTiempo($set, $get);
                                                    $set('campo_calculado', null);
                                                    $set('resultado_calculado', null);
                                                    $set('interes_generado_calculado_VP', null);
                                                    $set('interes_generado_calculado_VF', null);
                                                    $set('mensaje_calculado', null);
                                                })->visible(fn (callable $get) => $get('modo_tiempo_pagos') === 'anios_frecuencia' && !$get('usar_select_frecuencia')),

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
                                                })->visible(fn (callable $get) => !$get('usar_select_frecuencia')),

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
                                                })
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
                        ])
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

    /**
     * Construye el HTML para mostrar los resultados de anualidades
     */
    private static function buildResultHtml(
        $pagoPeriodicoInput, $valorPresenteInput, $valorFuturoInput,
        $tasaInteresInput, $numeroPagosInput, $periodicidadTasa,
        $camposCalculadosArray, $resultadosArray, $interesGeneradoVP, $interesGeneradoVF, $mensaje
    ): Htmlable {
        $html = '<div class="space-y-6">';

        // Header con título dinámico
        $html .= '
            <div class="bg-gradient-to-r from-yellow-50 to-yellow-200 dark:from-yellow-950/50 dark:to-yellow-700/50 rounded-xl p-6 border border-yellow-200 dark:border-yellow-800">
                <h3 class="text-xl font-bold text-yellow-900 dark:text-yellow-100 flex items-center gap-3">
                    <span class="text-3xl">💰</span>
                    <div>
                        <div>Resumen de Anualidades</div>
                        <div class="text-sm font-normal text-yellow-600 dark:text-yellow-300">Cálculos financieros completados</div>
                    </div>
                </h3>
            </div>
        ';

        // Grid de valores principales
        $html .= '<div class="grid grid-cols-1 md:grid-cols-2 gap-6">';

        // Pago Periódico
        $isCalculated = in_array('pago_periodico', $camposCalculadosArray);
        $bgClass = $isCalculated ? 'bg-gradient-to-br from-green-50 to-emerald-50 border-green-300 dark:from-green-950/50 dark:to-emerald-950/50 dark:border-green-700' : 'bg-gray-50 border-gray-200 dark:bg-gray-900/50 dark:border-gray-700';
        $textClass = $isCalculated ? 'text-green-900 dark:text-green-100' : 'text-gray-900 dark:text-gray-100';
        $badgeClass = $isCalculated ? 'bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-200' : 'bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-200';

        $displayValue = $isCalculated
            ? '$'.number_format($resultadosArray['pago_periodico'] ?? 0, 2)
            : (is_numeric($pagoPeriodicoInput) ? '$'.number_format($pagoPeriodicoInput, 2) : '--');

        $html .= "
            <div class='rounded-xl p-6 border {$bgClass} shadow-sm'>
                <div class='flex items-center justify-between mb-3'>
                    <h4 class='font-semibold {$textClass} flex items-center gap-2'>
                        <span>💳</span>
                        Pago Periódico (PMT)
                    </h4>
                    ".($isCalculated ? "<span class='px-3 py-1 text-xs font-medium rounded-full {$badgeClass}'>✨ Calculado</span>" : "<span class='px-3 py-1 text-xs font-medium rounded-full {$badgeClass}'>📝 Ingresado</span>")."
                </div>
                <p class='text-2xl font-bold {$textClass} mb-2'>{$displayValue}</p>
                <p class='text-sm text-gray-600 dark:text-gray-400'>Pago fijo por período</p>
            </div>
        ";

        // Tasa de Interés
        $isCalculated = in_array('tasa_interes', $camposCalculadosArray);
        $bgClass = $isCalculated ? 'bg-gradient-to-br from-green-50 to-emerald-50 border-green-300 dark:from-green-950/50 dark:to-emerald-950/50 dark:border-green-700' : 'bg-gray-50 border-gray-200 dark:bg-gray-900/50 dark:border-gray-700';
        $textClass = $isCalculated ? 'text-green-900 dark:text-green-100' : 'text-gray-900 dark:text-gray-100';
        $badgeClass = $isCalculated ? 'bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-200' : 'bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-200';

        $displayValue = $isCalculated
            ? $resultadosArray['tasa_interes'].'%'
            : (is_numeric($tasaInteresInput) ? $tasaInteresInput.'%' : '--');

        $html .= "
            <div class='rounded-xl p-6 border {$bgClass} shadow-sm'>
                <div class='flex items-center justify-between mb-3'>
                    <h4 class='font-semibold {$textClass} flex items-center gap-2'>
                        <span>📈</span>
                        Tasa de Interés
                    </h4>
                    ".($isCalculated ? "<span class='px-3 py-1 text-xs font-medium rounded-full {$badgeClass}'>✨ Calculado</span>" : "<span class='px-3 py-1 text-xs font-medium rounded-full {$badgeClass}'>📝 Ingresado</span>")."
                </div>
                <p class='text-2xl font-bold {$textClass} mb-2'>{$displayValue}</p>
                <p class='text-sm text-gray-600 dark:text-gray-400'>Según periodicidad seleccionada</p>
            </div>
        ";

        // Número de Pagos
        $isCalculated = in_array('numero_pagos', $camposCalculadosArray);
        $bgClass = $isCalculated ? 'bg-gradient-to-br from-green-50 to-emerald-50 border-green-300 dark:from-green-950/50 dark:to-emerald-950/50 dark:border-green-700' : 'bg-gray-50 border-gray-200 dark:bg-gray-900/50 dark:border-gray-700';
        $textClass = $isCalculated ? 'text-green-900 dark:text-green-100' : 'text-gray-900 dark:text-gray-100';
        $badgeClass = $isCalculated ? 'bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-200' : 'bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-200';

        $displayValue = $isCalculated
            ? $resultadosArray['numero_pagos'] ?? 0
            : (is_numeric($numeroPagosInput) ? $numeroPagosInput : '--');

        $html .= "
            <div class='rounded-xl p-6 border {$bgClass} shadow-sm'>
                <div class='flex items-center justify-between mb-3'>
                    <h4 class='font-semibold {$textClass} flex items-center gap-2'>
                        <span>🔢</span>
                        Número de Pagos (n)
                    </h4>
                    ".($isCalculated ? "<span class='px-3 py-1 text-xs font-medium rounded-full {$badgeClass}'>✨ Calculado</span>" : "<span class='px-3 py-1 text-xs font-medium rounded-full {$badgeClass}'>📝 Ingresado</span>")."
                </div>
                <p class='text-2xl font-bold {$textClass} mb-2'>{$displayValue}</p>
                <p class='text-sm text-gray-600 dark:text-gray-400'>Total de pagos</p>
            </div>
        ";

        if ($periodicidadTasa || $interesGeneradoVP || $interesGeneradoVF) {

            // Periodicidad de la tasa
            if ($periodicidadTasa) {
                $periodicidadTexto = match ((int) $periodicidadTasa) {
                    1 => 'Anual',
                    2 => 'Semestral',
                    4 => 'Trimestral',
                    6 => 'Bimestral',
                    12 => 'Mensual',
                    24 => 'Quincenal',
                    52 => 'Semanal',
                    360 => 'Diaria Comercial',
                    365 => 'Diaria',
                    default => $periodicidadTasa.' veces/año'
                };

                $html .= "
                    <div class='rounded-lg p-4 border bg-indigo-50 border-indigo-200 dark:bg-indigo-950/50 dark:border-indigo-700 shadow-sm'>
                        <div class='flex justify-between items-center gap-2 mb-2 mt-2'>
                        <div class='flex'>
                            <span class='text-indigo-600 dark:text-indigo-400'>📊</span>
                            <h4 class='font-semibold text-indigo-900 dark:text-indigo-100 text-sm'>Periodicidad de Tasa</h4>
                        </div>
                            ".($isCalculated ? "<span class='px-3 py-1 text-xs font-medium rounded-full {$badgeClass}'>✨ Calculado</span>" : "<span class='px-3 py-1 text-xs font-medium rounded-full {$badgeClass}'>📝 Ingresado</span>")."
                        </div>
                        <p class='text-xl mt-4 font-bold text-indigo-900 dark:text-indigo-100'>{$periodicidadTexto}</p>
                        <p class='sm pt-2 text-indigo-600 dark:text-indigo-400'>{$periodicidadTasa} períodos/año</p>
                    </div>
                ";
            }

            // Valor Presente
            $isCalculated = in_array('valor_presente', $camposCalculadosArray);
            $bgClass = $isCalculated ? 'bg-gradient-to-br from-green-50 to-emerald-50 border-green-300 dark:from-green-950/50 dark:to-emerald-950/50 dark:border-green-700' : 'bg-gray-50 border-gray-200 dark:bg-gray-900/50 dark:border-gray-700';
            $textClass = $isCalculated ? 'text-green-900 dark:text-green-100' : 'text-gray-900 dark:text-gray-100';
            $badgeClass = $isCalculated ? 'bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-200' : 'bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-200';

            $displayValue = $isCalculated
                ? '$'.number_format($resultadosArray['valor_presente'] ?? 0, 2)
                : (is_numeric($valorPresenteInput) ? '$'.number_format($valorPresenteInput, 2) : '--');

            $html .= "
            <div class='rounded-xl p-6 border {$bgClass} shadow-sm'>
                <div class='flex items-center justify-between mb-3'>
                    <h4 class='font-semibold {$textClass} flex items-center gap-2'>
                        <span>📊</span>
                        Valor Presente (VP)
                    </h4>
                    ".($isCalculated ? "<span class='px-3 py-1 text-xs font-medium rounded-full {$badgeClass}'>✨ Calculado</span>" : "<span class='px-3 py-1 text-xs font-medium rounded-full {$badgeClass}'>📝 Ingresado</span>")."
                </div>
                <p class='text-2xl font-bold {$textClass} mb-2'>{$displayValue}</p>
                <p class='text-sm text-gray-600 dark:text-gray-400'>Valor actual neto</p>
            </div>
        ";

            // Valor Futuro
            $isCalculated = in_array('valor_futuro', $camposCalculadosArray);
            $bgClass = $isCalculated ? 'bg-gradient-to-br from-green-50 to-emerald-50 border-green-300 dark:from-green-950/50 dark:to-emerald-950/50 dark:border-green-700' : 'bg-gray-50 border-gray-200 dark:bg-gray-900/50 dark:border-gray-700';
            $textClass = $isCalculated ? 'text-green-900 dark:text-green-100' : 'text-gray-900 dark:text-gray-100';
            $badgeClass = $isCalculated ? 'bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-200' : 'bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-200';

            $displayValue = $isCalculated
                ? '$'.number_format($resultadosArray['valor_futuro'] ?? 0, 2)
                : (is_numeric($valorFuturoInput) ? '$'.number_format($valorFuturoInput, 2) : '--');

            $html .= "
            <div class='rounded-xl p-6 border {$bgClass} shadow-sm'>
                <div class='flex items-center justify-between mb-3'>
                    <h4 class='font-semibold {$textClass} flex items-center gap-2'>
                        <span>🎯</span>
                        Valor Futuro (VF)
                    </h4>
                    ".($isCalculated ? "<span class='px-3 py-1 text-xs font-medium rounded-full {$badgeClass}'>✨ Calculado</span>" : "<span class='px-3 py-1 text-xs font-medium rounded-full {$badgeClass}'>📝 Ingresado</span>")."
                </div>
                <p class='text-2xl font-bold {$textClass} mb-2'>{$displayValue}</p>
                <p class='text-sm text-gray-600 dark:text-gray-400'>Valor acumulado final</p>
            </div>
        ";

            if ($interesGeneradoVP) {
                $html .= "
                    <div class='rounded-lg p-4 border bg-slate-50 border-slate-200 dark:bg-slate-950/50 dark:border-slate-700 shadow-sm'>
                        <div class='flex items-center gap-2 mb-2'>
                            <span class='text-slate-600 dark:text-slate-400'>💸</span>
                            <h4 class='font-semibold text-slate-900 dark:text-slate-100 text-sm'>Costo Financiero</h4>
                        </div>
                        <p class='text-lg font-bold text-slate-900 dark:text-slate-100'>$".number_format($interesGeneradoVP, 2)."</p>
                        <p class='text-xs text-slate-600 dark:text-slate-400'>Ganancia total</p>
                    </div>
                ";
            }
            if ($interesGeneradoVF) {
                $html .= "
                    <div class='rounded-lg p-4 border bg-amber-50 border-amber-200 dark:bg-amber-950/50 dark:border-amber-700 shadow-sm'>
                        <div class='flex items-center gap-2 mb-2'>
                            <span class='text-amber-600 dark:text-amber-400'>💎</span>
                            <h4 class='font-semibold text-amber-900 dark:text-amber-100 text-sm'>Rendimiento</h4>
                        </div>
                        <p class='text-lg font-bold text-amber-900 dark:text-amber-100'>$".number_format($interesGeneradoVF, 2)."</p>
                        <p class='text-xs text-amber-600 dark:text-amber-400'>Ganancia total</p>
                    </div>
                ";
            }

        $html .= '</div>'; // Fin del grid principal

        }

        // Mensaje de resultado si existe
        if ($mensaje) {
            $html .= "
                <div class='bg-gradient-to-r from-blue-50 to-purple-50 dark:from-blue-950/50 dark:to-purple-950/50 rounded-xl p-6 border border-blue-200 dark:border-blue-700 shadow-sm'>
                    <div class='flex items-start gap-4'>
                        <div class='flex-shrink-0 text-3xl'>🎯</div>
                        <div>
                            <h4 class='font-bold text-blue-900 dark:text-blue-100 mb-2 text-lg'>Resultado del Cálculo de Anualidades</h4>
                            <p class='text-blue-800 dark:text-blue-200 leading-relaxed'>{$mensaje}</p>
                        </div>
                    </div>
                </div>
            ";
        }

        $html .= '</div>'; // Fin del contenedor principal

        return new HtmlString($html);
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
