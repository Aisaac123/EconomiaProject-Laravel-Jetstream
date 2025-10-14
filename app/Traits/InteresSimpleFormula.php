<?php

namespace App\Traits;

trait InteresSimpleFormula
{
    use HelpersFormula;

    private function calculateInteresSimple(array $data): array
    {
        // Determinar qué campos están vacíos (incluyendo interes_generado)
        $emptyFields = [];
        $mainFields = ['capital', 'monto_final', 'tasa_interes', 'tiempo'];

        foreach ($mainFields as $field) {
            if (empty($data[$field])) {
                $emptyFields[] = $field;
            }
        }

        // Verificar si se proporcionó interés generado
        $interesGeneradoProvided = ! empty($data['interes_generado']);

        // Si se proporciona interés generado, removerlo de campos vacíos para el conteo
        $emptyFieldsForValidation = $emptyFields;
        if ($interesGeneradoProvided) {
            $emptyFieldsForValidation = array_filter($emptyFields, function ($field) {
                return $field !== 'interes_generado';
            });
        }

        // Validar cantidad de campos vacíos
        $maxEmptyFields = $interesGeneradoProvided ? 2 : 1;

        if (count($emptyFieldsForValidation) === 0) {
            return [
                'error' => true,
                'message' => 'Debes dejar exactamente un campo vacío para calcular'.
                    ($interesGeneradoProvided ? ' (o dos si proporcionas el interés generado)' : '').'.',
            ];
        }

        if (count($emptyFieldsForValidation) > $maxEmptyFields) {
            return [
                'error' => true,
                'message' => $interesGeneradoProvided
                    ? 'Con interés generado puedes dejar máximo 2 campos vacíos. Actualmente hay '.count($emptyFieldsForValidation).' campos vacíos.'
                    : 'Solo un campo puede estar vacío. Actualmente hay '.count($emptyFieldsForValidation).' campos vacíos.',
            ];
        }

        $periodicidadTasa = $data['periodicidad_tasa'] ?? 1;

        // Si se proporciona interés generado, usar la lógica específica
        if ($interesGeneradoProvided) {
            return $this->calculateWithInteresGenerado($data, $emptyFieldsForValidation, $periodicidadTasa);
        }

        // Lógica original para un solo campo vacío
        return $this->calculateSingleField($data, $emptyFieldsForValidation[0], $periodicidadTasa);
    }

    private function calculateWithInteresGenerado(array $data, array $emptyFields, int $periodicidadTasa): array
    {
        $interesGenerado = $data['interes_generado'];

        // Convertir la tasa a anual si está disponible
        $rate = null;
        if (! empty($data['tasa_interes'])) {
            $tasaAnual = $data['tasa_interes'];
            if ($periodicidadTasa != 1) {
                $tasaAnual = $data['tasa_interes'] * $periodicidadTasa;
            }
            $rate = $tasaAnual / 100;
        }

        $result1 = null;
        $result2 = null;
        $message = '';

        // Casos con interés generado (I = P × r × t)
        if (count($emptyFields) == 1) {
            // Un solo campo vacío + interés generado
            $field = $emptyFields[0];

            switch ($field) {
                case 'capital':
                    // I = P × r × t -> P = I / (r × t)
                    $result1 = $interesGenerado / ($rate * $data['tiempo']);
                    $result2 = $result1 + $interesGenerado; // Monto final
                    $message = 'Capital inicial requerido: $'.number_format($result1, 2);
                    break;

                case 'monto_final':
                    // Monto final = Capital + Interés generado
                    $result1 = $data['capital'] + $interesGenerado;
                    $message = 'Monto final obtenido: $'.number_format($result1, 2);
                    break;

                case 'tasa_interes':
                    // I = P × r × t -> r = I / (P × t)
                    $rateCalc = $interesGenerado / ($data['capital'] * $data['tiempo']);
                    $result1 = ($rateCalc * 100) / $periodicidadTasa;
                    $result2 = $data['capital'] + $interesGenerado; // Monto final
                    $periodicidadTexto = $this->getPeriodicidadTexto($periodicidadTasa);
                    $message = 'Tasa de interés requerida: '.number_format($result1, 4).'% '.$periodicidadTexto;
                    break;

                case 'tiempo':
                    // I = P × r × t -> t = I / (P × r)
                    $result1 = $interesGenerado / ($data['capital'] * $rate);
                    $result2 = $data['capital'] + $interesGenerado; // Monto final
                    $message = 'Tiempo requerido: '.smartRound($result1).' años';
                    break;
            }

        } elseif (count($emptyFields) == 2) {
            // Dos campos vacíos + interés generado
            $fields = $emptyFields;
            sort($fields);

            if (in_array('capital', $fields) && in_array('monto_final', $fields)) {
                // Faltan capital y monto final, tenemos tasa e interés y tiempo
                // I = P × r × t -> P = I / (r × t)
                $result1 = $interesGenerado / ($rate * $data['tiempo']);
                $result2 = $result1 + $interesGenerado;
                $message = 'Capital: $'.number_format($result1, 2).' | Monto final: $'.number_format($result2, 2);

            } elseif (in_array('capital', $fields) && in_array('tasa_interes', $fields)) {
                // Faltan capital y tasa, tenemos monto final, interés y tiempo
                $capital = $data['monto_final'] - $interesGenerado;
                $rateCalc = $interesGenerado / ($capital * $data['tiempo']);
                $result1 = $capital;
                $result2 = ($rateCalc * 100) / $periodicidadTasa;
                $periodicidadTexto = $this->getPeriodicidadTexto($periodicidadTasa);
                $message = 'Capital: $'.number_format($result1, 2).' | Tasa: '.number_format($result2, 4).'% '.$periodicidadTexto;

            } elseif (in_array('capital', $fields) && in_array('tiempo', $fields)) {
                // Faltan capital y tiempo, tenemos monto final, tasa e interés
                $capital = $data['monto_final'] - $interesGenerado;
                $tiempo = $interesGenerado / ($capital * $rate);
                $result1 = $capital;
                $result2 = $tiempo;
                $message = 'Capital: $'.number_format($result1, 2).' | Tiempo: '.smartRound($result2).' años';

            } elseif (in_array('monto_final', $fields) && in_array('tasa_interes', $fields)) {
                // Faltan monto final y tasa, tenemos capital, interés y tiempo
                $montoFinal = $data['capital'] + $interesGenerado;
                $rateCalc = $interesGenerado / ($data['capital'] * $data['tiempo']);
                $result1 = $montoFinal;
                $result2 = ($rateCalc * 100) / $periodicidadTasa;
                $periodicidadTexto = $this->getPeriodicidadTexto($periodicidadTasa);
                $message = 'Monto final: $'.number_format($result1, 2).' | Tasa: '.number_format($result2, 4).'% '.$periodicidadTexto;

            } elseif (in_array('monto_final', $fields) && in_array('tiempo', $fields)) {
                // Faltan monto final y tiempo, tenemos capital, tasa e interés
                $montoFinal = $data['capital'] + $interesGenerado;
                $tiempo = $interesGenerado / ($data['capital'] * $rate);
                $result1 = $montoFinal;
                $result2 = $tiempo;
                $message = 'Monto final: $'.number_format($result1, 2).' | Tiempo: '.smartRound($result2).' años';

            } elseif (in_array('tasa_interes', $fields) && in_array('tiempo', $fields)) {
                // Faltan tasa y tiempo, tenemos capital, monto final e interés
                // Verificar consistencia: monto_final = capital + interes_generado
                if (abs($data['monto_final'] - ($data['capital'] + $interesGenerado)) > 0.01) {
                    return [
                        'error' => true,
                        'message' => 'Los valores proporcionados son inconsistentes. El monto final debe ser igual al capital más el interés generado.',
                    ];
                }

                // Usar cualquier combinación válida para calcular
                $rateCalc = $interesGenerado / ($data['capital'] * 1); // Asumimos tiempo = 1 inicialmente
                $tiempo = $interesGenerado / ($data['capital'] * $rateCalc);
                $result1 = ($rateCalc * 100) / $periodicidadTasa;
                $result2 = $tiempo;
                $periodicidadTexto = $this->getPeriodicidadTexto($periodicidadTasa);
                $message = 'Tasa: '.number_format($result1, 4).'% '.$periodicidadTexto.' | Tiempo: '.smartRound($result2).' años';
            }
        }

        return [
            'error' => false,
            'data' => array_merge($data, [
                'campo_calculado' => implode(',', $emptyFields),
                'resultado_calculado' => $result1,
                'resultado_calculado_2' => $result2,
                'interes_generado_calculado' => $interesGenerado,
                'mensaje_calculado' => $message,
            ]),
            'message' => $message.' | Interés generado: $'.number_format($interesGenerado, 2),
        ];
    }

    private function calculateSingleField(array $data, string $field, int $periodicidadTasa): array
    {
        // Convertir la tasa a anual si es necesario
        $rate = null;
        if (! empty($data['tasa_interes'])) {
            $tasaAnual = $data['tasa_interes'];
            if ($periodicidadTasa != 1) {
                $tasaAnual = $data['tasa_interes'] * $periodicidadTasa;
            }
            $rate = $tasaAnual / 100;
        }

        $result = 0;
        $message = '';

        switch ($field) {
            case 'capital':
                $result = $data['monto_final'] / (1 + ($rate * $data['tiempo']));
                $message = 'Capital inicial requerido: $'.number_format($result, 2);
                break;

            case 'monto_final':
                $result = $data['capital'] * (1 + ($rate * $data['tiempo']));
                $message = 'Monto final obtenido: $'.number_format($result, 2);
                break;

            case 'tasa_interes':
                $rateCalc = (($data['monto_final'] / $data['capital']) - 1) / $data['tiempo'];
                $result = ($rateCalc * 100) / $periodicidadTasa;
                $periodicidadTexto = $this->getPeriodicidadTexto($periodicidadTasa);
                $message = 'Tasa de interés requerida: '.number_format($result, 4).'% '.$periodicidadTexto;
                break;

            case 'tiempo':
                $result = (($data['monto_final'] / $data['capital']) - 1) / $rate;
                $message = 'Tiempo requerido: '.smartRound($result).' años';
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
