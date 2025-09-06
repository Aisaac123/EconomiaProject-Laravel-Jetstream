<?php

namespace App\Filament\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class InteresCompuestoSchema
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Calculadora de Interés Compuesto')
                ->description('Complete los campos conocidos. El campo vacío será calculado automáticamente.')
                ->icon('heroicon-o-calculator')
                ->schema([
                    Grid::make(2)->schema([
                        TextInput::make('capital')
                            ->rules(['nullable', 'integer', 'min:0'])
                            ->validationMessages([
                                'min' => 'El capital inicial debe ser mayor o igual a 0',
                            ])
                            ->label('Capital Inicial (P)')
                            ->numeric()
                            ->prefix('$')
                            ->placeholder('Ejemplo: 10000')
                            ->hint('Monto inicial de inversión'),

                        TextInput::make('monto_final')
                            ->rules(['nullable', 'integer', 'min:0'])
                            ->validationMessages([
                                'min' => 'El monto final debe ser mayor o igual a 0',
                            ])
                            ->label('Monto Final (A)')
                            ->numeric()
                            ->prefix('$')
                            ->placeholder('Ejemplo: 15000')
                            ->hint('Valor final esperado'),
                    ]),

                    Grid::make(2)->schema([
                        TextInput::make('tasa_interes')
                            ->rules(['nullable', 'integer', 'min:0'])
                            ->validationMessages([
                                'min' => 'La tasa de interes debe ser mayor o igual a 0',
                            ])
                            ->label('Tasa de Interés Anual (r)')
                            ->numeric()
                            ->suffix('%')
                            ->placeholder('Ejemplo: 5.5')
                            ->step(0.01)
                            ->hint('Tasa anual en porcentaje'),

                        TextInput::make('tiempo')
                            ->rules(['nullable', 'integer', 'min:1'])
                            ->validationMessages([
                                'min' => 'El tiempo debe ser mayor o igual a 1',
                            ])
                            ->label('Tiempo (t)')
                            ->numeric()
                            ->suffix('años')
                            ->placeholder('Ejemplo: 5')
                            ->step(0.1)
                            ->hint('Duración en años'),
                    ]),

                    Grid::make(2)->schema([
                        TextInput::make('frecuencia')
                            ->rules(['nullable', 'integer', 'min:0'])
                            ->validationMessages([
                                'min' => 'La frecuencia debe ser mayor o igual a 0',
                            ])
                            ->label('Frecuencia de Capitalización (n)')
                            ->numeric()
                            ->placeholder('Ejemplo: 12')
                            ->hint('Veces por año')
                            ->helperText('Valores: 12=mensual, 4=trimestral, 1=anual, etc.'),

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
