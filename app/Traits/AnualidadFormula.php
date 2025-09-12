<?php

namespace App\Traits;

trait AnualidadFormula
{
    use HelpersFormula;

    private function calculateAnualidad(array $data): array
    {
        $emptyFields = [];
        $camposCalculados = [];
        $resultados = [];
        $messages = [];

        foreach (['pago_periodico', 'valor_presente', 'valor_futuro', 'tasa_interes', 'numero_pagos'] as $field) {
            if (empty($data[$field])) {
                $emptyFields[] = $field;
            }
        }

        // Validación de campos
        if (count($emptyFields) === 0 || count($emptyFields) > 2) {
            return [
                'error' => true,
                'message' => count($emptyFields) === 0
                    ? 'Debes dejar 1 o 2 campos vacíos para calcular.'
                    : 'Solo puedes dejar máximo 2 campos vacíos. Actualmente hay '.count($emptyFields).' vacíos.',
            ];
        }

        $periodicidadTasa = $data['periodicidad_tasa'] ?? 12;

        // Inicializar valores de trabajo (mayor precisión)
        $values = [
            'pago_periodico' => ! empty($data['pago_periodico']) ? (float) $data['pago_periodico'] : null,
            'valor_presente' => ! empty($data['valor_presente']) ? (float) $data['valor_presente'] : null,
            'valor_futuro' => ! empty($data['valor_futuro']) ? (float) $data['valor_futuro'] : null,
            'tasa_interes' => ! empty($data['tasa_interes']) ? (float) $data['tasa_interes'] : null,
            'numero_pagos' => ! empty($data['numero_pagos']) ? (int) $data['numero_pagos'] : null,
        ];

        // Convertir tasa a decimal anual si existe
        $rate = null;
        if ($values['tasa_interes'] !== null) {
            $tasaAnual = $values['tasa_interes'];
            if ($periodicidadTasa != 1) {
                // Convertir de la periodicidad dada a anual
                $tasaAnual = $values['tasa_interes'] * $periodicidadTasa;
            }
            $rate = $tasaAnual / 100;
        }

        try {
            // Algoritmo iterativo: seguir calculando hasta que no se puedan calcular más campos
            $maxIterations = 10;
            $iteration = 0;
            $calculated = true;

            while ($calculated && $iteration < $maxIterations) {
                $calculated = false;
                $iteration++;

                // 1. Calcular Valor Presente si falta
                if ($values['valor_presente'] === null &&
                    $values['pago_periodico'] !== null &&
                    $rate !== null &&
                    $values['numero_pagos'] !== null) {

                    // VP = PMT × [(1 - (1 + r)^-n) / r]
                    if ($rate == 0) {
                        $values['valor_presente'] = $values['pago_periodico'] * $values['numero_pagos'];
                    } else {
                        $values['valor_presente'] = $values['pago_periodico'] * ((1 - pow(1 + $rate, -$values['numero_pagos'])) / $rate);
                    }

                    $resultados['valor_presente'] = round($values['valor_presente'], 2);
                    $camposCalculados[] = 'valor_presente';
                    $messages[] = 'Valor Presente calculado: '.number_format($resultados['valor_presente'], 2);
                    $calculated = true;
                }

                // 2. Calcular Valor Futuro si falta
                if ($values['valor_futuro'] === null &&
                    $values['pago_periodico'] !== null &&
                    $rate !== null &&
                    $values['numero_pagos'] !== null) {

                    // VF = PMT × [((1 + r)^n - 1) / r]
                    if ($rate == 0) {
                        $values['valor_futuro'] = $values['pago_periodico'] * $values['numero_pagos'];
                    } else {
                        $values['valor_futuro'] = $values['pago_periodico'] * ((pow(1 + $rate, $values['numero_pagos']) - 1) / $rate);
                    }

                    $resultados['valor_futuro'] = round($values['valor_futuro'], 2);
                    $camposCalculados[] = 'valor_futuro';
                    $messages[] = 'Valor Futuro calculado: '.number_format($resultados['valor_futuro'], 2);
                    $calculated = true;
                }

                // 3. Calcular Pago Periódico desde VP
                if ($values['pago_periodico'] === null &&
                    $values['valor_presente'] !== null &&
                    $rate !== null &&
                    $values['numero_pagos'] !== null) {

                    // PMT = VP × [r / (1 - (1+r)^-n)]
                    if ($rate == 0) {
                        $values['pago_periodico'] = $values['valor_presente'] / $values['numero_pagos'];
                    } else {
                        $values['pago_periodico'] = $values['valor_presente'] * ($rate / (1 - pow(1 + $rate, -$values['numero_pagos'])));
                    }

                    $resultados['pago_periodico'] = round($values['pago_periodico'], 2);
                    $camposCalculados[] = 'pago_periodico';
                    $messages[] = 'Pago Periódico calculado (desde VP): '.number_format($resultados['pago_periodico'], 2);
                    $calculated = true;
                }

                // 4. Calcular Pago Periódico desde VF (solo si no se calculó desde VP)
                if ($values['pago_periodico'] === null &&
                    $values['valor_futuro'] !== null &&
                    $rate !== null &&
                    $values['numero_pagos'] !== null) {

                    // PMT = VF × [r / ((1+r)^n - 1)]
                    if ($rate == 0) {
                        $values['pago_periodico'] = $values['valor_futuro'] / $values['numero_pagos'];
                    } else {
                        $values['pago_periodico'] = $values['valor_futuro'] * ($rate / (pow(1 + $rate, $values['numero_pagos']) - 1));
                    }

                    $resultados['pago_periodico'] = round($values['pago_periodico'], 2);
                    $camposCalculados[] = 'pago_periodico';
                    $messages[] = 'Pago Periódico calculado (desde VF): '.number_format($resultados['pago_periodico'], 2);
                    $calculated = true;
                }

                // 5. Calcular Número de pagos desde VF
                if ($values['numero_pagos'] === null &&
                    $values['valor_futuro'] !== null &&
                    $values['pago_periodico'] !== null &&
                    $rate !== null && $rate > 0) {

                    // n = log(VF*r/PMT + 1) / log(1+r)
                    $ratio = ($values['valor_futuro'] * $rate) / $values['pago_periodico'] + 1;
                    if ($ratio > 0) {
                        $values['numero_pagos'] = round(log($ratio) / log(1 + $rate), 0);
                        $resultados['numero_pagos'] = (int) $values['numero_pagos'];
                        $camposCalculados[] = 'numero_pagos';
                        $messages[] = 'Número de pagos calculado (desde VF): '.$resultados['numero_pagos'];
                        $calculated = true;
                    }
                }

                // 6. Calcular Número de pagos desde VP
                if ($values['numero_pagos'] === null &&
                    $values['valor_presente'] !== null &&
                    $values['pago_periodico'] !== null &&
                    $rate !== null && $rate > 0) {

                    // n = -log(1 - (VP * r / PMT)) / log(1 + r)
                    $ratio = 1 - ($values['valor_presente'] * $rate) / $values['pago_periodico'];
                    if ($ratio > 0) {
                        $values['numero_pagos'] = round(-log($ratio) / log(1 + $rate), 0);
                        $resultados['numero_pagos'] = (int) $values['numero_pagos'];
                        $camposCalculados[] = 'numero_pagos';
                        $messages[] = 'Número de pagos calculado (desde VP): '.$resultados['numero_pagos'];
                        $calculated = true;
                    }
                }

                // 7. Calcular Tasa de interés (método Newton-Raphson mejorado)
                if ($values['tasa_interes'] === null &&
                    $values['pago_periodico'] !== null &&
                    $values['numero_pagos'] !== null &&
                    ($values['valor_presente'] !== null || $values['valor_futuro'] !== null)) {

                    $r = 0.05; // Estimación inicial (5% anual)
                    $maxIter = 200;
                    $tol = 1e-10;
                    $found = false;

                    if ($values['valor_presente'] !== null) {
                        // Iteración usando VP: PMT * [(1-(1+r)^-n)/r] - VP = 0
                        for ($i = 0; $i < $maxIter; $i++) {
                            if (abs($r) < 1e-12) {
                                $r = 1e-8; // Evitar división por cero
                            }

                            $factor = pow(1 + $r, -$values['numero_pagos']);
                            $f = $values['pago_periodico'] * ((1 - $factor) / $r) - $values['valor_presente'];
                            $fprime = $values['pago_periodico'] * (
                                (($values['numero_pagos'] * $factor) / (1 + $r)) / $r -
                                ((1 - $factor) / ($r * $r))
                            );

                            if (abs($fprime) < 1e-12) {
                                break;
                            }

                            $r_new = $r - $f / $fprime;

                            if (abs($r_new - $r) < $tol) {
                                $r = $r_new;
                                $found = true;
                                break;
                            }
                            $r = $r_new;

                            // Mantener r en rango razonable
                            if ($r < -0.99) {
                                $r = -0.99;
                            }
                            if ($r > 10) {
                                $r = 10;
                            }
                        }
                    } elseif ($values['valor_futuro'] !== null) {
                        // Iteración usando VF: PMT * [((1+r)^n - 1)/r] - VF = 0
                        for ($i = 0; $i < $maxIter; $i++) {
                            if (abs($r) < 1e-12) {
                                $r = 1e-8; // Evitar división por cero
                            }

                            $factor = pow(1 + $r, $values['numero_pagos']);
                            $f = $values['pago_periodico'] * (($factor - 1) / $r) - $values['valor_futuro'];
                            $fprime = $values['pago_periodico'] * (
                                (($values['numero_pagos'] * pow(1 + $r, $values['numero_pagos'] - 1)) / $r) -
                                (($factor - 1) / ($r * $r))
                            );

                            if (abs($fprime) < 1e-12) {
                                break;
                            }

                            $r_new = $r - $f / $fprime;

                            if (abs($r_new - $r) < $tol) {
                                $r = $r_new;
                                $found = true;
                                break;
                            }
                            $r = $r_new;

                            // Mantener r en rango razonable
                            if ($r < -0.99) {
                                $r = -0.99;
                            }
                            if ($r > 10) {
                                $r = 10;
                            }
                        }
                    }

                    if ($found && $r > -0.99 && $r < 10) {
                        $rate = $r; // Actualizar para futuros cálculos
                        $values['tasa_interes'] = $this->smartRound(($r * 100) * $periodicidadTasa);
                        $resultados['tasa_interes'] = $values['tasa_interes'];
                        $camposCalculados[] = 'tasa_interes';
                        $periodicidadTexto = $this->getPeriodicidadTexto($periodicidadTasa);
                        $source = $values['valor_presente'] !== null ? 'VP' : 'VF';
                        $messages[] = 'Tasa de interés calculada ('.$source.'): '.$resultados['tasa_interes'].'% '.$periodicidadTexto;
                        $calculated = true;
                    }
                }
            }
            if (isset($values['pago_periodico']) && isset($values['numero_pagos'])) {
                if (isset($values['valor_presente'])) {
                    $interesGenerado = $values['pago_periodico'] * $values['numero_pagos'] - $values['valor_presente'];
                } elseif (isset($values['valor_futuro'])) {
                    $interesGenerado = $values['valor_futuro'] - $values['pago_periodico'] * $values['numero_pagos'];
                }
            }

            if ($interesGenerado !== null) {
                $resultados['interes_generado'] = round($interesGenerado, 2);
                $messages[] = 'Interés generado calculado: '.number_format($resultados['interes_generado'], 2);
            }

        } catch (\Throwable $e) {
            return [
                'error' => true,
                'message' => 'Error en cálculo: '.$e->getMessage(),
            ];
        }

        // Retornar SOLO los campos ocultos, NO modificar los campos principales
        return [
            'error' => false,
            'data' => array_merge($data, [
                // Campos ocultos para almacenar resultados
                'campos_calculados' => json_encode($camposCalculados),
                'resultados_calculados' => json_encode($resultados),
                'interes_generado_calculado' => $interesGenerado ?? null,
                'mensaje_calculado' => implode('. ', $messages),
            ]),
            'message' => implode('. ', $messages),
        ];
    }
}
