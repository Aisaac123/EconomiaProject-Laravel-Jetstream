<?php

namespace App\Filament\Schemas;

use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;

class AnualidadSchema
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Calculadora de Anualidades')
                ->description('Complete los campos conocidos. Los campos vacíos serán calculados automáticamente.')
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
                            ->disabled()
                            ->placeholder('Se calculará automáticamente')
                            ->hint('Este campo se completará al enviar'),
                    ]),
                ]),

            // Sección de detalles de resultado para anualidades
            Section::make('📊 Detalles del Cálculo')
                ->description('Resumen completo de los valores calculados')
                ->icon('heroicon-o-chart-bar-square')
                ->collapsed()
                ->collapsible()
                ->schema([
                    Grid::make(1)->schema([
                        Placeholder::make('_')
                            ->label('')
                            ->content(function (callable $get): Htmlable {
                                $pagoPeriodico = $get('pago_periodico');
                                $valorPresente = $get('valor_presente');
                                $valorFuturo = $get('valor_futuro');
                                $tasaInteres = $get('tasa_interes');
                                $numeroPagos = $get('numero_pagos');
                                $resultado = $get('resultado');
                                $mensaje = $get('mensaje');
                                $camposCalculados = $get('campos_calculados') ? json_decode($get('campos_calculados'), true) : [];

                                // Si no hay datos suficientes, mostrar mensaje
                                if (empty($pagoPeriodico) && empty($valorPresente) && empty($valorFuturo) && empty($tasaInteres) && empty($numeroPagos)) {
                                    return new HtmlString('
                                        <div class="text-center py-8 text-gray-500">
                                            <div class="text-4xl mb-2">📈</div>
                                            <p class="text-lg font-medium">Complete los campos para ver los detalles</p>
                                            <p class="text-sm">Los resultados aparecerán aquí después del cálculo</p>
                                        </div>
                                    ');
                                }

                                $html = '<div class="space-y-6">';

                                // Header con título dinámico
                                $html .= '
                                    <div class="bg-gradient-to-r from-primary-50 to-primary-50 dark:from-primary-950 dark:to-primary-950 rounded-lg p-4 border border-primary-200 dark:border-primary-800">
                                        <h3 class="text-lg font-bold text-primary-900 dark:text-primary-100 flex items-center gap-2">
                                            <span class="text-2xl">💰</span>
                                            Resultados de la Anualidad
                                        </h3>
                                    </div>
                                ';

                                // Grid de valores principales
                                $html .= '<div class="grid grid-cols-1 md:grid-cols-2 gap-4">';

                                // Pago Periódico
                                $isCalculated = in_array('pago_periodico', $camposCalculados);
                                $bgClass = $isCalculated ? 'bg-gradient-to-br from-green-50 to-emerald-50 border-green-300 dark:from-green-950 dark:to-emerald-950 dark:border-green-700' : 'bg-gray-50 border-gray-200 dark:bg-gray-800 dark:border-gray-700';
                                $textClass = $isCalculated ? 'text-green-900 dark:text-green-100' : 'text-gray-900 dark:text-gray-100';
                                $badgeClass = $isCalculated ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-primary-100 text-primary-800 dark:bg-primary-900 dark:text-primary-200';

                                $html .= "
                                    <div class='rounded-lg p-4 border {$bgClass}'>
                                        <div class='flex items-center justify-between mb-2'>
                                            <h4 class='font-semibold {$textClass}'>💵 Pago Periódico</h4>
                                            ".($isCalculated ? "<span class='px-2 py-1 text-xs rounded-full {$badgeClass}'>✨ Calculado</span>" : "<span class='px-2 py-1 text-xs rounded-full {$badgeClass}'>📝 Ingresado</span>")."
                                        </div>
                                        <p class='text-2xl font-bold {$textClass}'>".($pagoPeriodico ? '$'.number_format($pagoPeriodico, 2) : 'N/A')."</p>
                                        <p class='text-sm text-gray-600 dark:text-gray-400'>Monto de cada pago (PMT)</p>
                                    </div>
                                ";

                                // Valor Presente
                                $isCalculated = in_array('valor_presente', $camposCalculados);
                                $bgClass = $isCalculated ? 'bg-gradient-to-br from-green-50 to-emerald-50 border-green-300 dark:from-green-950 dark:to-emerald-950 dark:border-green-700' : 'bg-gray-50 border-gray-200 dark:bg-gray-800 dark:border-gray-700';
                                $textClass = $isCalculated ? 'text-green-900 dark:text-green-100' : 'text-gray-900 dark:text-gray-100';
                                $badgeClass = $isCalculated ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-primary-100 text-primary-800 dark:bg-primary-900 dark:text-primary-200';

                                $html .= "
                                    <div class='rounded-lg p-4 border {$bgClass}'>
                                        <div class='flex items-center justify-between mb-2'>
                                            <h4 class='font-semibold {$textClass}'>📉 Valor Presente</h4>
                                            ".($isCalculated ? "<span class='px-2 py-1 text-xs rounded-full {$badgeClass}'>✨ Calculado</span>" : "<span class='px-2 py-1 text-xs rounded-full {$badgeClass}'>📝 Ingresado</span>")."
                                        </div>
                                        <p class='text-2xl font-bold {$textClass}'>".($valorPresente ? '$'.number_format($valorPresente, 2) : 'N/A')."</p>
                                        <p class='text-sm text-gray-600 dark:text-gray-400'>Valor actual (VP)</p>
                                    </div>
                                ";

                                // Valor Futuro
                                $isCalculated = in_array('valor_futuro', $camposCalculados);
                                $bgClass = $isCalculated ? 'bg-gradient-to-br from-green-50 to-emerald-50 border-green-300 dark:from-green-950 dark:to-emerald-950 dark:border-green-700' : 'bg-gray-50 border-gray-200 dark:bg-gray-800 dark:border-gray-700';
                                $textClass = $isCalculated ? 'text-green-900 dark:text-green-100' : 'text-gray-900 dark:text-gray-100';
                                $badgeClass = $isCalculated ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-primary-100 text-primary-800 dark:bg-primary-900 dark:text-primary-200';

                                $html .= "
                                    <div class='rounded-lg p-4 border {$bgClass}'>
                                        <div class='flex items-center justify-between mb-2'>
                                            <h4 class='font-semibold {$textClass}'>📈 Valor Futuro</h4>
                                            ".($isCalculated ? "<span class='px-2 py-1 text-xs rounded-full {$badgeClass}'>✨ Calculado</span>" : "<span class='px-2 py-1 text-xs rounded-full {$badgeClass}'>📝 Ingresado</span>")."
                                        </div>
                                        <p class='text-2xl font-bold {$textClass}'>".($valorFuturo ? '$'.number_format($valorFuturo, 2) : 'N/A')."</p>
                                        <p class='text-sm text-gray-600 dark:text-gray-400'>Valor acumulado (VF)</p>
                                    </div>
                                ";

                                // Tasa de Interés
                                $isCalculated = in_array('tasa_interes', $camposCalculados);
                                $bgClass = $isCalculated ? 'bg-gradient-to-br from-green-50 to-emerald-50 border-green-300 dark:from-green-950 dark:to-emerald-950 dark:border-green-700' : 'bg-gray-50 border-gray-200 dark:bg-gray-800 dark:border-gray-700';
                                $textClass = $isCalculated ? 'text-green-900 dark:text-green-100' : 'text-gray-900 dark:text-gray-100';
                                $badgeClass = $isCalculated ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-primary-100 text-primary-800 dark:bg-primary-900 dark:text-primary-200';

                                $html .= "
                                    <div class='rounded-lg p-4 border {$bgClass}'>
                                        <div class='flex items-center justify-between mb-2'>
                                            <h4 class='font-semibold {$textClass}'>🎯 Tasa de Interés</h4>
                                            ".($isCalculated ? "<span class='px-2 py-1 text-xs rounded-full {$badgeClass}'>✨ Calculado</span>" : "<span class='px-2 py-1 text-xs rounded-full {$badgeClass}'>📝 Ingresado</span>")."
                                        </div>
                                        <p class='text-2xl font-bold {$textClass}'>".($tasaInteres ? $tasaInteres.'%' : 'N/A')."</p>
                                        <p class='text-sm text-gray-600 dark:text-gray-400'>Tasa por período (r)</p>
                                    </div>
                                ";

                                // Número de Pagos
                                $isCalculated = in_array('numero_pagos', $camposCalculados);
                                $bgClass = $isCalculated ? 'bg-gradient-to-br from-green-50 to-emerald-50 border-green-300 dark:from-green-950 dark:to-emerald-950 dark:border-green-700' : 'bg-gray-50 border-gray-200 dark:bg-gray-800 dark:border-gray-700';
                                $textClass = $isCalculated ? 'text-green-900 dark:text-green-100' : 'text-gray-900 dark:text-gray-100';
                                $badgeClass = $isCalculated ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-primary-100 text-primary-800 dark:bg-primary-900 dark:text-primary-200';

                                $html .= "
                                    <div class='rounded-lg p-4 border {$bgClass}'>
                                        <div class='flex items-center justify-between mb-2'>
                                            <h4 class='font-semibold {$textClass}'>🔄 Número de Pagos</h4>
                                            ".($isCalculated ? "<span class='px-2 py-1 text-xs rounded-full {$badgeClass}'>✨ Calculado</span>" : "<span class='px-2 py-1 text-xs rounded-full {$badgeClass}'>📝 Ingresado</span>")."
                                        </div>
                                        <p class='text-2xl font-bold {$textClass}'>".($numeroPagos ? $numeroPagos.' pagos' : 'N/A')."</p>
                                        <p class='text-sm text-gray-600 dark:text-gray-400'>Total de pagos (n)</p>
                                    </div>
                                ";

                                $html .= '</div>'; // Fin del grid principal

                                // Mensaje de resultado si existe
                                if ($resultado) {
                                    $html .= "
                                        <div class='bg-gradient-to-r from-primary-50 to-purple-50 dark:from-primary-950 dark:to-purple-950 rounded-lg p-4 border border-primary-200 dark:border-primary-800'>
                                            <div class='flex items-start gap-3'>
                                                <div class='flex-shrink-0 text-2xl'>🎯</div>
                                                <div>
                                                    <h4 class='font-semibold text-primary-900 dark:text-primary-100 mb-1'>Resultado del Cálculo</h4>
                                                    <p class='text-primary-800 dark:text-primary-200'>{$mensaje}</p>
                                                </div>
                                            </div>
                                        </div>
                                    ";
                                }

                                $html .= '</div>'; // Fin del contenedor principal

                                return new HtmlString($html);
                            }),
                    ]),
                ]),
        ]);
    }
}
