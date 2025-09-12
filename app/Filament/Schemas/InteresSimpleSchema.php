<?php

namespace App\Filament\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;

class InteresSimpleSchema
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            // Campos ocultos donde el trait volcará los resultados (no rompen UI)
            Hidden::make('campo_calculado'),
            Hidden::make('resultado_calculado'),
            Hidden::make('interes_generado_calculado'),
            Hidden::make('mensaje_calculado'),

            // Hero: explicación + calculadora
            Section::make('Calculadora de Interés Simple')
                ->description('Complete los campos conocidos. Deje exactamente 1 campo vacío para calcularlo automáticamente.')
                ->icon('heroicon-o-calculator')
                ->schema([
                    Grid::make(2)->schema([
                        TextInput::make('capital')
                            ->rules(['nullable', 'numeric', 'min:0'])
                            ->label('Capital (P)')
                            ->numeric()
                            ->prefix('$')
                            ->placeholder('Ejemplo: 1000')
                            ->hint('Capital inicial o principal')
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (callable $set) {
                                $set('campo_calculado', null);
                                $set('resultado_calculado', null);
                                $set('interes_generado_calculado', null);
                                $set('mensaje_calculado', null);
                            }),

                        TextInput::make('monto_final')
                            ->rules(['nullable', 'numeric', 'min:0'])
                            ->label('Monto Final (A)')
                            ->numeric()
                            ->prefix('$')
                            ->placeholder('Ejemplo: 1100')
                            ->hint('Monto acumulado (P + I)')
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (callable $set) {
                                $set('campo_calculado', null);
                                $set('resultado_calculado', null);
                                $set('interes_generado_calculado', null);
                                $set('mensaje_calculado', null);
                            }),
                    ]),

                    Grid::make(2)->schema([
                        TextInput::make('tasa_interes')
                            ->rules(['nullable', 'numeric', 'min:0'])
                            ->label('Tasa de Interés (r)')
                            ->numeric()
                            ->suffix('%')
                            ->placeholder('Ejemplo: 5')
                            ->hint('Tasa por período (en %)')
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (callable $set) {
                                $set('campo_calculado', null);
                                $set('resultado_calculado', null);
                                $set('interes_generado_calculado', null);
                                $set('mensaje_calculado', null);
                            }),

                        TextInput::make('tiempo')
                            ->rules(['nullable', 'numeric', 'min:0'])
                            ->label('Tiempo (t)')
                            ->numeric()
                            ->suffix('años')
                            ->placeholder('Ejemplo: 2')
                            ->hint('Duración en años (o fracción)')
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (callable $set) {
                                $set('campo_calculado', null);
                                $set('resultado_calculado', null);
                                $set('interes_generado_calculado', null);
                                $set('mensaje_calculado', null);
                            }),
                    ]),

                    // Campo de resultado visible (se completa al enviar)
                    Grid::make(1)->schema([
                        TextInput::make('resultado')
                            ->label('Interés Calculado (I)')
                            ->disabled()
                            ->prefix('$')
                            ->placeholder('Se calculará automáticamente'),
                    ]),
                ]),

            // Sección de ayuda: periodicidad de la tasa (igual que compuesto, opcional)
            Section::make('Configuración de Tasa (opcional)')
                ->description('Si la tasa no está en base anual puedes indicar la periodicidad.')
                ->icon('heroicon-o-percent-badge')
                ->collapsible()
                ->collapsed()
                ->schema([
                    Grid::make(12)->schema([
                        Toggle::make('usar_select_periodicidad_tasa')
                            ->label('Usar selector de periodicidad')
                            ->default(false)
                            ->live()
                            ->columnSpan(3)
                            ->afterStateUpdated(function (callable $set) {
                                $set('campo_calculado', null);
                                $set('resultado_calculado', null);
                                $set('interes_generado_calculado', null);
                                $set('mensaje_calculado', null);
                            }),

                        TextInput::make('periodicidad_tasa')
                            ->label('Periodicidad (numérica)')
                            ->numeric()
                            ->placeholder('Ej: 12 para mensual')
                            ->default(1)
                            ->columnSpan(9)
                            ->visible(fn (callable $get) => ! $get('usar_select_periodicidad_tasa'))
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (callable $set) {
                                $set('campo_calculado', null);
                                $set('resultado_calculado', null);
                                $set('interes_generado_calculado', null);
                                $set('mensaje_calculado', null);
                            }),

                        Select::make('periodicidad_tasa')
                            ->label('Periodicidad de la tasa')
                            ->options([
                                1 => 'Anual',
                                2 => 'Semestral',
                                4 => 'Trimestral',
                                6 => 'Bimestral',
                                12 => 'Mensual',
                                24 => 'Quincenal',
                                52 => 'Semanal',
                                365 => 'Diaria',
                            ])
                            ->default(1)
                            ->visible(fn (callable $get) => $get('usar_select_periodicidad_tasa'))
                            ->columnSpan(9)
                            ->live()
                            ->afterStateUpdated(function (callable $set) {
                                $set('campo_calculado', null);
                                $set('resultado_calculado', null);
                                $set('interes_generado_calculado', null);
                                $set('mensaje_calculado', null);
                            }),
                    ]),
                ]),

            // Sección de tiempo: permitir calcular tiempo desde fechas (opcional)
            Section::make('Tiempo / Fechas (opcional)')
                ->description('Puedes calcular tiempo a partir de fechas si lo deseas.')
                ->icon('heroicon-o-clock')
                ->collapsible()
                ->collapsed()
                ->schema([
                    Grid::make(12)->schema([
                        Toggle::make('usar_fechas_tiempo')
                            ->label('Usar fechas para calcular tiempo')
                            ->default(false)
                            ->live()
                            ->columnSpan(4)
                            ->afterStateUpdated(function (callable $set) {
                                $set('campo_calculado', null);
                                $set('resultado_calculado', null);
                                $set('interes_generado_calculado', null);
                                $set('mensaje_calculado', null);
                            }),

                        TextInput::make('tiempo')
                            ->label('Tiempo (años)')
                            ->numeric()
                            ->suffix('años')
                            ->placeholder('Ej: 1.5')
                            ->visible(fn (callable $get) => ! $get('usar_fechas_tiempo'))
                            ->columnSpan(8)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (callable $set) {
                                $set('campo_calculado', null);
                                $set('resultado_calculado', null);
                                $set('interes_generado_calculado', null);
                                $set('mensaje_calculado', null);
                            }),
                    ]),

                    Grid::make(2)->schema([
                        DatePicker::make('fecha_inicio')
                            ->label('Fecha de inicio')
                            ->visible(fn (callable $get) => $get('usar_fechas_tiempo'))
                            ->afterStateUpdated(function (callable $set, callable $get) {
                                static::calcularTiempoDesdeFechas($set, $get);
                                $set('campo_calculado', null);
                                $set('resultado_calculado', null);
                                $set('interes_generado_calculado', null);
                                $set('mensaje_calculado', null);
                            }),

                        DatePicker::make('fecha_final')
                            ->label('Fecha final')
                            ->visible(fn (callable $get) => $get('usar_fechas_tiempo'))
                            ->afterStateUpdated(function (callable $set, callable $get) {
                                static::calcularTiempoDesdeFechas($set, $get);
                                $set('campo_calculado', null);
                                $set('resultado_calculado', null);
                                $set('interes_generado_calculado', null);
                                $set('mensaje_calculado', null);
                            }),
                    ]),
                ]),

            // Detalles y resultado (placeholder dinámico)
            Section::make('Detalles del Cálculo')
                ->description('Resumen y explicación del resultado.')
                ->icon('heroicon-o-chart-bar-square')
                ->collapsed()
                ->collapsible()
                ->schema([
                    Grid::make(1)->schema([
                        Placeholder::make('detalles_resultado')
                            ->label('')
                            ->content(function (callable $get): Htmlable {
                                $capital = $get('capital');
                                $montoFinal = $get('monto_final');
                                $tasaInteres = $get('tasa_interes');
                                $tiempo = $get('tiempo');

                                $campoCalculado = $get('campo_calculado');
                                $resultado = $get('resultado_calculado');
                                $interesGenerado = $get('interes_generado_calculado');
                                $mensaje = $get('mensaje_calculado');

                                // Contar campos vacíos
                                $emptyFields = [];
                                foreach (['capital', 'monto_final', 'tasa_interes', 'tiempo'] as $field) {
                                    $value = $get($field);
                                    if ($value === null || $value === '') {
                                        $emptyFields[] = $field;
                                    }
                                }

                                if ($campoCalculado && $resultado !== null) {
                                    return static::buildResultHtml(
                                        $capital, $montoFinal, $tasaInteres, $tiempo,
                                        $interesGenerado, $mensaje, $campoCalculado, $resultado
                                    );
                                }

                                if (empty($capital) && empty($montoFinal) && empty($tasaInteres) && empty($tiempo)) {
                                    return new HtmlString('
                                        <div class="text-center py-12 text-gray-500 dark:text-gray-400">
                                            <div class="text-5xl mb-4">📘</div>
                                            <h3 class="text-xl font-semibold mb-2">Complete los campos para ver los detalles</h3>
                                            <p class="text-sm text-gray-400">Deje exactamente 1 campo vacío y presione Calcular</p>
                                        </div>
                                    ');
                                }

                                if (count($emptyFields) !== 1) {
                                    $errorMessage = count($emptyFields) === 0
                                        ? 'Debes dejar exactamente un campo vacío para calcular.'
                                        : 'Solo un campo puede estar vacío. Actualmente hay '.count($emptyFields).' campos vacíos.';

                                    return new HtmlString('<div class="p-5 bg-red-50 dark:bg-red-900/50 rounded-lg text-red-800">'.$errorMessage.'</div>');
                                }

                                return new HtmlString('
                                    <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                                        <div class="text-4xl mb-2">⏳</div>
                                        <h4 class="font-semibold">Listo para calcular</h4>
                                        <p class="text-sm">Presiona "Calcular" para obtener resultados</p>
                                    </div>
                                ');
                            }),
                    ]),
                ]),
        ]);
    }

    /**
     * Construye HTML simple para mostrar el resultado del interés simple.
     */
    private static function buildResultHtml(
        $capital, $montoFinal, $tasaInteres, $tiempo,
        $interesGenerado, $mensaje, $campoCalculado, $resultado
    ): Htmlable {
        $html = '<div class="space-y-4">';
        $html .= '<div class="rounded-lg p-4 border bg-gray-50 dark:bg-gray-900/50 dark:border-gray-700">';
        $html .= '<h4 class="text-lg font-bold mb-2">Resultado</h4>';
        $html .= '<p class="text-sm text-gray-600 dark:text-gray-400 mb-3">'.$mensaje.'</p>';

        $html .= '<div class="grid grid-cols-1 md:grid-cols-2 gap-4">';

        // Mostrar valores principales
        $html .= static::valueCard('Capital (P)', $capital, $campoCalculado === 'capital');
        $html .= static::valueCard('Monto Final (A)', $montoFinal, $campoCalculado === 'monto_final');
        $html .= static::valueCard('Tasa (r)', is_numeric($tasaInteres) ? $tasaInteres.'%' : '--', $campoCalculado === 'tasa_interes');
        $html .= static::valueCard('Tiempo (t)', is_numeric($tiempo) ? $tiempo.' años' : '--', $campoCalculado === 'tiempo');

        $html .= '</div>'; // grid

        if ($interesGenerado) {
            $html .= '<div class="mt-4 p-4 rounded-lg bg-amber-50 dark:bg-amber-900/50 border">';
            $html .= '<strong>Interés generado:</strong> $'.number_format($interesGenerado, 2);
            $html .= '</div>';
        }

        $html .= '</div></div>';

        return new HtmlString($html);
    }

    private static function valueCard($title, $value, $isCalculated = false): string
    {
        $badge = $isCalculated ? '✨ Calculado' : '📝 Ingresado';
        $display = is_numeric($value) ? number_format((float) $value, 2) : ($value ?? '--');

        // if value includes '%' we keep it as-is
        if (is_string($value) && str_ends_with($value, '%')) {
            $display = $value;
        }

        return "
            <div class='rounded-lg p-4 border bg-white/20 dark:bg-gray-900/60'>
                <div class='flex items-center justify-between mb-2'>
                    <div class='text-sm font-semibold'>{$title}</div>
                    <div class='text-xs px-2 py-1 rounded-full bg-gray-100 dark:bg-gray-800'>{$badge}</div>
                </div>
                <div class='text-2xl font-bold'>{$display}</div>
            </div>
        ";
    }

    /**
     * Calcular tiempo (años) desde fechas y setear el campo 'tiempo'
     */
    private static function calcularTiempoDesdeFechas(callable $set, callable $get): void
    {
        $fechaInicio = $get('fecha_inicio');
        $fechaFinal = $get('fecha_final');

        if ($fechaInicio && $fechaFinal) {
            $inicio = \Carbon\Carbon::parse($fechaInicio);
            $final = \Carbon\Carbon::parse($fechaFinal);

            if ($final->gt($inicio)) {
                $tiempoEnAnios = $inicio->diffInDays($final) / 365.25;
                $set('tiempo', round($tiempoEnAnios, 4));
            } else {
                $set('tiempo', null);
            }
        }
    }

    private static function smartRound(float $value): float
    {
        if (abs($value - round($value)) < 0.01) {
            return round($value);
        }
        $oneDecimal = round($value, 1);
        if (abs($value - $oneDecimal) < 0.05) {
            return $oneDecimal;
        }

        return round($value, 2);
    }
}
