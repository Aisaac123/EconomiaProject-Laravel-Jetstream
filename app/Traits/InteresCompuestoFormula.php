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
        $frequency = $data['frecuencia'] ?? 12;
        $periodicidadTasa = $data['periodicidad_tasa'] ?? 1;

        // Convertir la tasa a la periodicidad correcta para el cálculo
        $rate = null;
        if (! empty($data['tasa_interes'])) {
            // Si la tasa está en periodicidad diferente a anual, convertirla
            $tasaAnual = $data['tasa_interes'];
            if ($periodicidadTasa != 1) {
                // Convertir de la periodicidad dada a anual
                $tasaAnual = $data['tasa_interes'] * $periodicidadTasa;
            }
            $rate = $tasaAnual / 100;
        }

        $result = 0;
        $message = '';

        switch ($field) {
            case 'capital':
                $result = $data['monto_final'] / pow(1 + ($rate / $frequency), $frequency * $data['tiempo']);
                $message = 'Capital inicial requerido: $'.number_format($result, 2);
                break;

            case 'monto_final':
                $result = $data['capital'] * pow(1 + ($rate / $frequency), $frequency * $data['tiempo']);
                $message = 'Monto final obtenido: $'.number_format($result, 2);
                break;

            case 'tasa_interes':
                $rateCalc = $frequency * (pow($data['monto_final'] / $data['capital'], 1 / ($frequency * $data['tiempo'])) - 1);
                // Convertir la tasa calculada según la periodicidad deseada
                $result = ($rateCalc * 100) / $periodicidadTasa; // CORREGIDO: quitar number_format aquí
                $periodicidadTexto = $this->getPeriodicidadTexto($periodicidadTasa);
                $message = 'Tasa de interés requerida: '.number_format($result, 2).'% '.$periodicidadTexto;
                break;

            case 'tiempo':
                $result = log($data['monto_final'] / $data['capital']) / ($frequency * log(1 + ($rate / $frequency)));
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

        // Retornar SOLO los campos ocultos, NO modificar los campos principales
        return [
            'error' => false,
            'data' => array_merge($data, [
                // Campos ocultos para almacenar resultados
                'campo_calculado' => $field,
                'resultado_calculado' => $result,
                'interes_generado_calculado' => $interest,
                'mensaje_calculado' => $message,
            ]),
            'message' => $message.($interest !== null ? ' | Interés generado: $'.number_format($interest, 2) : ''),
        ];
    }
}
