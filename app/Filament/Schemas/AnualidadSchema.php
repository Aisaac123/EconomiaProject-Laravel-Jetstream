<?php

namespace App\Filament\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class AnualidadSchema
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Calculadora de Anualidades')
                ->description('Complete los campos conocidos. El campo vacío será calculado automáticamente.')
                ->icon('heroicon-o-banknotes')
                ->schema([
                    Grid::make(2)->schema([
                        TextInput::make('pago_periodico')
                            ->rules(['nullable', 'numeric', 'min:0'])
                            ->validationMessages([
                                'min' => 'El pago periódico debe ser mayor o igual a 0',
                            ])
                            ->label('Pago Periódico (PMT)')
                            ->numeric()
                            ->prefix('$')
                            ->placeholder('Ejemplo: 1000')
                            ->hint('Monto de cada pago'),

                        TextInput::make('valor_presente')
                            ->rules(['nullable', 'numeric', 'min:0'])
                            ->validationMessages([
                                'min' => 'El valor presente debe ser mayor o igual a 0',
                            ])
                            ->label('Valor Presente (VP)')
                            ->numeric()
                            ->prefix('$')
                            ->placeholder('Ejemplo: 10000')
                            ->hint('Valor actual de la anualidad'),
                    ]),

                    Grid::make(2)->schema([
                        TextInput::make('valor_futuro')
                            ->rules(['nullable', 'numeric', 'min:0'])
                            ->validationMessages([
                                'min' => 'El valor futuro debe ser mayor o igual a 0',
                            ])
                            ->label('Valor Futuro (VF)')
                            ->numeric()
                            ->prefix('$')
                            ->placeholder('Ejemplo: 15000')
                            ->hint('Valor acumulado al final'),

                        TextInput::make('tasa_interes')
                            ->rules(['nullable', 'numeric', 'min:0'])
                            ->validationMessages([
                                'min' => 'La tasa de interés debe ser mayor o igual a 0',
                            ])
                            ->label('Tasa de Interés por Período (r)')
                            ->numeric()
                            ->suffix('%')
                            ->placeholder('Ejemplo: 2.5')
                            ->step(0.01)
                            ->hint('Tasa por período de pago'),
                    ]),

                    Grid::make(2)->schema([
                        TextInput::make('numero_pagos')
                            ->rules(['nullable', 'integer', 'min:1'])
                            ->validationMessages([
                                'min' => 'El número de pagos debe ser mayor o igual a 1',
                            ])
                            ->label('Número de Pagos (n)')
                            ->numeric()
                            ->suffix('pagos')
                            ->placeholder('Ejemplo: 24')
                            ->hint('Total de pagos a realizar'),

                        TextInput::make('resultado')
                            ->label('Resultado Calculado')
                            ->prefix('Resultado:')
                            ->disabled()
                            ->placeholder('Se calculará automáticamente')
                            ->hint('Este campo se completará al enviar'),
                    ]),
                ]),
        ]);
    }
}
