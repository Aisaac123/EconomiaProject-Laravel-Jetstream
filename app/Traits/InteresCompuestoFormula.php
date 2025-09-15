<?php

namespace App\Traits;

trait InteresCompuestoFormula
{
    use HelpersFormula;

    private function calculateInteresCompuesto(array $data): array
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
        $interesGeneradoProvided = !empty($data['interes_generado']);

        // Si se proporciona interés generado, removerlo de campos vacíos para el conteo
        $emptyFieldsForValidation = $emptyFields;
        if ($interesGeneradoProvided) {
            $emptyFieldsForValidation = array_filter($emptyFields, function($field) {
                return $field !== 'interes_generado';
            });
        }

        // Validar cantidad de campos vacíos
        $maxEmptyFields = $interesGeneradoProvided ? 2 : 1;

        if (count($emptyFieldsForValidation) === 0) {
            return [
                'error' => true,
                'message' => 'Debes dejar exactamente un campo vacío para calcular' .
                    ($interesGeneradoProvided ? ' (o dos si proporcionas el interés generado)' : '') . '.',
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

        $frequency = $data['frecuencia'] ?? 12; // frecuencia de capitalización (n en la fórmula)
        $periodicidadTasa = $data['periodicidad_tasa'] ?? 1; // periodicidad de la tasa dada
        $tipoTasa = $data['tipo_tasa']; // 'nominal' o 'efectiva'

        // Si se proporciona interés generado, usar la lógica específica
        if ($interesGeneradoProvided) {
            return $this->calculateCompuestoWithInteresGenerado($data, $emptyFieldsForValidation, $frequency, $periodicidadTasa, $tipoTasa);
        }

        // Lógica original para un solo campo vacío
        return $this->calculateCompuestoSingleField($data, $emptyFieldsForValidation[0], $frequency, $periodicidadTasa, $tipoTasa);
    }

    private function calculateCompuestoWithInteresGenerado(array $data, array $emptyFields, int $frequency, int $periodicidadTasa, string $tipoTasa): array
    {
        $interesGenerado = $data['interes_generado'];

        // Convertir la tasa según su tipo si está disponible
        $tasaAnual = null;
        if (!empty($data['tasa_interes'])) {
            $tasa = $data['tasa_interes'] / 100;

            if ($tipoTasa === 'nominal') {
                $tasaAnual = $tasa * $periodicidadTasa;
            } else {
                $tasaAnual = pow(1 + $tasa, $periodicidadTasa) - 1;
            }
        }

        $result1 = null;
        $result2 = null;
        $message = '';
        $fieldsToUpdate = [];

        // Casos con interés generado (I = A - P)
        if (count($emptyFields) == 1) {
            // Un solo campo vacío + interés generado proporcionado
            $field = $emptyFields[0];

            switch ($field) {
                case 'capital':
                    // I = A - P, entonces P = A - I
                    // Pero necesitamos A también, así que A = P + I
                    // Tenemos que resolver: A = P(1 + r/n)^(n×t) y I = A - P
                    // Sustituir: I = P(1 + r/n)^(n×t) - P = P[(1 + r/n)^(n×t) - 1]
                    // Entonces: P = I / [(1 + r/n)^(n×t) - 1]

                    if ($tipoTasa === 'nominal') {
                        $factor = pow(1 + $tasaAnual / $frequency, $frequency * $data['tiempo']);
                        $result1 = $interesGenerado / ($factor - 1);
                        $result2 = $result1 + $interesGenerado; // Monto final
                    } else {
                        $tasaPeriodica = pow(1 + $tasaAnual, 1 / $frequency) - 1;
                        $factor = pow(1 + $tasaPeriodica, $frequency * $data['tiempo']);
                        $result1 = $interesGenerado / ($factor - 1);
                        $result2 = $result1 + $interesGenerado; // Monto final
                    }
                    $message = 'Capital inicial requerido: $'.number_format($result1, 2);
                    $fieldsToUpdate = ['capital', 'monto_final'];
                    break;

                case 'monto_final':
                    // I = A - P, entonces A = P + I
                    $result1 = $data['capital'] + $interesGenerado;
                    $message = 'Monto final obtenido: $'.number_format($result1, 2);
                    $fieldsToUpdate = ['monto_final'];
                    break;

                case 'tasa_interes':
                    // I = A - P = P[(1 + r/n)^(n×t) - 1]
                    // Entonces: (1 + r/n)^(n×t) = 1 + I/P
                    // (1 + r/n) = (1 + I/P)^(1/(n×t))
                    // r/n = (1 + I/P)^(1/(n×t)) - 1

                    $periods = $frequency * $data['tiempo'];
                    $tasaPeriodica = pow(1 + $interesGenerado / $data['capital'], 1 / $periods) - 1;

                    if ($tipoTasa === 'nominal') {
                        $tasaNominalAnual = $tasaPeriodica * $frequency;
                        $result1 = ($tasaNominalAnual / $periodicidadTasa) * 100;
                    } else {
                        $tasaEfectivaAnual = pow(1 + $tasaPeriodica, $frequency) - 1;
                        $result1 = (pow(1 + $tasaEfectivaAnual, 1 / $periodicidadTasa) - 1) * 100;
                    }

                    $result2 = $data['capital'] + $interesGenerado; // Monto final
                    $periodicidadTexto = $this->getPeriodicidadTexto($periodicidadTasa);
                    $message = 'Tasa de interés requerida: '.number_format($result1, 6).'% '.$periodicidadTexto;
                    $fieldsToUpdate = ['tasa_interes', 'monto_final'];
                    break;

                case 'tiempo':
                    // I = P[(1 + r/n)^(n×t) - 1]
                    // (1 + r/n)^(n×t) = 1 + I/P
                    // n×t = ln(1 + I/P) / ln(1 + r/n)
                    // t = ln(1 + I/P) / [n × ln(1 + r/n)]

                    if ($tipoTasa === 'nominal') {
                        $tasaPeriodica = $tasaAnual / $frequency;
                    } else {
                        $tasaPeriodica = pow(1 + $tasaAnual, 1 / $frequency) - 1;
                    }

                    $result1 = log(1 + $interesGenerado / $data['capital']) / ($frequency * log(1 + $tasaPeriodica));
                    $result2 = $data['capital'] + $interesGenerado; // Monto final
                    $message = 'Tiempo requerido: '.number_format($result1, 4).' años';
                    $fieldsToUpdate = ['tiempo', 'monto_final'];
                    break;
            }

        } else if (count($emptyFields) == 2) {
            // Dos campos vacíos + interés generado proporcionado
            $fields = $emptyFields;
            sort($fields);

            if (in_array('capital', $fields) && in_array('monto_final', $fields)) {
                // Faltan capital y monto final, tenemos tasa, interés y tiempo
                if ($tipoTasa === 'nominal') {
                    $factor = pow(1 + $tasaAnual / $frequency, $frequency * $data['tiempo']);
                    $result1 = $interesGenerado / ($factor - 1);
                    $result2 = $result1 + $interesGenerado;
                } else {
                    $tasaPeriodica = pow(1 + $tasaAnual, 1 / $frequency) - 1;
                    $factor = pow(1 + $tasaPeriodica, $frequency * $data['tiempo']);
                    $result1 = $interesGenerado / ($factor - 1);
                    $result2 = $result1 + $interesGenerado;
                }
                $message = 'Capital: $'.number_format($result1, 2).' | Monto final: $'.number_format($result2, 2);
                $fieldsToUpdate = ['capital', 'monto_final'];

            } else if (in_array('capital', $fields) && in_array('tasa_interes', $fields)) {
                // Faltan capital y tasa, tenemos monto final, interés y tiempo
                $capital = $data['monto_final'] - $interesGenerado;
                $periods = $frequency * $data['tiempo'];
                $tasaPeriodica = pow($data['monto_final'] / $capital, 1 / $periods) - 1;

                if ($tipoTasa === 'nominal') {
                    $tasaNominalAnual = $tasaPeriodica * $frequency;
                    $result2 = ($tasaNominalAnual / $periodicidadTasa) * 100;
                } else {
                    $tasaEfectivaAnual = pow(1 + $tasaPeriodica, $frequency) - 1;
                    $result2 = (pow(1 + $tasaEfectivaAnual, 1 / $periodicidadTasa) - 1) * 100;
                }

                $result1 = $capital;
                $periodicidadTexto = $this->getPeriodicidadTexto($periodicidadTasa);
                $message = 'Capital: $'.number_format($result1, 2).' | Tasa: '.number_format($result2, 6).'% '.$periodicidadTexto;
                $fieldsToUpdate = ['capital', 'tasa_interes'];

            } else if (in_array('capital', $fields) && in_array('tiempo', $fields)) {
                // Faltan capital y tiempo, tenemos monto final, tasa e interés
                $capital = $data['monto_final'] - $interesGenerado;

                if ($tipoTasa === 'nominal') {
                    $tasaPeriodica = $tasaAnual / $frequency;
                } else {
                    $tasaPeriodica = pow(1 + $tasaAnual, 1 / $frequency) - 1;
                }

                $tiempo = log($data['monto_final'] / $capital) / ($frequency * log(1 + $tasaPeriodica));
                $result1 = $capital;
                $result2 = $tiempo;
                $message = 'Capital: $'.number_format($result1, 2).' | Tiempo: '.number_format($result2, 4).' años';
                $fieldsToUpdate = ['capital', 'tiempo'];

            } else if (in_array('monto_final', $fields) && in_array('tasa_interes', $fields)) {
                // Faltan monto final y tasa, tenemos capital, interés y tiempo
                $montoFinal = $data['capital'] + $interesGenerado;
                $periods = $frequency * $data['tiempo'];
                $tasaPeriodica = pow($montoFinal / $data['capital'], 1 / $periods) - 1;

                if ($tipoTasa === 'nominal') {
                    $tasaNominalAnual = $tasaPeriodica * $frequency;
                    $result2 = ($tasaNominalAnual / $periodicidadTasa) * 100;
                } else {
                    $tasaEfectivaAnual = pow(1 + $tasaPeriodica, $frequency) - 1;
                    $result2 = (pow(1 + $tasaEfectivaAnual, 1 / $periodicidadTasa) - 1) * 100;
                }

                $result1 = $montoFinal;
                $periodicidadTexto = $this->getPeriodicidadTexto($periodicidadTasa);
                $message = 'Monto final: $'.number_format($result1, 2).' | Tasa: '.number_format($result2, 6).'% '.$periodicidadTexto;
                $fieldsToUpdate = ['monto_final', 'tasa_interes'];

            } else if (in_array('monto_final', $fields) && in_array('tiempo', $fields)) {
                // Faltan monto final y tiempo, tenemos capital, tasa e interés
                $montoFinal = $data['capital'] + $interesGenerado;

                if ($tipoTasa === 'nominal') {
                    $tasaPeriodica = $tasaAnual / $frequency;
                } else {
                    $tasaPeriodica = pow(1 + $tasaAnual, 1 / $frequency) - 1;
                }

                $tiempo = log($montoFinal / $data['capital']) / ($frequency * log(1 + $tasaPeriodica));
                $result1 = $montoFinal;
                $result2 = $tiempo;
                $message = 'Monto final: $'.number_format($result1, 2).' | Tiempo: '.number_format($result2, 4).' años';
                $fieldsToUpdate = ['monto_final', 'tiempo'];

            } else if (in_array('tasa_interes', $fields) && in_array('tiempo', $fields)) {
                // Faltan tasa y tiempo, tenemos capital, monto final e interés
                if (abs($data['monto_final'] - ($data['capital'] + $interesGenerado)) > 0.01) {
                    return [
                        'error' => true,
                        'message' => 'Los valores proporcionados son inconsistentes. El monto final debe ser igual al capital más el interés generado.',
                    ];
                }

                // Usar tiempo asumido para resolver el sistema
                $tiempoAsumido = 1;
                $periods = $frequency * $tiempoAsumido;
                $tasaPeriodica = pow($data['monto_final'] / $data['capital'], 1 / $periods) - 1;

                if ($tipoTasa === 'nominal') {
                    $tasaNominalAnual = $tasaPeriodica * $frequency;
                    $result1 = ($tasaNominalAnual / $periodicidadTasa) * 100;
                } else {
                    $tasaEfectivaAnual = pow(1 + $tasaPeriodica, $frequency) - 1;
                    $result1 = (pow(1 + $tasaEfectivaAnual, 1 / $periodicidadTasa) - 1) * 100;
                }

                $result2 = $tiempoAsumido;
                $periodicidadTexto = $this->getPeriodicidadTexto($periodicidadTasa);
                $message = 'Tasa: '.number_format($result1, 6).'% '.$periodicidadTexto.' | Tiempo: '.number_format($result2, 4).' años (solución con tiempo = 1)';
                $fieldsToUpdate = ['tasa_interes', 'tiempo'];
            }
        }

        return [
            'error' => false,
            'data' => array_merge($data, [
                'campo_calculado' => implode(',', $fieldsToUpdate),
                'resultado_calculado' => $result1,
                'resultado_calculado_2' => $result2,
                'interes_generado_calculado' => $interesGenerado,
                'mensaje_calculado' => $message,
            ]),
            'message' => $message . ' | Interés generado: $'.number_format($interesGenerado, 2),
        ];
    }

    private function calculateCompuestoSingleField(array $data, string $field, int $frequency, int $periodicidadTasa, string $tipoTasa): array
    {
        // Convertir la tasa según su tipo
        $tasaAnual = null;
        if (!empty($data['tasa_interes'])) {
            $tasa = $data['tasa_interes'] / 100;

            if ($tipoTasa === 'nominal') {
                $tasaAnual = $tasa * $periodicidadTasa;
            } else {
                $tasaAnual = pow(1 + $tasa, $periodicidadTasa) - 1;
            }
        }

        $result = 0;
        $message = '';

        switch ($field) {
            case 'capital':
                if ($tipoTasa === 'nominal') {
                    $result = $data['monto_final'] / pow(1 + $tasaAnual / $frequency, $frequency * $data['tiempo']);
                } else {
                    $tasaPeriodica = pow(1 + $tasaAnual, 1 / $frequency) - 1;
                    $result = $data['monto_final'] / pow(1 + $tasaPeriodica, $frequency * $data['tiempo']);
                }
                $message = 'Capital inicial requerido: $'.number_format($result, 2);
                break;

            case 'monto_final':
                if ($tipoTasa === 'nominal') {
                    $result = $data['capital'] * pow(1 + $tasaAnual / $frequency, $frequency * $data['tiempo']);
                } else {
                    $tasaPeriodica = pow(1 + $tasaAnual, 1 / $frequency) - 1;
                    $result = $data['capital'] * pow(1 + $tasaPeriodica, $frequency * $data['tiempo']);
                }
                $message = 'Monto final obtenido: $'.number_format($result, 2);
                break;

            case 'tasa_interes':
                $periods = $frequency * $data['tiempo'];

                if ($tipoTasa === 'nominal') {
                    $tasaPeriodica = pow($data['monto_final'] / $data['capital'], 1 / $periods) - 1;
                    $tasaNominalAnual = $tasaPeriodica * $frequency;
                    $result = ($tasaNominalAnual / $periodicidadTasa) * 100;
                } else {
                    $tasaPeriodica = pow($data['monto_final'] / $data['capital'], 1 / $periods) - 1;
                    $tasaEfectivaAnual = pow(1 + $tasaPeriodica, $frequency) - 1;
                    $result = (pow(1 + $tasaEfectivaAnual, 1 / $periodicidadTasa) - 1) * 100;
                }

                $periodicidadTexto = $this->getPeriodicidadTexto($periodicidadTasa);
                $message = 'Tasa de interés requerida: '.number_format($result, 6).'% '.$periodicidadTexto;
                break;

            case 'tiempo':
                if ($tipoTasa === 'nominal') {
                    $result = log($data['monto_final'] / $data['capital']) / ($frequency * log(1 + $tasaAnual / $frequency));
                } else {
                    $tasaPeriodica = pow(1 + $tasaAnual, 1 / $frequency) - 1;
                    $result = log($data['monto_final'] / $data['capital']) / ($frequency * log(1 + $tasaPeriodica));
                }
                $message = 'Tiempo requerido: '.number_format($result, 4).' años';
                break;
        }

        $finalAmount = $data['monto_final'] ?? $result;
        if ($field === 'monto_final') {
            $finalAmount = $result;
        }

        // Calcular interés generado
        $interest = null;
        if (!empty($finalAmount) && !empty($data['capital'])) {
            $interest = $finalAmount - $data['capital'];
        } elseif (empty($finalAmount) && !empty($data['capital'])) {
            $interest = $result - $data['capital'];
        } elseif (!empty($finalAmount) && empty($data['capital'])) {
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
