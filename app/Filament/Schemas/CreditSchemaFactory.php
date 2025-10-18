<?php

namespace App\Filament\Schemas;

use App\Enums\CalculationType;

class CreditSchemaFactory
{
    public static function buildResultHtml(string $type, array $inputs): string
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

        // Casos con tabla y resultado
        if (in_array($type, [
            CalculationType::AMORTIZACION->value,
            CalculationType::TIR->value,
            CalculationType::GRADIENTES->value,
        ])) {
            $resultHtml = method_exists($schemaClass, 'buildResultHtml')
                ? $schemaClass::buildResultHtml($inputs)
                : null;
            if (in_array($type, [
                CalculationType::AMORTIZACION->value,
            ])) {
                $tablaHtml = method_exists($schemaClass, 'buildTablaAmortizacionHtml')
                    ? $schemaClass::buildTablaAmortizacionHtml($inputs)
                    : null;
            }
            if (in_array($type, [
                CalculationType::TIR->value,
            ])) {
                $tablaHtml = method_exists($schemaClass, 'buildTablaFlujosHtml')
                    ? $schemaClass::buildTablaFlujosHtml($inputs)
                    : null;
            }
            if (in_array($type, [
                CalculationType::GRADIENTES->value,
            ])) {
                $tablaHtml = method_exists($schemaClass, 'buildTablaGradienteHtml')
                    ? $schemaClass::buildTablaGradienteHtml($inputs)
                    : null;
            }

            // Si ninguno existe
            if (! $resultHtml && ! $tablaHtml) {
                return '<p class="text-slate-500 dark:text-slate-400 text-center py-4">⚠️ Métodos mixtos no disponibles.</p>';
            }

            return <<<HTML
                <div class="space-y-4">
                    {$resultHtml}
                    {$tablaHtml}
                </div>
            HTML;
        }

        // Casos normales (solo resultado)
        if (method_exists($schemaClass, 'buildResultHtmlFromData')) {
            return $schemaClass::buildResultHtmlFromData($inputs);
        }

        return '<p class="text-slate-500 dark:text-slate-400 text-center py-4">⚠️ Tipo de cálculo no soportado.</p>';
    }
}
