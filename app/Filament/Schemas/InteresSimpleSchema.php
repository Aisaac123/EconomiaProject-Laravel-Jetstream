<?php

namespace App\Filament\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class InteresSimpleSchema
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Calculadora de Interés Simple')
                ->description('Complete los campos conocidos. El campo vacío será calculado automáticamente.')
                ->icon('heroicon-o-banknotes')
                ->schema([
                    Grid::make(2)->schema([
                        TextInput::make('capital')
                            ->label('Capital (C)')
                            ->numeric()
                            ->prefix('$')
                            ->placeholder('Ejemplo: 1000'),

                        TextInput::make('tasa')
                            ->label('Tasa de Interés (i)')
                            ->numeric()
                            ->suffix('%')
                            ->placeholder('Ejemplo: 5'),
                    ]),

                    Grid::make(2)->schema([
                        TextInput::make('tiempo')
                            ->label('Tiempo (t)')
                            ->numeric()
                            ->suffix('años')
                            ->placeholder('Ejemplo: 2'),

                        TextInput::make('resultado')
                            ->label('Interés Calculado (I)')
                            ->prefix('$')
                            ->disabled()
                            ->placeholder('Se calculará automáticamente'),
                    ]),
                ]),
        ]);
    }
}
