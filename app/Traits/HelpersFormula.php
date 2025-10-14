<?php

namespace App\Traits;

trait HelpersFormula
{
    private function calculateResponse($finalAmount, array $data, float|int $result, string $message, ?string $calculatedField = null): array
    {
        $interest = null;
        if (! empty($finalAmount) && ! empty($data['capital'])) {
            $interest = $finalAmount - $data['capital'];
        } elseif (empty($finalAmount) && ! empty($data['capital'])) {
            $interest = $result - $data['capital'];
        } elseif (! empty($finalAmount) && empty($data['capital'])) {
            $interest = $finalAmount - $result;
        }

        // Preservar fechas si existen y se usaron para calcular tiempo
        $responseData = $data;
        if (! empty($data['fecha_inicio'])) {
            $responseData['fecha_inicio'] = $data['fecha_inicio'];
        }
        if (! empty($data['fecha_final'])) {
            $responseData['fecha_final'] = $data['fecha_final'];
        }

        $this->result = [
            'error' => false,
            'data' => array_merge($responseData, [
                // Campos ocultos para resultados
                'campo_calculado' => $calculatedField,
                'resultado_calculado' => $result,
                'interes_generado_calculado' => $interest,
                'mensaje_calculado' => $message,
                // Datos legacy (mantener por compatibilidad)
                'resultado' => number_format($result, 2),
                'interes_generado' => $interest !== null ? number_format($interest, 2) : null,
                'monto_final' => $finalAmount,
                'mensaje' => $message,
            ]),
            'message' => $message.($interest !== null ? ' | Interés generado: $'.number_format($interest, 2) : ''),
        ];

        return $this->result;
    }

    private function getPeriodicidadTexto(int $periodicidad): string
    {
        return match ($periodicidad) {
            1 => 'anual',
            2 => 'semestral',
            4 => 'trimestral',
            6 => 'bimestral',
            12 => 'mensual',
            24 => 'quincenal',
            52 => 'semanal',
            360 => 'diario (comercial)',
            365 => 'diario',
            default => "cada $periodicidad períodos"
        };
    }
}
