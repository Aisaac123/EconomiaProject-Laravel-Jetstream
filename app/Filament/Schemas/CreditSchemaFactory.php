<?php

namespace App\Filament\Schemas;

use App\Enums\CalculationType;
use App\Models\Credit;
use Blade;
use Illuminate\Support\HtmlString;

class CreditSchemaFactory
{
    public static function buildResultHtml(string $type, array $inputs, ?Credit $credit = null): string
    {
        $map = [
            CalculationType::SIMPLE->value => InteresSimpleSchema::class,
            CalculationType::COMPUESTO->value => InteresCompuestoSchema::class,
            CalculationType::ANUALIDAD->value => AnualidadSchema::class,
            CalculationType::AMORTIZACION->value => AmortizacionSchema::class,
            CalculationType::TIR->value => TasaInternaRetornoSchema::class,
            CalculationType::GRADIENTES->value => GradientesSchema::class,
        ];

        $schemaClass = $map[$type] ?? null;

        if (! $schemaClass) {
            return '<p class="text-slate-500 dark:text-slate-400 text-center py-4">⚠️ Tipo de cálculo no soportado.</p>';
        }

        // Generar HTML de pagos solo si el crédito existe y el schema tiene el método
        $pagosHtml = null;
        if ($credit && method_exists($schemaClass, 'buildPagosHtml')) {
            try {
                $pagosData = $credit->getPagosData();
                if (! empty($pagosData)) {
                    $pagosHtml = $schemaClass::buildPagosHtml($pagosData);
                }
            } catch (\Exception $e) {
                // Si hay error, simplemente no mostrar pagos
                throw $e;
            }
        }

        // Casos con tabla y resultado
        if (in_array($type, [
            CalculationType::AMORTIZACION->value,
            CalculationType::TIR->value,
            CalculationType::GRADIENTES->value,
        ])) {
            $resultHtml = method_exists($schemaClass, 'buildResultHtml')
                ? $schemaClass::buildResultHtml($inputs)
                : null;

            $tablaHtml = null;
            if (in_array($type, [CalculationType::AMORTIZACION->value])) {
                $tablaHtml = method_exists($schemaClass, 'buildTablaAmortizacionHtml')
                    ? $schemaClass::buildTablaAmortizacionHtml($inputs)
                    : null;
            }
            if (in_array($type, [CalculationType::TIR->value])) {
                $tablaHtml = method_exists($schemaClass, 'buildTablaFlujosHtml')
                    ? $schemaClass::buildTablaFlujosHtml($inputs)
                    : null;
            }
            if (in_array($type, [CalculationType::GRADIENTES->value])) {
                $tablaHtml = method_exists($schemaClass, 'buildTablaGradienteHtml')
                    ? $schemaClass::buildTablaGradienteHtml($inputs)
                    : null;
            }

            // Si ninguno existe
            if (! $resultHtml && ! $tablaHtml && ! $pagosHtml) {
                return '<p class="text-slate-500 dark:text-slate-400 text-center py-4">⚠️ Método no disponible.</p>';
            }

            return new HtmlString(
                Blade::render(<<<'BLADE'
                    <div class="space-y-4">
                        <x-sections.content title="Cálculo Inicial"  class="space-y-4">
                            {!! $resultHtml !!}
                            {!! $tablaHtml !!}
                        </x-sections.content>

                        <x-sections.content title="Resultado de Pagos" class="space-y-4">
                            {!! $pagosHtml !!}
                        </x-sections.content>
                    </div>
                BLADE, [
                    'resultHtml' => $resultHtml,
                    'pagosHtml' => $pagosHtml,
                    'tablaHtml' => $tablaHtml,
                ])
            );

        }

        // Casos normales (solo resultado + pagos)
        if (method_exists($schemaClass, 'buildResultHtmlFromData')) {
            $resultHtml = $schemaClass::buildResultHtmlFromData($inputs);

            // Incluir pagos HTML si existe
            if ($pagosHtml) {
                return new HtmlString(
                    Blade::render(<<<'BLADE'
                    <div class="space-y-4">
                    <x-sections.content title="Cálculo Inicial" class="space-y-4">
                        {!! $resultHtml !!}
                    </x-sections.content>

                    <x-sections.content collapsed="true" title="Resultado de Pagos" class="space-y-4">
                        {!! $pagosHtml !!}
                    </x-sections.content>
                    </div>
                BLADE, [
                        'resultHtml' => $resultHtml,
                        'pagosHtml' => $pagosHtml,
                    ])
                );
            }

            return $resultHtml;
        }

        return '<p class="text-slate-500 dark:text-slate-400 text-center py-4">⚠️ Tipo de cálculo no soportado.</p>';
    }
}
