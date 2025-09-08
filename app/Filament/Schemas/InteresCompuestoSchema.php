<?php

namespace App\Filament\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Placeholder;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;

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
                            ->rules(['nullable', 'numeric', 'min:0'])
                            ->validationMessages([
                                'min' => 'El capital inicial debe ser mayor o igual a 0',
                            ])
                            ->label('Capital Inicial (P)')
                            ->numeric()
                            ->prefix('$')
                            ->placeholder('Ejemplo: 10000')
                            ->hint('Monto inicial de inversión'),

                        TextInput::make('monto_final')
                            ->rules(['nullable', 'numeric', 'min:0'])
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
                            ->rules(['nullable', 'numeric', 'min:0'])
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
                            ->rules(['nullable', 'numeric', 'min:1'])
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
                            ->disabled()
                            ->placeholder('Se calculará automáticamente')
                            ->hint('Este campo se completará al enviar'),
                    ]),
                ]),

            // Nueva sección de detalles de resultado
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
                                $capital = $get('capital');
                                $montoFinal = $get('monto_final');
                                $tasaInteres = $get('tasa_interes');
                                $tiempo = $get('tiempo');
                                $frecuencia = $get('frecuencia') ?: 12;
                                $resultado = $get('resultado');
                                $interesGenerado = $get('interes_generado');
                                $mensaje = $get('mensaje');

                                // Determinar qué campo fue calculado
                                $emptyFields = [];
                                foreach (['capital', 'monto_final', 'tasa_interes', 'tiempo'] as $field) {
                                    if (empty($get($field))) {
                                        $emptyFields[] = $field;
                                    }
                                }
                                $calculatedField = count($emptyFields) === 1 ? $emptyFields[0] : null;

                                // Si no hay datos suficientes, mostrar mensaje
                                if (empty($capital) && empty($montoFinal) && empty($tasaInteres) && empty($tiempo)) {
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
                                            Resultados
                                        </h3>
                                    </div>
                                ';

                                // Grid de valores principales
                                $html .= '<div class="grid grid-cols-1 md:grid-cols-2 gap-4">';

                                // Capital Inicial
                                $isCalculated = $calculatedField === 'capital';
                                $bgClass = $isCalculated ? 'bg-gradient-to-br from-green-50 to-emerald-50 border-green-300 dark:from-green-950 dark:to-emerald-950 dark:border-green-700' : 'bg-gray-50 border-gray-200 dark:bg-gray-800 dark:border-gray-700';
                                $textClass = $isCalculated ? 'text-green-900 dark:text-green-100' : 'text-gray-900 dark:text-gray-100';
                                $badgeClass = $isCalculated ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-primary-100 text-primary-800 dark:bg-primary-900 dark:text-primary-200';

                                $html .= "
                                    <div class='rounded-lg p-4 border {$bgClass}'>
                                        <div class='flex items-center justify-between mb-2'>
                                            <h4 class='font-semibold {$textClass}'>💵 Capital Inicial</h4>
                                            " . ($isCalculated ? "<span class='px-2 py-1 text-xs rounded-full {$badgeClass}'>✨ Calculado</span>" : "<span class='px-2 py-1 text-xs rounded-full {$badgeClass}'>📝 Ingresado</span>") . "
                                        </div>
                                        <p class='text-2xl font-bold {$textClass}'>" . ($capital ? '$' . number_format($capital, 2) : $resultado) . "</p>
                                        <p class='text-sm text-gray-600 dark:text-gray-400'>Inversión inicial</p>
                                    </div>
                                ";

                                // Monto Final
                                $isCalculated = $calculatedField === 'monto_final';
                                $bgClass = $isCalculated ? 'bg-gradient-to-br from-green-50 to-emerald-50 border-green-300 dark:from-green-950 dark:to-emerald-950 dark:border-green-700' : 'bg-gray-50 border-gray-200 dark:bg-gray-800 dark:border-gray-700';
                                $textClass = $isCalculated ? 'text-green-900 dark:text-green-100' : 'text-gray-900 dark:text-gray-100';
                                $badgeClass = $isCalculated ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-primary-100 text-primary-800 dark:bg-primary-900 dark:text-primary-200';

                                $html .= "
                                    <div class='rounded-lg p-4 border {$bgClass}'>
                                        <div class='flex items-center justify-between mb-2'>
                                            <h4 class='font-semibold {$textClass}'>🎯 Monto Final</h4>
                                            " . ($isCalculated ? "<span class='px-2 py-1 text-xs rounded-full {$badgeClass}'>✨ Calculado</span>" : "<span class='px-2 py-1 text-xs rounded-full {$badgeClass}'>📝 Ingresado</span>") . "
                                        </div>
                                        <p class='text-2xl font-bold {$textClass}'>" . ($montoFinal ? '$' . number_format($montoFinal, 2) : $resultado) . "</p>
                                        <p class='text-sm text-gray-600 dark:text-gray-400'>Valor al vencimiento</p>
                                    </div>
                                ";

                                // Tasa de Interés
                                $isCalculated = $calculatedField === 'tasa_interes';
                                $bgClass = $isCalculated ? 'bg-gradient-to-br from-green-50 to-emerald-50 border-green-300 dark:from-green-950 dark:to-emerald-950 dark:border-green-700' : 'bg-gray-50 border-gray-200 dark:bg-gray-800 dark:border-gray-700';
                                $textClass = $isCalculated ? 'text-green-900 dark:text-green-100' : 'text-gray-900 dark:text-gray-100';
                                $badgeClass = $isCalculated ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-primary-100 text-primary-800 dark:bg-primary-900 dark:text-primary-200';

                                $html .= "
                                    <div class='rounded-lg p-4 border {$bgClass}'>
                                        <div class='flex items-center justify-between mb-2'>
                                            <h4 class='font-semibold {$textClass}'>📈 Tasa de Interés</h4>
                                            " . ($isCalculated ? "<span class='px-2 py-1 text-xs rounded-full {$badgeClass}'>✨ Calculado</span>" : "<span class='px-2 py-1 text-xs rounded-full {$badgeClass}'>📝 Ingresado</span>") . "
                                        </div>
                                        <p class='text-2xl font-bold {$textClass}'>" . ($tasaInteres ? $tasaInteres . '%' : $resultado) . "</p>
                                        <p class='text-sm text-gray-600 dark:text-gray-400'>Tasa anual</p>
                                    </div>
                                ";

                                // Tiempo
                                $isCalculated = $calculatedField === 'tiempo';
                                $bgClass = $isCalculated ? 'bg-gradient-to-br from-green-50 to-emerald-50 border-green-300 dark:from-green-950 dark:to-emerald-950 dark:border-green-700' : 'bg-gray-50 border-gray-200 dark:bg-gray-800 dark:border-gray-700';
                                $textClass = $isCalculated ? 'text-green-900 dark:text-green-100' : 'text-gray-900 dark:text-gray-100';
                                $badgeClass = $isCalculated ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-primary-100 text-primary-800 dark:bg-primary-900 dark:text-primary-200';

                                $html .= "
                                    <div class='rounded-lg p-4 border {$bgClass}'>
                                        <div class='flex items-center justify-between mb-2'>
                                            <h4 class='font-semibold {$textClass}'>⏰ Tiempo</h4>
                                            " . ($isCalculated ? "<span class='px-2 py-1 text-xs rounded-full {$badgeClass}'>✨ Calculado</span>" : "<span class='px-2 py-1 text-xs rounded-full {$badgeClass}'>📝 Ingresado</span>") . "
                                        </div>
                                        <p class='text-2xl font-bold {$textClass}'>" . ($tiempo ? $tiempo . ' años' : $resultado . ' años') . "</p>
                                        <p class='text-sm text-gray-600 dark:text-gray-400'>Período de inversión</p>
                                    </div>
                                ";

                                $html .= '</div>'; // Fin del grid principal

                                // Información adicional
                                if ($frecuencia || $interesGenerado) {
                                    $html .= '<div class="grid grid-cols-1 md:grid-cols-2 gap-4">';

                                    // Frecuencia de capitalización
                                    if ($frecuencia) {
                                        $frecuenciaTexto = match((int)$frecuencia) {
                                            1 => 'Anual',
                                            2 => 'Semestral',
                                            4 => 'Trimestral',
                                            12 => 'Mensual',
                                            24 => 'Quincenal',
                                            52 => 'Semanal',
                                            365 => 'Diaria',
                                            360 => 'Diaria (Aproximada)',
                                            default => $frecuencia . ' veces/año'
                                        };

                                        $html .= "
                                            <div class='rounded-lg p-4 border bg-purple-50 border-purple-200 dark:bg-purple-950 dark:border-purple-700'>
                                                <div class='flex items-center gap-2 mb-2'>
                                                    <span class='text-purple-600 dark:text-purple-400'>🔄</span>
                                                    <h4 class='font-semibold text-purple-900 dark:text-purple-100'>Capitalización</h4>
                                                </div>
                                                <p class='text-xl font-bold text-purple-900 dark:text-purple-100'>{$frecuenciaTexto}</p>
                                                <p class='text-sm text-purple-600 dark:text-purple-400'>{$frecuencia} veces por año</p>
                                            </div>
                                        ";
                                    }

                                    // Interés generado
                                    if ($interesGenerado) {
                                        $html .= "
                                            <div class='rounded-lg p-4 border bg-amber-50 border-amber-200 dark:bg-amber-950 dark:border-amber-700'>
                                                <div class='flex items-center gap-2 mb-2'>
                                                    <span class='text-amber-600 dark:text-amber-400'>💎</span>
                                                    <h4 class='font-semibold text-amber-900 dark:text-amber-100'>Interés Generado</h4>
                                                </div>
                                                <p class='text-xl font-bold text-amber-900 dark:text-amber-100'>$" . $interesGenerado . "</p>
                                                <p class='text-sm text-amber-600 dark:text-amber-400'>Ganancia total</p>
                                            </div>
                                        ";
                                    }

                                    $html .= '</div>';
                                }

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
                            })
                    ]),
                ])
        ]);
    }
}
