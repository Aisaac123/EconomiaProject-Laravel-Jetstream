<?php

namespace App\Traits;

trait AmortizacionFormula
{
    use HelpersFormula;

    /**
     * Método principal para calcular sistemas de amortización
     */
    private function calculateAmortizacion(array $data): array
    {
        $camposCalculados = [];
        $resultados = [];
        $messages = [];

        // Validación 1: Sistema de amortización siempre debe estar presente
        if (empty($data['sistema_amortizacion'])) {
            return [
                'error' => true,
                'message' => 'Debes seleccionar un sistema de amortización (Francés, Alemán o Americano).',
            ];
        }

        $sistemaAmortizacion = strtolower($data['sistema_amortizacion']);
        $periodicidadTasa = $data['periodicidad_tasa'] ?? 12;

        // Campos principales según el sistema
        if ($sistemaAmortizacion === 'frances') {
            $camposFlexibles = ['monto_prestamo', 'numero_pagos', 'cuota_fija', 'tasa_interes'];
            $maxCamposVacios = 2;
        } elseif ($sistemaAmortizacion === 'aleman') {
            $camposFlexibles = ['monto_prestamo', 'numero_pagos', 'cuota_inicial', 'tasa_interes'];
            $camposObligatorios = []; // Ahora todos son flexibles
            $maxCamposVacios = 2;
        } elseif ($sistemaAmortizacion === 'americano') {
            $camposFlexibles = ['monto_prestamo', 'cuota_periodica', 'tasa_interes'];
            $camposObligatorios = ['numero_pagos'];
            $maxCamposVacios = 2;
        } else {
            $camposFlexibles = ['numero_pagos'];
            $camposObligatorios = ['monto_prestamo', 'tasa_interes'];
            $maxCamposVacios = 1;
        }

        // Validar campos obligatorios (cuando existen)
        if (! empty($camposObligatorios)) {
            $camposVacios = [];
            foreach ($camposObligatorios as $field) {
                if (empty($data[$field])) {
                    $camposVacios[] = $field;
                }
            }

            if (count($camposVacios) > 0) {
                $camposTexto = implode(', ', array_map(function ($field) {
                    return match ($field) {
                        'numero_pagos' => 'Número de Pagos',
                        'monto_prestamo' => 'Monto del Préstamo',
                        'tasa_interes' => 'Tasa de Interés',
                        default => $field
                    };
                }, $camposVacios));

                return [
                    'error' => true,
                    'message' => "Los siguientes campos son obligatorios para este sistema: $camposTexto",
                ];
            }
        }

        // Validar campos flexibles
        $camposFlexiblesVacios = [];
        foreach ($camposFlexibles as $field) {
            if (empty($data[$field])) {
                $camposFlexiblesVacios[] = $field;
            }
        }

        if (count($camposFlexiblesVacios) > $maxCamposVacios) {
            return [
                'error' => true,
                'message' => "En el sistema {$sistemaAmortizacion}, solo puedes dejar máximo {$maxCamposVacios} campo(s) vacío(s). Actualmente hay ".count($camposFlexiblesVacios).' vacíos.',
            ];
        }
        if (count($camposFlexiblesVacios) === 0) {
            return [
                'error' => true,
                'message' => 'Debes dejar al menos 1 campo vacío para que el sistema pueda calcularlo. Actualmente todos los campos están llenos.',
            ];
        }

        // Inicializar valores
        $values = [
            'monto_prestamo' => ! empty($data['monto_prestamo']) ? (float) $data['monto_prestamo'] : null,
            'tasa_interes' => ! empty($data['tasa_interes']) ? (float) $data['tasa_interes'] : null,
            'numero_pagos' => ! empty($data['numero_pagos']) ? (int) $data['numero_pagos'] : null,
            'cuota_fija' => ! empty($data['cuota_fija']) ? (float) $data['cuota_fija'] : null,
            'cuota_inicial' => ! empty($data['cuota_inicial']) ? (float) $data['cuota_inicial'] : null,
            'cuota_periodica' => ! empty($data['cuota_periodica']) ? (float) $data['cuota_periodica'] : null,
        ];

        // Convertir tasa a decimal por periodo
        $tasaPorPeriodo = null;
        if ($values['tasa_interes'] !== null) {
            $tasaAnual = $values['tasa_interes'];
            if ($periodicidadTasa != 1) {
                $tasaAnual = $values['tasa_interes'] * $periodicidadTasa;
            }
            $tasaPorPeriodo = ($tasaAnual / 100) / $periodicidadTasa;
        }

        try {
            if ($sistemaAmortizacion === 'frances') {
                // SISTEMA FRANCÉS - Cálculos iterativos
                $resultado = $this->calculateSistemaFrancesIterativo($values, $tasaPorPeriodo, $periodicidadTasa, $messages, $camposCalculados);

            } elseif ($sistemaAmortizacion === 'aleman') {
                // SISTEMA ALEMÁN
                $resultado = $this->calculateSistemaAleman($values, $tasaPorPeriodo, $periodicidadTasa, $camposCalculados);

            } elseif ($sistemaAmortizacion === 'americano') {
                // SISTEMA AMERICANO
                $resultado = $this->calculateSistemaAmericano($values, $tasaPorPeriodo, $periodicidadTasa, $camposCalculados);

            } else {
                return [
                    'error' => true,
                    'message' => 'Sistema de amortización no válido.',
                ];
            }

            if ($resultado['error']) {
                return $resultado;
            }

            $resultados = $resultado['data'];
            $messages = array_merge($messages, $resultado['messages']);
            $camposCalculados = array_merge($camposCalculados, $resultado['campos_calculados'] ?? []);

        } catch (\Throwable $e) {
            return [
                'error' => true,
                'message' => 'Error en cálculo: '.$e->getMessage(),
            ];
        }

        if (isset($resultado['campos_calculados'])) {
            $camposCalculados = array_unique(array_merge($camposCalculados, $resultado['campos_calculados']));
        }

        return [
            'error' => false,
            'data' => array_merge($data, [
                'campos_calculados' => json_encode($camposCalculados),
                'resultados_calculados' => json_encode($resultados),
                'tabla_amortizacion' => json_encode($resultados['tabla_amortizacion'] ?? []),
                'mensaje_calculado' => implode('. ', $messages),
            ]),
            'message' => implode('. ', $messages),
        ];
    }

    /**
     * SISTEMA FRANCÉS - Cálculos iterativos con despejes completos
     */
    private function calculateSistemaFrancesIterativo(array &$values, ?float &$tasaPorPeriodo, int $periodicidadTasa, array &$messages, array &$camposCalculados): array
    {
        $maxIterations = 15;
        $iteration = 0;
        $calculated = true;

        while ($calculated && $iteration < $maxIterations) {
            $calculated = false;
            $iteration++;

            // 1. Calcular MONTO_PRESTAMO: VP = PMT × [(1 - (1+r)^-n) / r]
            if ($values['monto_prestamo'] === null && $values['cuota_fija'] !== null &&
                $tasaPorPeriodo !== null && $values['numero_pagos'] !== null) {

                if ($tasaPorPeriodo == 0) {
                    $values['monto_prestamo'] = $values['cuota_fija'] * $values['numero_pagos'];
                } else {
                    $values['monto_prestamo'] = $values['cuota_fija'] * ((1 - pow(1 + $tasaPorPeriodo, -$values['numero_pagos'])) / $tasaPorPeriodo);
                }
                $camposCalculados[] = 'monto_prestamo';
                $messages[] = 'Monto del préstamo calculado: $'.number_format($values['monto_prestamo'], 2);
                $calculated = true;
            }

            // 2. Calcular CUOTA_FIJA: PMT = VP × [r / (1 - (1+r)^-n)]
            if ($values['cuota_fija'] === null && $values['monto_prestamo'] !== null &&
                $tasaPorPeriodo !== null && $values['numero_pagos'] !== null) {

                if ($tasaPorPeriodo == 0) {
                    $values['cuota_fija'] = $values['monto_prestamo'] / $values['numero_pagos'];
                } else {
                    $values['cuota_fija'] = $values['monto_prestamo'] * ($tasaPorPeriodo / (1 - pow(1 + $tasaPorPeriodo, -$values['numero_pagos'])));
                }
                $camposCalculados[] = 'cuota_fija';
                $messages[] = 'Cuota fija calculada: $'.number_format($values['cuota_fija'], 2);
                $calculated = true;
            }

            // 3. Calcular NÚMERO_PAGOS: n = -log(1 - (VP*r/PMT)) / log(1+r)
            if ($values['numero_pagos'] === null && $values['monto_prestamo'] !== null &&
                $values['cuota_fija'] !== null && $tasaPorPeriodo !== null && $tasaPorPeriodo > 0) {

                $ratio = 1 - ($values['monto_prestamo'] * $tasaPorPeriodo) / $values['cuota_fija'];
                if ($ratio > 0) {
                    $values['numero_pagos'] = round(-log($ratio) / log(1 + $tasaPorPeriodo), 0);
                    $camposCalculados[] = 'numero_pagos';
                    $messages[] = 'Número de pagos calculado: '.intval($values['numero_pagos']);
                    $calculated = true;
                } else {
                    return [
                        'error' => true,
                        'message' => 'No se puede calcular el número de pagos con estos valores. La cuota es muy pequeña.',
                    ];
                }
            }

            // 4. Calcular TASA DE INTERÉS: Newton-Raphson
            if ($values['tasa_interes'] === null && $values['monto_prestamo'] !== null &&
                $values['numero_pagos'] !== null && $values['cuota_fija'] !== null) {

                $r = 0.01;
                $maxIter = 200;
                $tol = 1e-10;
                $found = false;

                for ($i = 0; $i < $maxIter; $i++) {
                    if (abs($r) < 1e-12) {
                        $r = 1e-8;
                    }

                    $factor = pow(1 + $r, -$values['numero_pagos']);
                    $f = $values['cuota_fija'] * ((1 - $factor) / $r) - $values['monto_prestamo'];
                    $fprime = $values['cuota_fija'] * (
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

                    if ($r < -0.99) {
                        $r = -0.99;
                    }
                    if ($r > 10) {
                        $r = 10;
                    }
                }

                if ($found && $r > -0.99 && $r < 10) {
                    $tasaPorPeriodo = $r;
                    $tasaAnual = $r * $periodicidadTasa * 100;
                    $values['tasa_interes'] = smartRound($tasaAnual / $periodicidadTasa);
                    $camposCalculados[] = 'tasa_interes';
                    $messages[] = 'Tasa de interés calculada: '.$values['tasa_interes'].'%';
                    $calculated = true;
                } else {
                    return [
                        'error' => true,
                        'message' => 'No se pudo calcular la tasa de interés. Verifica los valores ingresados.',
                    ];
                }
            }
        }

        // Validar que todos los valores estén calculados
        if ($values['monto_prestamo'] === null || $tasaPorPeriodo === null ||
            $values['numero_pagos'] === null || $values['cuota_fija'] === null) {
            return [
                'error' => true,
                'message' => 'No fue posible calcular todos los valores necesarios. Verifica los datos ingresados.',
            ];
        }

        // Generar tabla
        $tabla = $this->generateTablaAmortizacion(
            $values['monto_prestamo'],
            $values['cuota_fija'],
            $tasaPorPeriodo,
            (int) $values['numero_pagos'],
            'frances'
        );

        $primeraCuota = $tabla[0];
        $ultimaCuota = $tabla[count($tabla) - 1];
        $totalIntereses = array_sum(array_column($tabla, 'interes'));
        $totalPagado = array_sum(array_column($tabla, 'cuota'));

        $resultados = [
            'sistema' => 'Francés',
            'monto_prestamo' => round($values['monto_prestamo'], 2),
            'tasa_interes' => round($values['tasa_interes'], 2),
            'numero_pagos' => (int) $values['numero_pagos'],
            'cuota_fija' => round($values['cuota_fija'], 2),
            'cuota_inicial' => round($primeraCuota['cuota'], 2),
            'cuota_final' => round($ultimaCuota['cuota'], 2),
            'amortizacion_inicial' => round($primeraCuota['amortizacion'], 2),
            'amortizacion_final' => round($ultimaCuota['amortizacion'], 2),
            'interes_inicial' => round($primeraCuota['interes'], 2),
            'interes_final' => round($ultimaCuota['interes'], 2),
            'saldo_inicial' => round($values['monto_prestamo'], 2),
            'saldo_final' => round($ultimaCuota['saldo_final'], 2),
            'total_intereses' => round($totalIntereses, 2),
            'total_pagado' => round($totalPagado, 2),
            'tabla_amortizacion' => $tabla,
        ];

        return [
            'error' => false,
            'data' => $resultados,
            'messages' => $messages,
        ];
    }

    /**
     * Sistema Alemán: Amortización constante
     */
    private function calculateSistemaAleman(array &$values, ?float &$tasaPorPeriodo, int $periodicidadTasa, array &$camposCalculados): array
    {
        $messages = [];
        $camposCalculados = [];
        $maxIterations = 15;
        $iteration = 0;
        $calculated = true;

        while ($calculated && $iteration < $maxIterations) {
            $calculated = false;
            $iteration++;

            // 1. Calcular MONTO_PRESTAMO desde cuota_inicial
            // cuota_inicial = (P/n) + (P*r) => P = cuota_inicial / (1/n + r)
            if ($values['monto_prestamo'] === null && $values['cuota_inicial'] !== null &&
                $tasaPorPeriodo !== null && $values['numero_pagos'] !== null) {

                $denominador = (1 / $values['numero_pagos']) + $tasaPorPeriodo;
                if ($denominador > 0) {
                    $values['monto_prestamo'] = $values['cuota_inicial'] / $denominador;
                    $camposCalculados[] = 'monto_prestamo';
                    $messages[] = 'Monto del préstamo calculado: $'.number_format($values['monto_prestamo'], 2);
                    $calculated = true;
                }
            }

            // 2. Calcular NÚMERO_PAGOS desde cuota_inicial
            // cuota_inicial = (P/n) + (P*r) => n = P / (cuota_inicial - P*r)
            if ($values['numero_pagos'] === null && $values['monto_prestamo'] !== null &&
                $values['cuota_inicial'] !== null && $tasaPorPeriodo !== null) {

                $denominador = $values['cuota_inicial'] - ($values['monto_prestamo'] * $tasaPorPeriodo);
                if ($denominador > 0) {
                    $values['numero_pagos'] = round($values['monto_prestamo'] / $denominador, 0);
                    $camposCalculados[] = 'numero_pagos';
                    $messages[] = 'Número de pagos calculado: '.intval($values['numero_pagos']);
                    $calculated = true;
                }
            }

            // 3. Calcular TASA_INTERES desde cuota_inicial usando Newton-Raphson
            // cuota_inicial = (P/n) + (P*r) => r = (cuota_inicial - P/n) / P
            if ($values['tasa_interes'] === null && $values['monto_prestamo'] !== null &&
                $values['numero_pagos'] !== null && $values['cuota_inicial'] !== null) {

                $amortizacion = $values['monto_prestamo'] / $values['numero_pagos'];
                $interesInicial = $values['cuota_inicial'] - $amortizacion;

                if ($interesInicial >= 0 && $values['monto_prestamo'] > 0) {
                    $tasaPorPeriodo = $interesInicial / $values['monto_prestamo'];
                    $tasaAnual = $tasaPorPeriodo * $periodicidadTasa * 100;
                    $values['tasa_interes'] = smartRound($tasaAnual / $periodicidadTasa);
                    $camposCalculados[] = 'tasa_interes';
                    $messages[] = 'Tasa de interés calculada: '.$values['tasa_interes'].'%';
                    $calculated = true;
                }
            }

            // 4. Calcular CUOTA_INICIAL
            // cuota_inicial = (P/n) + (P*r)
            if ($values['cuota_inicial'] === null && $values['monto_prestamo'] !== null &&
                $tasaPorPeriodo !== null && $values['numero_pagos'] !== null) {

                $amortizacion = $values['monto_prestamo'] / $values['numero_pagos'];
                $interes = $values['monto_prestamo'] * $tasaPorPeriodo;
                $values['cuota_inicial'] = $amortizacion + $interes;
                $camposCalculados[] = 'cuota_inicial';
                $messages[] = 'Cuota inicial calculada: $'.number_format($values['cuota_inicial'], 2);
                $calculated = true;
            }
        }

        // Validar que todos los valores estén calculados
        if ($values['monto_prestamo'] === null || $tasaPorPeriodo === null ||
            $values['numero_pagos'] === null) {
            return [
                'error' => true,
                'message' => 'No fue posible calcular todos los valores necesarios para el sistema alemán. Verifica los datos ingresados.',
            ];
        }

        $amortizacionConstante = $values['monto_prestamo'] / $values['numero_pagos'];

        $tabla = $this->generateTablaAmortizacion(
            $values['monto_prestamo'],
            null,
            $tasaPorPeriodo,
            $values['numero_pagos'],
            'aleman',
            $amortizacionConstante
        );

        $primeraCuota = $tabla[0];
        $ultimaCuota = $tabla[count($tabla) - 1];
        $totalIntereses = array_sum(array_column($tabla, 'interes'));
        $totalPagado = array_sum(array_column($tabla, 'cuota'));

        $resultados = [
            'sistema' => 'Alemán',
            'monto_prestamo' => round($values['monto_prestamo'], 2),
            'tasa_interes' => round($values['tasa_interes'], 2),
            'numero_pagos' => (int) $values['numero_pagos'],
            'amortizacion_constante' => round($amortizacionConstante, 2),
            'cuota_inicial' => round($primeraCuota['cuota'], 2),
            'cuota_final' => round($ultimaCuota['cuota'], 2),
            'amortizacion_inicial' => round($primeraCuota['amortizacion'], 2),
            'amortizacion_final' => round($ultimaCuota['amortizacion'], 2),
            'interes_inicial' => round($primeraCuota['interes'], 2),
            'interes_final' => round($ultimaCuota['interes'], 2),
            'saldo_inicial' => round($values['monto_prestamo'], 2),
            'saldo_final' => round($ultimaCuota['saldo_final'], 2),
            'total_intereses' => round($totalIntereses, 2),
            'total_pagado' => round($totalPagado, 2),
            'tabla_amortizacion' => $tabla,
        ];

        if (empty($messages)) {
            $messages[] = 'Sistema Alemán: Amortización constante de $'.number_format($amortizacionConstante, 2);
        }
        $messages[] = 'Cuota inicial: $'.number_format($primeraCuota['cuota'], 2);
        $messages[] = 'Cuota final: $'.number_format($ultimaCuota['cuota'], 2);
        $messages[] = 'Total de intereses: $'.number_format($totalIntereses, 2);

        return [
            'error' => false,
            'data' => $resultados,
            'messages' => $messages,
            'campos_calculados' => $camposCalculados,
        ];
    }

    /**
     * Sistema Americano: Solo intereses, capital al final
     */
    private function calculateSistemaAmericano(array &$values, ?float &$tasaPorPeriodo, int $periodicidadTasa, array &$camposCalculados): array
    {
        $messages = [];
        $camposCalculados = [];
        $maxIterations = 15;
        $iteration = 0;
        $calculated = true;

        while ($calculated && $iteration < $maxIterations) {
            $calculated = false;
            $iteration++;

            // 1. Calcular MONTO_PRESTAMO desde cuota_periodica
            // cuota_periodica = P * r => P = cuota_periodica / r
            if ($values['monto_prestamo'] === null && $values['cuota_periodica'] !== null &&
                $tasaPorPeriodo !== null && $tasaPorPeriodo > 0) {

                $values['monto_prestamo'] = $values['cuota_periodica'] / $tasaPorPeriodo;
                $camposCalculados[] = 'monto_prestamo';
                $messages[] = 'Monto del préstamo calculado: $'.number_format($values['monto_prestamo'], 2);
                $calculated = true;
            }

            // 2. Calcular TASA_INTERES desde cuota_periodica
            // cuota_periodica = P * r => r = cuota_periodica / P
            if ($values['tasa_interes'] === null && $values['monto_prestamo'] !== null &&
                $values['cuota_periodica'] !== null && $values['monto_prestamo'] > 0) {

                $tasaPorPeriodo = $values['cuota_periodica'] / $values['monto_prestamo'];
                $tasaAnual = $tasaPorPeriodo * $periodicidadTasa * 100;
                $values['tasa_interes'] = smartRound($tasaAnual / $periodicidadTasa);
                $camposCalculados[] = 'tasa_interes';
                $messages[] = 'Tasa de interés calculada: '.$values['tasa_interes'].'%';
                $calculated = true;
            }

            // 3. Calcular CUOTA_PERIODICA
            // cuota_periodica = P * r
            if ($values['cuota_periodica'] === null && $values['monto_prestamo'] !== null &&
                $tasaPorPeriodo !== null) {

                $values['cuota_periodica'] = $values['monto_prestamo'] * $tasaPorPeriodo;
                $camposCalculados[] = 'cuota_periodica';
                $messages[] = 'Cuota periódica calculada: $'.number_format($values['cuota_periodica'], 2);
                $calculated = true;
            }

        }

        // Validar que todos los valores estén calculados
        if ($values['monto_prestamo'] === null || $tasaPorPeriodo === null ||
            $values['numero_pagos'] === null) {
            return [
                'error' => true,
                'message' => 'No fue posible calcular todos los valores necesarios para el sistema americano. Verifica los datos ingresados.',
            ];
        }

        $cuotaInteres = $values['monto_prestamo'] * $tasaPorPeriodo;

        $tabla = $this->generateTablaAmortizacion(
            $values['monto_prestamo'],
            null,
            $tasaPorPeriodo,
            $values['numero_pagos'],
            'americano'
        );

        $primeraCuota = $tabla[0];
        $ultimaCuota = $tabla[count($tabla) - 1];
        $totalIntereses = array_sum(array_column($tabla, 'interes'));
        $totalPagado = array_sum(array_column($tabla, 'cuota'));

        $resultados = [
            'sistema' => 'Americano',
            'monto_prestamo' => round($values['monto_prestamo'], 2),
            'tasa_interes' => round($values['tasa_interes'], 2),
            'numero_pagos' => (int) $values['numero_pagos'],
            'cuota_interes_periodica' => round($cuotaInteres, 2),
            'cuota_inicial' => round($primeraCuota['cuota'], 2),
            'cuota_final' => round($ultimaCuota['cuota'], 2),
            'amortizacion_inicial' => round($primeraCuota['amortizacion'], 2),
            'amortizacion_final' => round($ultimaCuota['amortizacion'], 2),
            'interes_inicial' => round($primeraCuota['interes'], 2),
            'interes_final' => round($ultimaCuota['interes'], 2),
            'saldo_inicial' => round($values['monto_prestamo'], 2),
            'saldo_final' => round($ultimaCuota['saldo_final'], 2),
            'total_intereses' => round($totalIntereses, 2),
            'total_pagado' => round($totalPagado, 2),
            'tabla_amortizacion' => $tabla,
        ];

        if (empty($messages)) {
            $messages[] = 'Sistema Americano: Cuota de interés periódica de $'.number_format($cuotaInteres, 2);
        }
        $messages[] = 'Pago final (capital + interés): $'.number_format($ultimaCuota['cuota'], 2);
        $messages[] = 'Total de intereses: $'.number_format($totalIntereses, 2);

        return [
            'error' => false,
            'data' => $resultados,
            'messages' => $messages,
            'campos_calculados' => $camposCalculados,
        ];
    }

    /**
     * Genera la tabla de amortización completa
     */
    private function generateTablaAmortizacion(
        float $montoPrestamo,
        ?float $cuotaFija,
        float $tasaPorPeriodo,
        int $numeroPagos,
        string $sistema,
        ?float $amortizacionConstante = null
    ): array {
        $tabla = [];
        $saldoInicial = $montoPrestamo;

        for ($periodo = 1; $periodo <= $numeroPagos; $periodo++) {
            $interes = $saldoInicial * $tasaPorPeriodo;

            switch ($sistema) {
                case 'frances':
                    $cuota = $cuotaFija;
                    $amortizacion = $cuota - $interes;
                    break;

                case 'aleman':
                    $amortizacion = $amortizacionConstante;
                    $cuota = $amortizacion + $interes;
                    break;

                case 'americano':
                    if ($periodo < $numeroPagos) {
                        $amortizacion = 0;
                        $cuota = $interes;
                    } else {
                        $amortizacion = $saldoInicial;
                        $cuota = $amortizacion + $interes;
                    }
                    break;

                default:
                    $amortizacion = 0;
                    $cuota = 0;
            }

            $saldoFinal = $saldoInicial - $amortizacion;

            $tabla[] = [
                'periodo' => $periodo,
                'saldo_inicial' => round($saldoInicial, 2),
                'cuota' => round($cuota, 2),
                'interes' => round($interes, 2),
                'amortizacion' => round($amortizacion, 2),
                'saldo_final' => round($saldoFinal, 2),
            ];

            $saldoInicial = $saldoFinal;
        }

        return $tabla;
    }
}
