<?php

namespace App\Traits;

trait GradientesFormula
{
    use HelpersFormula;

    private function calculateGradiente(array $data): array
    {
        $tipoGradiente = $data['tipo_gradiente'] ?? null;

        if (! $tipoGradiente) {
            return [
                'error' => true,
                'message' => 'Debes seleccionar un tipo de gradiente.',
            ];
        }

        // Validar campos básicos
        $emptyFields = [];
        $basicFields = ['valor_presente', 'valor_futuro', 'anualidad_inicial', 'tasa_interes', 'numero_pagos'];

        // Agregar el campo de gradiente según el tipo
        if (strpos($tipoGradiente, 'aritmetico') !== false) {
            $basicFields[] = 'gradiente_aritmetico';
        } else {
            $basicFields[] = 'gradiente_geometrico';
        }

        foreach ($basicFields as $field) {
            if (in_array($field, ['gradiente_aritmetico', 'gradiente_geometrico'])) {
                // Para estos dos campos, solo se consideran vacíos si son null o '' (no 0)
                if (! isset($data[$field]) || $data[$field] === '') {
                    $emptyFields[] = $field;
                }
            } else {
                // Para los demás campos, se aplica empty normal
                if (empty($data[$field])) {
                    $emptyFields[] = $field;
                }
            }
        }

        // Validación: máximo 2 campos vacíos
        if (count($emptyFields) > 2) {
            return [
                'error' => true,
                'message' => 'Solo puedes dejar máximo 2 campos vacíos. Actualmente hay '.count($emptyFields).' vacíos.',
            ];
        }

        // Calcular según el tipo de gradiente
        switch ($tipoGradiente) {
            case 'aritmetico_anticipado':
            case 'aritmetico_vencido':
                return $this->calculateGradienteAritmetico($data, $emptyFields, $tipoGradiente);
            case 'geometrico_anticipado':
            case 'geometrico_vencido':
                return $this->calculateGradienteGeometrico($data, $emptyFields, $tipoGradiente);
            default:
                return [
                    'error' => true,
                    'message' => 'Tipo de gradiente no válido.',
                ];
        }
    }

    private function calculateGradienteAritmetico(array $data, array $emptyFields, string $tipoGradiente): array
    {
        $camposCalculados = [];
        $resultados = [];
        $messages = [];

        $periodicidadTasa = $data['periodicidad_tasa'] ?? 12;
        $esAnticipado = $tipoGradiente === 'aritmetico_anticipado';

        // Inicializar valores
        $values = [
            'valor_presente' => ! empty($data['valor_presente']) ? (float) $data['valor_presente'] : null,
            'valor_futuro' => ! empty($data['valor_futuro']) ? (float) $data['valor_futuro'] : null,
            'anualidad_inicial' => ! empty($data['anualidad_inicial']) ? (float) $data['anualidad_inicial'] : null,
            'gradiente_aritmetico' => isset($data['gradiente_aritmetico']) ? (float) $data['gradiente_aritmetico'] : null,
            'tasa_interes' => ! empty($data['tasa_interes']) ? (float) $data['tasa_interes'] : null,
            'numero_pagos' => ! empty($data['numero_pagos']) ? (int) $data['numero_pagos'] : null,
        ];

        // Convertir tasa a decimal por periodo
        $rate = null;
        if ($values['tasa_interes'] !== null) {
            $tasaAnual = $values['tasa_interes'];
            if ($periodicidadTasa != 1) {
                $tasaAnual = $values['tasa_interes'] * $periodicidadTasa;
            }
            $rate = $tasaAnual / 100;
        }

        try {
            $maxIterations = 10;
            $iteration = 0;
            $calculated = true;

            while ($calculated && $iteration < $maxIterations) {
                $calculated = false;
                $iteration++;

                // 1. Calcular Valor Presente si falta
                if ($values['valor_presente'] === null &&
                    $values['anualidad_inicial'] !== null &&
                    $values['gradiente_aritmetico'] !== null &&
                    $rate !== null &&
                    $values['numero_pagos'] !== null) {

                    $values['valor_presente'] = $this->calcularVPDesdeAnualidadAritmetica(
                        $values['anualidad_inicial'],
                        $values['gradiente_aritmetico'],
                        $rate,
                        $values['numero_pagos'],
                        $esAnticipado
                    );

                    $resultados['valor_presente'] = round($values['valor_presente'], 2);
                    $camposCalculados[] = 'valor_presente';
                    $messages[] = 'Valor Presente calculado: $'.number_format($resultados['valor_presente'], 2);
                    $calculated = true;
                }

                // 2. Calcular Valor Futuro si falta
                if ($values['valor_futuro'] === null &&
                    $values['anualidad_inicial'] !== null &&
                    $values['gradiente_aritmetico'] !== null &&
                    $rate !== null &&
                    $values['numero_pagos'] !== null) {

                    $values['valor_futuro'] = $this->calcularVFDesdeAnualidadAritmetica(
                        $values['anualidad_inicial'],
                        $values['gradiente_aritmetico'],
                        $rate,
                        $values['numero_pagos'],
                        $esAnticipado
                    );

                    $resultados['valor_futuro'] = round($values['valor_futuro'], 2);
                    $camposCalculados[] = 'valor_futuro';
                    $messages[] = 'Valor Futuro calculado: $'.number_format($resultados['valor_futuro'], 2);
                    $calculated = true;
                }

                // 3. Calcular Anualidad Inicial desde VP
                if ($values['anualidad_inicial'] === null &&
                    $values['valor_presente'] !== null &&
                    $values['gradiente_aritmetico'] !== null &&
                    $rate !== null &&
                    $values['numero_pagos'] !== null) {

                    $values['anualidad_inicial'] = $this->calcularAnualidadDesdeVPAritmetica(
                        $values['valor_presente'],
                        $values['gradiente_aritmetico'],
                        $rate,
                        $values['numero_pagos'],
                        $esAnticipado
                    );

                    $resultados['anualidad_inicial'] = round($values['anualidad_inicial'], 2);
                    $camposCalculados[] = 'anualidad_inicial';
                    $messages[] = 'Anualidad Inicial calculada: $'.number_format($resultados['anualidad_inicial'], 2);
                    $calculated = true;
                }

                // 4. Calcular Anualidad Inicial desde VF
                if ($values['anualidad_inicial'] === null &&
                    $values['valor_futuro'] !== null &&
                    $values['gradiente_aritmetico'] !== null &&
                    $rate !== null &&
                    $values['numero_pagos'] !== null) {

                    $values['anualidad_inicial'] = $this->calcularAnualidadDesdeVFAritmetica(
                        $values['valor_futuro'],
                        $values['gradiente_aritmetico'],
                        $rate,
                        $values['numero_pagos'],
                        $esAnticipado
                    );

                    $resultados['anualidad_inicial'] = round($values['anualidad_inicial'], 2);
                    $camposCalculados[] = 'anualidad_inicial';
                    $messages[] = 'Anualidad Inicial calculada: $'.number_format($resultados['anualidad_inicial'], 2);
                    $calculated = true;
                }

                // 5. Calcular Gradiente desde VP
                if ($values['gradiente_aritmetico'] === null &&
                    $values['valor_presente'] !== null &&
                    $values['anualidad_inicial'] !== null &&
                    $rate !== null &&
                    $values['numero_pagos'] !== null) {

                    $values['gradiente_aritmetico'] = $this->calcularGradienteDesdeVPAritmetica(
                        $values['valor_presente'],
                        $values['anualidad_inicial'],
                        $rate,
                        $values['numero_pagos'],
                        $esAnticipado
                    );

                    $resultados['gradiente_aritmetico'] = round($values['gradiente_aritmetico'], 2);
                    $camposCalculados[] = 'gradiente_aritmetico';
                    $messages[] = 'Gradiente Aritmético calculado: $'.number_format($resultados['gradiente_aritmetico'], 2);
                    $calculated = true;
                }

                // 6. Calcular Gradiente desde VF
                if ($values['gradiente_aritmetico'] === null &&
                    $values['valor_futuro'] !== null &&
                    $values['anualidad_inicial'] !== null &&
                    $rate !== null &&
                    $values['numero_pagos'] !== null) {

                    $values['gradiente_aritmetico'] = $this->calcularGradienteDesdeVFAritmetica(
                        $values['valor_futuro'],
                        $values['anualidad_inicial'],
                        $rate,
                        $values['numero_pagos'],
                        $esAnticipado
                    );

                    $resultados['gradiente_aritmetico'] = round($values['gradiente_aritmetico'], 2);
                    $camposCalculados[] = 'gradiente_aritmetico';
                    $messages[] = 'Gradiente Aritmético calculado: $'.number_format($resultados['gradiente_aritmetico'], 2);
                    $calculated = true;
                }

                // 7. Calcular Tasa de interés si falta (método iterativo)
                if ($values['tasa_interes'] === null &&
                    $values['anualidad_inicial'] !== null &&
                    $values['gradiente_aritmetico'] !== null &&
                    $values['numero_pagos'] !== null &&
                    ($values['valor_presente'] !== null || $values['valor_futuro'] !== null)) {

                    $tasaCalculada = $this->calcularTasaInteresAritmetica($values, $periodicidadTasa, $esAnticipado);

                    if ($tasaCalculada !== null) {
                        $values['tasa_interes'] = $tasaCalculada;
                        $rate = ($values['tasa_interes'] * $periodicidadTasa / 100);

                        $resultados['tasa_interes'] = round($values['tasa_interes'], 4);
                        $camposCalculados[] = 'tasa_interes';
                        $periodicidadTexto = $this->getPeriodicidadTexto($periodicidadTasa);
                        $messages[] = 'Tasa de interés calculada: '.$resultados['tasa_interes'].'% '.$periodicidadTexto;
                        $calculated = true;
                    }
                }

                // 8. Calcular Número de pagos si falta
                if ($values['numero_pagos'] === null &&
                    $values['anualidad_inicial'] !== null &&
                    $values['gradiente_aritmetico'] !== null &&
                    $rate !== null &&
                    ($values['valor_presente'] !== null || $values['valor_futuro'] !== null)) {

                    $nCalculado = $this->calcularNumeroPagosAritmetica($values, $rate, $esAnticipado);

                    if ($nCalculado !== null) {
                        $values['numero_pagos'] = $nCalculado;
                        $resultados['numero_pagos'] = (int) $values['numero_pagos'];
                        $camposCalculados[] = 'numero_pagos';
                        $messages[] = 'Número de pagos calculado: '.$resultados['numero_pagos'];
                        $calculated = true;
                    }
                }
            }

            // Calcular valores adicionales
            if ($values['anualidad_inicial'] !== null && $values['gradiente_aritmetico'] !== null && $values['numero_pagos'] !== null) {
                $resultados['anualidad_final'] = round($values['anualidad_inicial'] + ($values['gradiente_aritmetico'] * ($values['numero_pagos'] - 1)), 2);

                // Calcular total de pagos
                $totalPagos = 0;
                for ($i = 0; $i < $values['numero_pagos']; $i++) {
                    $totalPagos += $values['anualidad_inicial'] + ($values['gradiente_aritmetico'] * $i);
                }
                $resultados['total_pagos'] = round($totalPagos, 2);
            }

            // Calcular interés generado
            if (isset($resultados['total_pagos'])) {
                if (isset($values['valor_presente'])) {
                    $resultados['interes_generadoVP'] = round($resultados['total_pagos'] - $values['valor_presente'], 2);
                    $messages[] = 'Interés generado calculado desde VP: $'.number_format($resultados['interes_generadoVP'], 2);
                }
                if (isset($values['valor_futuro'])) {
                    $resultados['interes_generadoVF'] = round($values['valor_futuro'] - $resultados['total_pagos'], 2);
                    $messages[] = 'Interés generado calculado desde VF: $'.number_format($resultados['interes_generadoVF'], 2);
                }
            }

            // Agregar valores no calculados a resultados
            foreach ($values as $key => $value) {
                if ($value !== null && ! isset($resultados[$key])) {
                    $resultados[$key] = is_float($value) ? round($value, 2) : $value;
                }
            }

            // Generar tabla de gradiente
            $tablaGradiente = $this->generarTablaGradienteAritmetico($values, $rate, $esAnticipado);

        } catch (\Throwable $e) {
            return [
                'error' => true,
                'message' => 'Error en cálculo: '.$e->getMessage(),
            ];
        }

        return [
            'error' => false,
            'data' => array_merge($data, [
                'campos_calculados' => json_encode($camposCalculados),
                'resultados_calculados' => json_encode($resultados),
                'tabla_gradiente' => json_encode($tablaGradiente),
                'mensaje_calculado' => implode('. ', $messages),
            ]),
            'message' => implode('. ', $messages),
        ];
    }

    private function calculateGradienteGeometrico(array $data, array $emptyFields, string $tipoGradiente): array
    {
        $camposCalculados = [];
        $resultados = [];
        $messages = [];

        $periodicidadTasa = $data['periodicidad_tasa'] ?? 12;
        $esAnticipado = $tipoGradiente === 'geometrico_anticipado';

        // Inicializar valores
        $values = [
            'valor_presente' => ! empty($data['valor_presente']) ? (float) $data['valor_presente'] : null,
            'valor_futuro' => ! empty($data['valor_futuro']) ? (float) $data['valor_futuro'] : null,
            'anualidad_inicial' => ! empty($data['anualidad_inicial']) ? (float) $data['anualidad_inicial'] : null,
            'gradiente_geometrico' => isset($data['gradiente_geometrico']) ? (float) $data['gradiente_geometrico'] : null,
            'tasa_interes' => ! empty($data['tasa_interes']) ? (float) $data['tasa_interes'] : null,
            'numero_pagos' => ! empty($data['numero_pagos']) ? (int) $data['numero_pagos'] : null,
        ];

        // Convertir tasa a decimal por periodo
        $rate = null;
        if ($values['tasa_interes'] !== null) {
            $tasaAnual = $values['tasa_interes'];
            if ($periodicidadTasa != 1) {
                $tasaAnual = $values['tasa_interes'] * $periodicidadTasa;
            }
            $rate = $tasaAnual / 100;
        }

        // Convertir gradiente geométrico a decimal
        $g = null;
        if ($values['gradiente_geometrico'] !== null) {
            $g = $values['gradiente_geometrico'] / 100;
        }

        try {
            $maxIterations = 10;
            $iteration = 0;
            $calculated = true;

            while ($calculated && $iteration < $maxIterations) {
                $calculated = false;
                $iteration++;

                // 1. Calcular Valor Presente si falta
                if ($values['valor_presente'] === null &&
                    $values['anualidad_inicial'] !== null &&
                    $g !== null &&
                    $rate !== null &&
                    $values['numero_pagos'] !== null) {

                    $values['valor_presente'] = $this->calcularVPDesdeAnualidadGeometrica(
                        $values['anualidad_inicial'],
                        $g,
                        $rate,
                        $values['numero_pagos'],
                        $esAnticipado
                    );

                    $resultados['valor_presente'] = round($values['valor_presente'], 2);
                    $camposCalculados[] = 'valor_presente';
                    $messages[] = 'Valor Presente calculado: $'.number_format($resultados['valor_presente'], 2);
                    $calculated = true;
                }

                // 2. Calcular Valor Futuro si falta
                if ($values['valor_futuro'] === null &&
                    $values['anualidad_inicial'] !== null &&
                    $g !== null &&
                    $rate !== null &&
                    $values['numero_pagos'] !== null) {

                    $values['valor_futuro'] = $this->calcularVFDesdeAnualidadGeometrica(
                        $values['anualidad_inicial'],
                        $g,
                        $rate,
                        $values['numero_pagos'],
                        $esAnticipado
                    );

                    $resultados['valor_futuro'] = round($values['valor_futuro'], 2);
                    $camposCalculados[] = 'valor_futuro';
                    $messages[] = 'Valor Futuro calculado: $'.number_format($resultados['valor_futuro'], 2);
                    $calculated = true;
                }

                // 3. Calcular Anualidad Inicial desde VP
                if ($values['anualidad_inicial'] === null &&
                    $values['valor_presente'] !== null &&
                    $g !== null &&
                    $rate !== null &&
                    $values['numero_pagos'] !== null) {

                    $values['anualidad_inicial'] = $this->calcularAnualidadDesdeVPGeometrica(
                        $values['valor_presente'],
                        $g,
                        $rate,
                        $values['numero_pagos'],
                        $esAnticipado
                    );

                    $resultados['anualidad_inicial'] = round($values['anualidad_inicial'], 2);
                    $camposCalculados[] = 'anualidad_inicial';
                    $messages[] = 'Anualidad Inicial calculada: $'.number_format($resultados['anualidad_inicial'], 2);
                    $calculated = true;
                }

                // 4. Calcular Anualidad Inicial desde VF
                if ($values['anualidad_inicial'] === null &&
                    $values['valor_futuro'] !== null &&
                    $g !== null &&
                    $rate !== null &&
                    $values['numero_pagos'] !== null) {

                    $values['anualidad_inicial'] = $this->calcularAnualidadDesdeVFGeometrica(
                        $values['valor_futuro'],
                        $g,
                        $rate,
                        $values['numero_pagos'],
                        $esAnticipado
                    );

                    $resultados['anualidad_inicial'] = round($values['anualidad_inicial'], 2);
                    $camposCalculados[] = 'anualidad_inicial';
                    $messages[] = 'Anualidad Inicial calculada: $'.number_format($resultados['anualidad_inicial'], 2);
                    $calculated = true;
                }

                // 5. Calcular Gradiente Geométrico desde VP
                if ($values['gradiente_geometrico'] === null &&
                    $values['valor_presente'] !== null &&
                    $values['anualidad_inicial'] !== null &&
                    $rate !== null &&
                    $values['numero_pagos'] !== null) {

                    $gCalculado = $this->calcularGradienteDesdeVPGeometrica(
                        $values['valor_presente'],
                        $values['anualidad_inicial'],
                        $rate,
                        $values['numero_pagos'],
                        $esAnticipado
                    );

                    if ($gCalculado !== null) {
                        $values['gradiente_geometrico'] = $gCalculado;
                        $g = $gCalculado / 100;
                        $resultados['gradiente_geometrico'] = round($values['gradiente_geometrico'], 4);
                        $camposCalculados[] = 'gradiente_geometrico';
                        $messages[] = 'Gradiente Geométrico calculado: '.$resultados['gradiente_geometrico'].'%';
                        $calculated = true;
                    }
                }

                // 6. Calcular Gradiente Geométrico desde VF
                if ($values['gradiente_geometrico'] === null &&
                    $values['valor_futuro'] !== null &&
                    $values['anualidad_inicial'] !== null &&
                    $rate !== null &&
                    $values['numero_pagos'] !== null) {

                    $gCalculado = $this->calcularGradienteDesdeVFGeometrica(
                        $values['valor_futuro'],
                        $values['anualidad_inicial'],
                        $rate,
                        $values['numero_pagos'],
                        $esAnticipado
                    );

                    if ($gCalculado !== null) {
                        $values['gradiente_geometrico'] = $gCalculado;
                        $g = $gCalculado / 100;
                        $resultados['gradiente_geometrico'] = round($values['gradiente_geometrico'], 4);
                        $camposCalculados[] = 'gradiente_geometrico';
                        $messages[] = 'Gradiente Geométrico calculado: '.$resultados['gradiente_geometrico'].'%';
                        $calculated = true;
                    }
                }

                // 7. Calcular Tasa de interés si falta
                if ($values['tasa_interes'] === null &&
                    $values['anualidad_inicial'] !== null &&
                    $g !== null &&
                    $values['numero_pagos'] !== null &&
                    ($values['valor_presente'] !== null || $values['valor_futuro'] !== null)) {

                    $tasaCalculada = $this->calcularTasaInteresGeometrica($values, $g, $periodicidadTasa, $esAnticipado);

                    if ($tasaCalculada !== null) {
                        $values['tasa_interes'] = $tasaCalculada;
                        $rate = ($values['tasa_interes'] * $periodicidadTasa / 100);

                        $resultados['tasa_interes'] = round($values['tasa_interes'], 4);
                        $camposCalculados[] = 'tasa_interes';
                        $periodicidadTexto = $this->getPeriodicidadTexto($periodicidadTasa);
                        $messages[] = 'Tasa de interés calculada: '.$resultados['tasa_interes'].'% '.$periodicidadTexto;
                        $calculated = true;
                    }
                }

                // 8. Calcular Número de pagos si falta
                if ($values['numero_pagos'] === null &&
                    $values['anualidad_inicial'] !== null &&
                    $g !== null &&
                    $rate !== null &&
                    ($values['valor_presente'] !== null || $values['valor_futuro'] !== null)) {

                    $nCalculado = $this->calcularNumeroPagosGeometrica($values, $rate, $g, $esAnticipado);

                    if ($nCalculado !== null) {
                        $values['numero_pagos'] = $nCalculado;
                        $resultados['numero_pagos'] = (int) $values['numero_pagos'];
                        $camposCalculados[] = 'numero_pagos';
                        $messages[] = 'Número de pagos calculado: '.$resultados['numero_pagos'];
                        $calculated = true;
                    }
                }
            }

            // Calcular anualidad final y total de pagos
            if ($values['anualidad_inicial'] !== null && $g !== null && $values['numero_pagos'] !== null) {
                $resultados['anualidad_final'] = round($values['anualidad_inicial'] * pow(1 + $g, $values['numero_pagos'] - 1), 2);

                // Calcular total de pagos sumando todos los períodos
                $totalPagos = 0;
                for ($i = 0; $i < $values['numero_pagos']; $i++) {
                    $totalPagos += $values['anualidad_inicial'] * pow(1 + $g, $i);
                }
                $resultados['total_pagos'] = round($totalPagos, 2);
            }

            // Calcular interés generado
            if (isset($resultados['total_pagos'])) {
                if (isset($values['valor_presente'])) {
                    $resultados['interes_generadoVP'] = round($resultados['total_pagos'] - $values['valor_presente'], 2);
                    $messages[] = 'Interés generado calculado desde VP: $'.number_format($resultados['interes_generadoVP'], 2);
                }
                if (isset($values['valor_futuro'])) {
                    $resultados['interes_generadoVF'] = round($values['valor_futuro'] - $resultados['total_pagos'], 2);
                    $messages[] = 'Interés generado calculado desde VF: $'.number_format($resultados['interes_generadoVF'], 2);
                }
            }

            // Agregar valores no calculados a resultados
            foreach ($values as $key => $value) {
                if ($value !== null && ! isset($resultados[$key])) {
                    $resultados[$key] = is_float($value) ? round($value, 2) : $value;
                }
            }

            // Generar tabla de gradiente
            $tablaGradiente = $this->generarTablaGradienteGeometrico($values, $rate, $g, $esAnticipado);

        } catch (\Throwable $e) {
            return [
                'error' => true,
                'message' => 'Error en cálculo: '.$e->getMessage(),
            ];
        }

        return [
            'error' => false,
            'data' => array_merge($data, [
                'campos_calculados' => json_encode($camposCalculados),
                'resultados_calculados' => json_encode($resultados),
                'tabla_gradiente' => json_encode($tablaGradiente),
                'mensaje_calculado' => implode('. ', $messages),
            ]),
            'message' => implode('. ', $messages),
        ];
    }

    // =========================================================================
    // MÉTODOS DE CÁLCULO PARA GRADIENTE ARITMÉTICO (ANTICIPADO Y VENCIDO)
    // =========================================================================

    private function calcularVPDesdeAnualidadAritmetica(float $anualidad, float $gradiente, float $rate, int $n, bool $esAnticipado): float
    {
        if ($rate == 0) {
            $vp = $anualidad * $n + $gradiente * (($n * ($n - 1)) / 2);
        } else {
            $factorA = (1 - pow(1 + $rate, -$n)) / $rate;
            $factorG = ((1 - pow(1 + $rate, -$n)) / ($rate * $rate)) - ($n / ($rate * pow(1 + $rate, $n)));
            $vp = $anualidad * $factorA + $gradiente * $factorG;
        }

        // Ajustar para anticipado
        if ($esAnticipado) {
            $vp *= (1 + $rate);
        }

        return $vp;
    }

    private function calcularVFDesdeAnualidadAritmetica(float $anualidad, float $gradiente, float $rate, int $n, bool $esAnticipado): float
    {
        if ($rate == 0) {
            $vf = $anualidad * $n + $gradiente * (($n * ($n - 1)) / 2);
        } else {
            $factorA = (pow(1 + $rate, $n) - 1) / $rate;
            $factorG = ((pow(1 + $rate, $n) - 1) / ($rate * $rate)) - ($n / $rate);
            $vf = $anualidad * $factorA + $gradiente * $factorG;
        }

        // Ajustar para anticipado
        if ($esAnticipado) {
            $vf *= (1 + $rate);
        }

        return $vf;
    }

    private function calcularAnualidadDesdeVPAritmetica(float $vp, float $gradiente, float $rate, int $n, bool $esAnticipado): float
    {
        // Ajustar VP para anticipado
        $vpAjustado = $esAnticipado ? $vp / (1 + $rate) : $vp;

        if ($rate == 0) {
            $gradienteComponent = $gradiente * (($n * ($n - 1)) / 2);

            return ($vpAjustado - $gradienteComponent) / $n;
        }

        $factorA = (1 - pow(1 + $rate, -$n)) / $rate;
        $factorG = ((1 - pow(1 + $rate, -$n)) / ($rate * $rate)) - ($n / ($rate * pow(1 + $rate, $n)));

        return ($vpAjustado - $gradiente * $factorG) / $factorA;
    }

    private function calcularAnualidadDesdeVFAritmetica(float $vf, float $gradiente, float $rate, int $n, bool $esAnticipado): float
    {
        // Ajustar VF para anticipado
        $vfAjustado = $esAnticipado ? $vf / (1 + $rate) : $vf;

        if ($rate == 0) {
            $gradienteComponent = $gradiente * (($n * ($n - 1)) / 2);

            return ($vfAjustado - $gradienteComponent) / $n;
        }

        $factorA = (pow(1 + $rate, $n) - 1) / $rate;
        $factorG = ((pow(1 + $rate, $n) - 1) / ($rate * $rate)) - ($n / $rate);

        return ($vfAjustado - $gradiente * $factorG) / $factorA;
    }

    private function calcularGradienteDesdeVPAritmetica(float $vp, float $anualidad, float $rate, int $n, bool $esAnticipado): float
    {
        // Ajustar VP para anticipado
        $vpAjustado = $esAnticipado ? $vp / (1 + $rate) : $vp;

        if ($rate == 0) {
            $anualidadComponent = $anualidad * $n;

            return ($vpAjustado - $anualidadComponent) / (($n * ($n - 1)) / 2);
        }

        $factorA = (1 - pow(1 + $rate, -$n)) / $rate;
        $factorG = ((1 - pow(1 + $rate, -$n)) / ($rate * $rate)) - ($n / ($rate * pow(1 + $rate, $n)));

        return ($vpAjustado - $anualidad * $factorA) / $factorG;
    }

    private function calcularGradienteDesdeVFAritmetica(float $vf, float $anualidad, float $rate, int $n, bool $esAnticipado): float
    {
        // Ajustar VF para anticipado
        $vfAjustado = $esAnticipado ? $vf / (1 + $rate) : $vf;

        if ($rate == 0) {
            $anualidadComponent = $anualidad * $n;

            return ($vfAjustado - $anualidadComponent) / (($n * ($n - 1)) / 2);
        }

        $factorA = (pow(1 + $rate, $n) - 1) / $rate;
        $factorG = ((pow(1 + $rate, $n) - 1) / ($rate * $rate)) - ($n / $rate);

        return ($vfAjustado - $anualidad * $factorA) / $factorG;
    }

    private function calcularTasaInteresAritmetica(array $values, int $periodicidadTasa, bool $esAnticipado): ?float
    {
        $r = 0.05 / $periodicidadTasa; // Estimación inicial (5% anual convertido a periodicidad)
        $maxIter = 200;
        $tol = 1e-10;
        $found = false;

        if ($values['valor_presente'] !== null) {
            // Iteración usando VP
            for ($i = 0; $i < $maxIter; $i++) {
                if (abs($r) < 1e-12) {
                    $r = 1e-8;
                }

                $vpCalculado = $this->calcularVPDesdeAnualidadAritmetica(
                    $values['anualidad_inicial'],
                    $values['gradiente_aritmetico'],
                    $r,
                    $values['numero_pagos'],
                    $esAnticipado
                );

                $f = $vpCalculado - $values['valor_presente'];

                // Derivada numérica
                $r1 = $r * 1.0001;
                $vp1 = $this->calcularVPDesdeAnualidadAritmetica(
                    $values['anualidad_inicial'],
                    $values['gradiente_aritmetico'],
                    $r1,
                    $values['numero_pagos'],
                    $esAnticipado
                );

                $fprime = ($vp1 - $vpCalculado) / ($r1 - $r);

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
        } elseif ($values['valor_futuro'] !== null) {
            // Iteración usando VF
            for ($i = 0; $i < $maxIter; $i++) {
                if (abs($r) < 1e-12) {
                    $r = 1e-8;
                }

                $vfCalculado = $this->calcularVFDesdeAnualidadAritmetica(
                    $values['anualidad_inicial'],
                    $values['gradiente_aritmetico'],
                    $r,
                    $values['numero_pagos'],
                    $esAnticipado
                );

                $f = $vfCalculado - $values['valor_futuro'];

                // Derivada numérica
                $r1 = $r * 1.0001;
                $vf1 = $this->calcularVFDesdeAnualidadAritmetica(
                    $values['anualidad_inicial'],
                    $values['gradiente_aritmetico'],
                    $r1,
                    $values['numero_pagos'],
                    $esAnticipado
                );

                $fprime = ($vf1 - $vfCalculado) / ($r1 - $r);

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
        }

        if ($found && $r > -0.99 && $r < 10) {
            // Convertir tasa por periodo a tasa nominal según periodicidad
            return $r * 100;
        }

        return null;
    }

    private function calcularNumeroPagosAritmetica(array $values, float $rate, bool $esAnticipado): ?int
    {
        $tol = 1e-3;

        if ($values['valor_presente'] !== null) {
            for ($n = 1; $n <= 1000; $n++) {
                $vpCalculado = $this->calcularVPDesdeAnualidadAritmetica(
                    $values['anualidad_inicial'],
                    $values['gradiente_aritmetico'],
                    $rate,
                    $n,
                    $esAnticipado
                );

                if (abs($vpCalculado - $values['valor_presente']) < $tol) {
                    return $n;
                }

                // Si nos pasamos, retornar el valor más cercano
                if ($n > 1) {
                    $vpAnterior = $this->calcularVPDesdeAnualidadAritmetica(
                        $values['anualidad_inicial'],
                        $values['gradiente_aritmetico'],
                        $rate,
                        $n - 1,
                        $esAnticipado
                    );

                    if (($vpAnterior < $values['valor_presente'] && $vpCalculado > $values['valor_presente']) ||
                        ($vpAnterior > $values['valor_presente'] && $vpCalculado < $values['valor_presente'])) {
                        return abs($vpCalculado - $values['valor_presente']) < abs($vpAnterior - $values['valor_presente']) ? $n : ($n - 1);
                    }
                }
            }
        } elseif ($values['valor_futuro'] !== null) {
            for ($n = 1; $n <= 1000; $n++) {
                $vfCalculado = $this->calcularVFDesdeAnualidadAritmetica(
                    $values['anualidad_inicial'],
                    $values['gradiente_aritmetico'],
                    $rate,
                    $n,
                    $esAnticipado
                );

                if (abs($vfCalculado - $values['valor_futuro']) < $tol) {
                    return $n;
                }

                // Si nos pasamos, retornar el valor más cercano
                if ($n > 1) {
                    $vfAnterior = $this->calcularVFDesdeAnualidadAritmetica(
                        $values['anualidad_inicial'],
                        $values['gradiente_aritmetico'],
                        $rate,
                        $n - 1,
                        $esAnticipado
                    );

                    if (($vfAnterior < $values['valor_futuro'] && $vfCalculado > $values['valor_futuro']) ||
                        ($vfAnterior > $values['valor_futuro'] && $vfCalculado < $values['valor_futuro'])) {
                        return abs($vfCalculado - $values['valor_futuro']) < abs($vfAnterior - $values['valor_futuro']) ? $n : ($n - 1);
                    }
                }
            }
        }

        return null;
    }

    // =========================================================================
    // MÉTODOS DE CÁLCULO PARA GRADIENTE GEOMÉTRICO (ANTICIPADO Y VENCIDO)
    // =========================================================================

    private function calcularVPDesdeAnualidadGeometrica(float $anualidad, float $g, float $rate, int $n, bool $esAnticipado): float
    {
        if (abs($rate - $g) < 1e-10) {
            // Caso especial: tasa igual a gradiente
            $vp = $anualidad * $n / (1 + $rate);
        } else {
            // Fórmula general
            $factor = (1 - pow((1 + $g) / (1 + $rate), $n)) / ($rate - $g);
            $vp = $anualidad * $factor;
        }

        // Ajustar para anticipado
        if ($esAnticipado) {
            $vp *= (1 + $rate);
        }

        return $vp;
    }

    private function calcularVFDesdeAnualidadGeometrica(float $anualidad, float $g, float $rate, int $n, bool $esAnticipado): float
    {
        if (abs($rate - $g) < 1e-10) {
            // Caso especial: tasa igual a gradiente
            $vf = $anualidad * $n * pow(1 + $rate, $n - 1);
        } else {
            // Fórmula general
            $factor = (pow(1 + $rate, $n) - pow(1 + $g, $n)) / ($rate - $g);
            $vf = $anualidad * $factor;
        }

        // Ajustar para anticipado
        if ($esAnticipado) {
            $vf *= (1 + $rate);
        }

        return $vf;
    }

    private function calcularAnualidadDesdeVPGeometrica(float $vp, float $g, float $rate, int $n, bool $esAnticipado): float
    {
        // Ajustar VP para anticipado
        $vpAjustado = $esAnticipado ? $vp / (1 + $rate) : $vp;

        if (abs($rate - $g) < 1e-10) {
            return $vpAjustado * (1 + $rate) / $n;
        }

        $factor = (1 - pow((1 + $g) / (1 + $rate), $n)) / ($rate - $g);

        return $vpAjustado / $factor;
    }

    private function calcularAnualidadDesdeVFGeometrica(float $vf, float $g, float $rate, int $n, bool $esAnticipado): float
    {
        // Ajustar VF para anticipado
        $vfAjustado = $esAnticipado ? $vf / (1 + $rate) : $vf;

        if (abs($rate - $g) < 1e-10) {
            return $vfAjustado / ($n * pow(1 + $rate, $n - 1));
        }

        $factor = (pow(1 + $rate, $n) - pow(1 + $g, $n)) / ($rate - $g);

        return $vfAjustado / $factor;
    }

    private function calcularGradienteDesdeVPGeometrica(float $vp, float $anualidad, float $rate, int $n, bool $esAnticipado): ?float
    {
        // Ajustar VP para anticipado
        $vpAjustado = $esAnticipado ? $vp / (1 + $rate) : $vp;

        // Método de bisección para encontrar g
        $g_low = -0.99;
        $g_high = 2.0;
        $tolerance = 1e-6;
        $maxIter = 100;

        for ($i = 0; $i < $maxIter; $i++) {
            $g_mid = ($g_low + $g_high) / 2;

            $vp_calculated = $this->calcularVPDesdeAnualidadGeometrica($anualidad, $g_mid, $rate, $n, false);
            $diff = $vp_calculated - $vpAjustado;

            if (abs($diff) < $tolerance) {
                return $g_mid * 100;
            }

            if ($diff > 0) {
                $g_high = $g_mid;
            } else {
                $g_low = $g_mid;
            }
        }

        return null;
    }

    private function calcularGradienteDesdeVFGeometrica(float $vf, float $anualidad, float $rate, int $n, bool $esAnticipado): ?float
    {
        // Ajustar VF para anticipado
        $vfAjustado = $esAnticipado ? $vf / (1 + $rate) : $vf;

        // Método de bisección para encontrar g
        $g_low = -0.99;
        $g_high = 2.0;
        $tolerance = 1e-6;
        $maxIter = 100;

        for ($i = 0; $i < $maxIter; $i++) {
            $g_mid = ($g_low + $g_high) / 2;

            $vf_calculated = $this->calcularVFDesdeAnualidadGeometrica($anualidad, $g_mid, $rate, $n, false);
            $diff = $vf_calculated - $vfAjustado;

            if (abs($diff) < $tolerance) {
                return $g_mid * 100;
            }

            if ($diff > 0) {
                $g_high = $g_mid;
            } else {
                $g_low = $g_mid;
            }
        }

        return null;
    }

    private function calcularTasaInteresGeometrica(array $values, float $g, int $periodicidadTasa, bool $esAnticipado): ?float
    {
        $r = 0.05 / $periodicidadTasa; // Estimación inicial (5% anual convertido a periodicidad)
        $maxIter = 200;
        $tol = 1e-10;
        $found = false;

        if ($values['valor_presente'] !== null) {
            // Iteración usando VP
            for ($i = 0; $i < $maxIter; $i++) {
                if (abs($r) < 1e-12) {
                    $r = 1e-8;
                }

                // Evitar que r sea igual a g
                if (abs($r - $g) < 1e-10) {
                    $r = $g + 1e-8;
                }

                $vpCalculado = $this->calcularVPDesdeAnualidadGeometrica(
                    $values['anualidad_inicial'],
                    $g,
                    $r,
                    $values['numero_pagos'],
                    $esAnticipado
                );

                $f = $vpCalculado - $values['valor_presente'];

                // Derivada numérica
                $r1 = $r * 1.0001;
                if (abs($r1 - $g) < 1e-10) {
                    $r1 = $g + 1e-8;
                }

                $vp1 = $this->calcularVPDesdeAnualidadGeometrica(
                    $values['anualidad_inicial'],
                    $g,
                    $r1,
                    $values['numero_pagos'],
                    $esAnticipado
                );

                $fprime = ($vp1 - $vpCalculado) / ($r1 - $r);

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
        } elseif ($values['valor_futuro'] !== null) {
            // Iteración usando VF
            for ($i = 0; $i < $maxIter; $i++) {
                if (abs($r) < 1e-12) {
                    $r = 1e-8;
                }

                // Evitar que r sea igual a g
                if (abs($r - $g) < 1e-10) {
                    $r = $g + 1e-8;
                }

                $vfCalculado = $this->calcularVFDesdeAnualidadGeometrica(
                    $values['anualidad_inicial'],
                    $g,
                    $r,
                    $values['numero_pagos'],
                    $esAnticipado
                );

                $f = $vfCalculado - $values['valor_futuro'];

                // Derivada numérica
                $r1 = $r * 1.0001;
                if (abs($r1 - $g) < 1e-10) {
                    $r1 = $g + 1e-8;
                }

                $vf1 = $this->calcularVFDesdeAnualidadGeometrica(
                    $values['anualidad_inicial'],
                    $g,
                    $r1,
                    $values['numero_pagos'],
                    $esAnticipado
                );

                $fprime = ($vf1 - $vfCalculado) / ($r1 - $r);

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
        }

        if ($found && $r > -0.99 && $r < 10) {
            // Convertir tasa por periodo a tasa nominal según periodicidad
            return $r * 100;
        }

        return null;
    }

    private function calcularNumeroPagosGeometrica(array $values, float $rate, float $g, bool $esAnticipado): ?int
    {
        $tol = 1e-3;

        if ($values['valor_presente'] !== null) {
            for ($n = 1; $n <= 1000; $n++) {
                $vpCalculado = $this->calcularVPDesdeAnualidadGeometrica(
                    $values['anualidad_inicial'],
                    $g,
                    $rate,
                    $n,
                    $esAnticipado
                );

                if (abs($vpCalculado - $values['valor_presente']) < $tol) {
                    return $n;
                }

                // Si nos pasamos, retornar el valor más cercano
                if ($n > 1) {
                    $vpAnterior = $this->calcularVPDesdeAnualidadGeometrica(
                        $values['anualidad_inicial'],
                        $g,
                        $rate,
                        $n - 1,
                        $esAnticipado
                    );

                    if (($vpAnterior < $values['valor_presente'] && $vpCalculado > $values['valor_presente']) ||
                        ($vpAnterior > $values['valor_presente'] && $vpCalculado < $values['valor_presente'])) {
                        return abs($vpCalculado - $values['valor_presente']) < abs($vpAnterior - $values['valor_presente']) ? $n : ($n - 1);
                    }
                }
            }
        } elseif ($values['valor_futuro'] !== null) {
            for ($n = 1; $n <= 1000; $n++) {
                $vfCalculado = $this->calcularVFDesdeAnualidadGeometrica(
                    $values['anualidad_inicial'],
                    $g,
                    $rate,
                    $n,
                    $esAnticipado
                );

                if (abs($vfCalculado - $values['valor_futuro']) < $tol) {
                    return $n;
                }

                // Si nos pasamos, retornar el valor más cercano
                if ($n > 1) {
                    $vfAnterior = $this->calcularVFDesdeAnualidadGeometrica(
                        $values['anualidad_inicial'],
                        $g,
                        $rate,
                        $n - 1,
                        $esAnticipado
                    );

                    if (($vfAnterior < $values['valor_futuro'] && $vfCalculado > $values['valor_futuro']) ||
                        ($vfAnterior > $values['valor_futuro'] && $vfCalculado < $values['valor_futuro'])) {
                        return abs($vfCalculado - $values['valor_futuro']) < abs($vfAnterior - $values['valor_futuro']) ? $n : ($n - 1);
                    }
                }
            }
        }

        return null;
    }

    // =========================================================================
    // MÉTODOS PARA GENERAR TABLAS DE GRADIENTE
    // =========================================================================

    private function generarTablaGradienteAritmetico(array $values, ?float $rate, bool $esAnticipado): array
    {
        if ($values['anualidad_inicial'] === null ||
            $values['gradiente_aritmetico'] === null ||
            $values['numero_pagos'] === null ||
            $rate === null) {
            return [];
        }

        $tabla = [];

        for ($i = 0; $i < $values['numero_pagos']; $i++) {
            $periodo = $i + 1;

            // Calcular pago del período
            $pago = $values['anualidad_inicial'] + ($values['gradiente_aritmetico'] * $i);
            $incremento = $i == 0 ? 0 : $values['gradiente_aritmetico'];

            if ($esAnticipado) {
                // Anticipado: pagos en t=0, 1, 2, ..., n-1
                // VP: traer cada pago desde su momento hasta t=0
                $periodosHastaPresente = $i;
                // VF: llevar cada pago desde su momento hasta t=n (capitaliza 1 período más)
                $periodosHastaFuturo = $values['numero_pagos'] - $i;
            } else {
                // Vencido: pagos en t=1, 2, 3, ..., n
                // VP: traer cada pago desde su momento hasta t=0
                $periodosHastaPresente = $i + 1;
                // VF: llevar cada pago desde su momento hasta t=n
                $periodosHastaFuturo = $values['numero_pagos'] - ($i + 1);
            }

            // Valor presente de este pago
            $valorPresente = $pago / pow(1 + $rate, $periodosHastaPresente);

            // Valor futuro de este pago
            $valorFuturo = $pago * pow(1 + $rate, $periodosHastaFuturo);

            $tabla[] = [
                'periodo' => $periodo,
                'pago' => round($pago, 2),
                'incremento' => round($incremento, 2),
                'valor_presente' => round($valorPresente, 2),
                'valor_futuro' => round($valorFuturo, 2),
            ];
        }

        return $tabla;
    }

    private function generarTablaGradienteGeometrico(array $values, ?float $rate, ?float $g, bool $esAnticipado): array
    {
        if ($values['anualidad_inicial'] === null ||
            $g === null ||
            $values['numero_pagos'] === null ||
            $rate === null) {
            return [];
        }

        $tabla = [];

        for ($i = 0; $i < $values['numero_pagos']; $i++) {
            $periodo = $i + 1;

            // Calcular pago del período
            $pago = $values['anualidad_inicial'] * pow(1 + $g, $i);
            $incrementoPorcentual = $i == 0 ? 0 : $values['gradiente_geometrico'];

            if ($esAnticipado) {
                // Anticipado: pagos en t=0, 1, 2, ..., n-1
                // VP: traer cada pago desde su momento hasta t=0
                $periodosHastaPresente = $i;
                // VF: llevar cada pago desde su momento hasta t=n (capitaliza 1 período más)
                $periodosHastaFuturo = $values['numero_pagos'] - $i;
            } else {
                // Vencido: pagos en t=1, 2, 3, ..., n
                // VP: traer cada pago desde su momento hasta t=0
                $periodosHastaPresente = $i + 1;
                // VF: llevar cada pago desde su momento hasta t=n
                $periodosHastaFuturo = $values['numero_pagos'] - ($i + 1);
            }

            // Valor presente de este pago
            $valorPresente = $pago / pow(1 + $rate, $periodosHastaPresente);

            // Valor futuro de este pago
            $valorFuturo = $pago * pow(1 + $rate, $periodosHastaFuturo);

            $tabla[] = [
                'periodo' => $periodo,
                'pago' => round($pago, 2),
                'incremento_porcentual' => round($incrementoPorcentual, 4),
                'valor_presente' => round($valorPresente, 2),
                'valor_futuro' => round($valorFuturo, 2),
            ];
        }

        return $tabla;
    }
}
