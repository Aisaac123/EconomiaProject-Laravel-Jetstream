<?php

namespace App\Traits;

trait InteresCompuestoFormula
{
    use HelpersFormula;

    private function calculateInteresCompuesto(array $data): array
    {
        $emptyFields = [];
        foreach (['capital', 'monto_final', 'tasa_interes', 'tiempo'] as $field) {
            if (empty($data[$field])) {
                $emptyFields[] = $field;
            }
        }

        if (count($emptyFields) !== 1) {
            return [
                'error' => true,
                'message' => count($emptyFields) === 0
                    ? 'Debes dejar exactamente un campo vacío para calcular.'
                    : 'Solo un campo puede estar vacío. Actualmente hay '.count($emptyFields).' campos vacíos.',
            ];
        }

        $field = $emptyFields[0];
        $frequency = $data['frecuencia'] ?? 12; // frecuencia de capitalización (n en la fórmula)
        $periodicidadTasa = $data['periodicidad_tasa'] ?? 1; // periodicidad de la tasa dada
        $tipoTasa = $data['tipo_tasa']; // 'nominal' o 'efectiva'

        // Convertir la tasa según su tipo
        $tasaAnual = null;
        if (! empty($data['tasa_interes'])) {
            $tasa = $data['tasa_interes'] / 100;

            if ($tipoTasa === 'nominal') {
                // Tasa nominal: convertir a tasa nominal anual
                // Si es trimestral (4%), la anual será 4% * 4 = 16%
                $tasaAnual = $tasa * $periodicidadTasa;
            } else {
                // Tasa efectiva: convertir a tasa efectiva anual
                // Si es trimestral efectiva (4%), usar: (1 + 0.04)^4 - 1
                $tasaAnual = pow(1 + $tasa, $periodicidadTasa) - 1;
            }
        }

        $result = 0;
        $message = '';

        switch ($field) {
            case 'capital':
                if ($tipoTasa === 'nominal') {
                    // Fórmula: P = A / (1 + r/n)^(n*t)
                    $result = $data['monto_final'] / pow(1 + $tasaAnual / $frequency, $frequency * $data['tiempo']);
                } else {
                    // Para efectiva: convertir a tasa periódica equivalente
                    $tasaPeriodica = pow(1 + $tasaAnual, 1 / $frequency) - 1;
                    $result = $data['monto_final'] / pow(1 + $tasaPeriodica, $frequency * $data['tiempo']);
                }
                $message = 'Capital inicial requerido: $'.number_format($result, 2);
                break;

            case 'monto_final':
                if ($tipoTasa === 'nominal') {
                    // Fórmula: A = P * (1 + r/n)^(n*t)
                    $result = $data['capital'] * pow(1 + $tasaAnual / $frequency, $frequency * $data['tiempo']);
                } else {
                    // Para efectiva: convertir a tasa periódica equivalente
                    $tasaPeriodica = pow(1 + $tasaAnual, 1 / $frequency) - 1;
                    $result = $data['capital'] * pow(1 + $tasaPeriodica, $frequency * $data['tiempo']);
                }
                $message = 'Monto final obtenido: $'.number_format($result, 2);
                break;

            case 'tasa_interes':
                $periods = $frequency * $data['tiempo'];

                if ($tipoTasa === 'nominal') {
                    // Calcular tasa nominal
                    // Primero obtener la tasa periódica: i = (A/P)^(1/n*t) - 1
                    $tasaPeriodica = pow($data['monto_final'] / $data['capital'], 1 / $periods) - 1;
                    // Convertir a tasa nominal anual: r = i * n
                    $tasaNominalAnual = $tasaPeriodica * $frequency;
                    // Convertir a la periodicidad solicitada
                    $result = ($tasaNominalAnual / $periodicidadTasa) * 100;
                } else {
                    // Calcular tasa efectiva
                    // Primero obtener la tasa periódica
                    $tasaPeriodica = pow($data['monto_final'] / $data['capital'], 1 / $periods) - 1;
                    // Convertir a tasa efectiva anual: (1 + i)^n - 1
                    $tasaEfectivaAnual = pow(1 + $tasaPeriodica, $frequency) - 1;
                    // Convertir a la periodicidad solicitada
                    $result = (pow(1 + $tasaEfectivaAnual, 1 / $periodicidadTasa) - 1) * 100;
                }

                $periodicidadTexto = $this->getPeriodicidadTexto($periodicidadTasa);
                $message = 'Tasa de interés requerida: '.number_format($result, 6).'% '.$periodicidadTexto;
                break;

            case 'tiempo':
                if ($tipoTasa === 'nominal') {
                    // Fórmula: t = ln(A/P) / (n * ln(1 + r/n))
                    $result = log($data['monto_final'] / $data['capital']) / ($frequency * log(1 + $tasaAnual / $frequency));
                } else {
                    // Para efectiva: usar tasa periódica equivalente
                    $tasaPeriodica = pow(1 + $tasaAnual, 1 / $frequency) - 1;
                    $result = log($data['monto_final'] / $data['capital']) / ($frequency * log(1 + $tasaPeriodica));
                }
                $message = 'Tiempo requerido: '.number_format($result, 2).' años';
                break;
        }

        $finalAmount = $data['monto_final'] ?? $result;
        if ($field === 'monto_final') {
            $finalAmount = $result;
        }

        // Calcular interés generado
        $interest = null;
        if (! empty($finalAmount) && ! empty($data['capital'])) {
            $interest = $finalAmount - $data['capital'];
        } elseif (empty($finalAmount) && ! empty($data['capital'])) {
            $interest = $result - $data['capital'];
        } elseif (! empty($finalAmount) && empty($data['capital'])) {
            $interest = $finalAmount - $result;
        }

        return [
            'error' => false,
            'data' => array_merge($data, [
                'campo_calculado' => $field,
                'resultado_calculado' => $result,
                'interes_generado_calculado' => $interest,
                'mensaje_calculado' => $message,
            ]),
            'message' => $message.($interest !== null ? ' | Interés generado: $'.number_format($interest, 2) : ''),
        ];
    }
}
