<?php

namespace App\Traits;

use App\Enums\CalculationType;
use Filament\Notifications\Notification;

trait FormCalculations
{
    /**
     * Public Properties
     */
    public array $result = [];

    /**
     * Calculation Methods
     */
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
                $result = number_format(($rateCalc * 100) / $periodicidadTasa, 2);
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

        return $this->calculateResponse($finalAmount, $data, $result, $message, $field);
    }

    private function calculateInteresSimple(array $data): array
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
        $periodicidadTasa = $data['periodicidad_tasa'] ?? 1;

        // Convertir la tasa a anual si es necesario
        $rate = null;
        if (! empty($data['tasa_interes'])) {
            $tasaAnual = $data['tasa_interes'];
            if ($periodicidadTasa != 1) {
                $tasaAnual = $data['tasa_interes'] / $periodicidadTasa;
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
                $result = ($rateCalc * 100) * $periodicidadTasa;
                $periodicidadTexto = $this->getPeriodicidadTexto($periodicidadTasa);
                $message = 'Tasa de interés requerida: '.number_format($result, 4).'% '.$periodicidadTexto;
                break;

            case 'tiempo':
                $result = (($data['monto_final'] / $data['capital']) - 1) / $rate;
                $message = 'Tiempo requerido: '.number_format($result, 2).' años';
                break;
        }

        $finalAmount = $data['monto_final'] ?? $result;
        if ($field === 'monto_final') {
            $finalAmount = $result;
        }

        return $this->calculateResponse($finalAmount, $data, $result, $message, $field);
    }

    private function calculateAnualidad(array $data): array
    {
        $emptyFields = [];
        $camposCalculados = [];
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

        $periodicidadTasa = $data['periodicidad_tasa'] ?? 1;
        $rate = null;
        if (! empty($data['tasa_interes'])) {
            $tasaAnual = $data['tasa_interes'];
            if ($periodicidadTasa != 1) {
                $tasaAnual = $data['tasa_interes'] / $periodicidadTasa;
            }
            $rate = $tasaAnual / 100;
        }

        $calc = $data;

        try {
            // Calcular según qué campos faltan
            if (in_array('valor_presente', $emptyFields) && ! empty($calc['pago_periodico']) && $rate !== null && ! empty($calc['numero_pagos'])) {
                // VP = PMT × [(1 - (1 + r)^-n) / r]
                $vp = $calc['pago_periodico'] * ((1 - pow(1 + $rate, -$calc['numero_pagos'])) / $rate);
                $calc['valor_presente'] = round($vp, 2);
                $camposCalculados[] = 'valor_presente';
                $messages[] = 'Valor Presente calculado: $'.number_format($calc['valor_presente'], 2);
            }

            if (in_array('valor_futuro', $emptyFields) && ! empty($calc['pago_periodico']) && $rate !== null && ! empty($calc['numero_pagos'])) {
                // VF = PMT × [((1 + r)^n - 1) / r]
                $vf = $calc['pago_periodico'] * ((pow(1 + $rate, $calc['numero_pagos']) - 1) / $rate);
                $calc['valor_futuro'] = round($vf, 2);
                $camposCalculados[] = 'valor_futuro';
                $messages[] = 'Valor Futuro calculado: $'.number_format($calc['valor_futuro'], 2);
            }

            if (in_array('pago_periodico', $emptyFields) && ! empty($calc['valor_presente']) && $rate !== null && ! empty($calc['numero_pagos'])) {
                // PMT = VP × [r / (1 - (1+r)^-n)]
                $pmt = $calc['valor_presente'] * ($rate / (1 - pow(1 + $rate, -$calc['numero_pagos'])));
                $calc['pago_periodico'] = round($pmt, 2);
                $camposCalculados[] = 'pago_periodico';
                $messages[] = 'Pago Periódico calculado (desde VP): $'.number_format($calc['pago_periodico'], 2);
            }

            if (in_array('pago_periodico', $emptyFields) && ! empty($calc['valor_futuro']) && $rate !== null && ! empty($calc['numero_pagos'])) {
                // PMT = VF × [r / ((1+r)^n - 1)]
                $pmt = $calc['valor_futuro'] * ($rate / (pow(1 + $rate, $calc['numero_pagos']) - 1));
                $calc['pago_periodico'] = round($pmt, 2);
                $camposCalculados[] = 'pago_periodico';
                $messages[] = 'Pago Periódico calculado (desde VF): $'.number_format($calc['pago_periodico'], 2);
            }

            if (in_array('numero_pagos', $emptyFields) && ! empty($calc['valor_futuro']) && ! empty($calc['pago_periodico']) && $rate !== null) {
                // n = log(VF*r/PMT + 1) / log(1+r)
                $n = log(($calc['valor_futuro'] * $rate / $calc['pago_periodico']) + 1) / log(1 + $rate);
                $calc['numero_pagos'] = (int) round($n, 0);
                $camposCalculados[] = 'numero_pagos';
                $messages[] = 'Número de pagos calculado: '.$calc['numero_pagos'];
            }

            if (in_array('tasa_interes', $emptyFields) && ! empty($calc['pago_periodico']) && ! empty($calc['numero_pagos'])) {
                $r = 0.05; // Estimación inicial (5%)
                $maxIter = 100;
                $tol = 1e-7;
                $n = $calc['numero_pagos'];
                $pmt = $calc['pago_periodico'];

                // Dependiendo de si tenemos VP o VF, usamos la fórmula correspondiente
                if (! empty($calc['valor_presente'])) {
                    $vp = $calc['valor_presente'];
                    for ($i = 0; $i < $maxIter; $i++) {
                        $f = $pmt * ((1 - pow(1 + $r, -$n)) / $r) - $vp;
                        $fprime = $pmt * ((pow(1 + $r, -$n) * $n / (1 + $r) * $r - (1 - pow(1 + $r, -$n))) / ($r * $r));
                        if (abs($fprime) < 1e-12) {
                            break;
                        }
                        $r_new = $r - $f / $fprime;
                        if (abs($r_new - $r) < $tol) {
                            $r = $r_new;
                            break;
                        }
                        $r = $r_new;
                    }
                    $calc['tasa_interes'] = $this->smartRound(round(($r * 100) * $periodicidadTasa, 4));
                    $camposCalculados[] = 'tasa_interes';
                    $periodicidadTexto = $this->getPeriodicidadTexto($periodicidadTasa);
                    $messages[] = 'Tasa de interés aproximada (VP): '.$calc['tasa_interes'].'% '.$periodicidadTexto;
                } elseif (! empty($calc['valor_futuro'])) {
                    $vf = $calc['valor_futuro'];
                    for ($i = 0; $i < $maxIter; $i++) {
                        $f = $pmt * ((pow(1 + $r, $n) - 1) / $r) - $vf;
                        $fprime = $pmt * (($n * pow(1 + $r, $n - 1) * $r - (pow(1 + $r, $n) - 1)) / ($r * $r));
                        if (abs($fprime) < 1e-12) {
                            break;
                        }
                        $r_new = $r - $f / $fprime;
                        if (abs($r_new - $r) < $tol) {
                            $r = $r_new;
                            break;
                        }
                        $r = $r_new;
                    }
                    $calc['tasa_interes'] = $this->smartRound(round(($r * 100) * $periodicidadTasa, 4));
                    $camposCalculados[] = 'tasa_interes';
                    $periodicidadTexto = $this->getPeriodicidadTexto($periodicidadTasa);
                    $messages[] = 'Tasa de interés aproximada (VF): '.$calc['tasa_interes'].'% '.$periodicidadTexto;
                }
            }

        } catch (\Throwable $e) {
            return [
                'error' => true,
                'message' => 'Error en cálculo: '.$e->getMessage(),
            ];
        }

        return [
            'error' => false,
            'data' => array_merge($calc, [
                'resultado' => implode(' | ', $messages),
                'campos_calculados' => json_encode($camposCalculados),
                'mensaje' => implode('. ', $messages),
            ]),
            'message' => implode('. ', $messages),
        ];
    }

    /**
     * Helping Methods
     */
    private function calculate(CalculationType $calculationType, array $formData): array
    {
        return match ($calculationType) {
            CalculationType::ANUALIDAD => $this->calculateAnualidad($formData),
            CalculationType::SIMPLE => $this->calculateInteresSimple($formData),
            CalculationType::COMPUESTO => $this->calculateInteresCompuesto($formData),
            CalculationType::TASA_INTERES => throw new \Exception('Por implementar'),
        };
    }

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

        // NO asignar el campo calculado directamente
        // En su lugar, usar campos ocultos para almacenar los resultados

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

    private function smartRound(float $value): float
    {
        // Si está casi en un entero (±0.01), devolver como entero
        if (abs($value - round($value)) < 0.01) {
            return round($value);
        }

        // Si el segundo decimal es 0 ó >= 5 → 1 decimal
        $oneDecimal = round($value, 1);
        if (abs($value - $oneDecimal) < 0.05) {
            return $oneDecimal;
        }

        // En otros casos → 2 decimales
        return round($value, 2);
    }

    /**
     * Exposed Methods
     */
    public function formSubmit(CalculationType $calculationType): void
    {
        $formData = $this->form->getState();

        // Si se están usando fechas para calcular tiempo, verificar que las fechas estén presentes
        if (! empty($formData['usar_fechas_tiempo']) && $formData['usar_fechas_tiempo']) {
            if (empty($formData['fecha_inicio']) || empty($formData['fecha_final'])) {
                Notification::make()
                    ->title('Error de validación')
                    ->danger()
                    ->body('Debe seleccionar tanto la fecha de inicio como la fecha final para calcular el tiempo.')
                    ->send();

                return;
            }

            // Calcular tiempo desde fechas si no está presente
            if (empty($formData['tiempo'])) {
                $inicio = \Carbon\Carbon::parse($formData['fecha_inicio']);
                $final = \Carbon\Carbon::parse($formData['fecha_final']);

                if ($final->gt($inicio)) {
                    $tiempoEnAnios = $inicio->diffInDays($final) / 365.25;
                    $formData['tiempo'] = round($tiempoEnAnios, 4);
                }
            }
        }

        $result = $this->calculate($calculationType, $formData);

        if ($result['error']) {
            Notification::make()
                ->title('Error de validación')
                ->danger()
                ->body($result['message'])
                ->send();

            return;
        }

        // Solo llenar con los campos ocultos de resultado, NO con el campo calculado
        $this->form->fill($result['data']);

        Notification::make()
            ->title('¡Cálculo completado!')
            ->success()
            ->body($result['message'])
            ->send();
    }

    public function limpiar(): void
    {
        $this->form->fill([]);
        $this->form->fill([
            'usar_select_frecuencia' => true,
            'usar_select_periodicidad_tasa' => true,
            'periodicidad_tasa' => 1,
            'frecuencia' => 1,
        ]);

        Notification::make()
            ->title('Formulario limpiado')
            ->info()
            ->send();
    }
}
