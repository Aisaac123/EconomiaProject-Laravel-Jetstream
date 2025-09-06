<?php

namespace App\Traits;

use App\Enums\CalculationType;
use Filament\Notifications\Notification;

trait FormCalculations
{
    public function calculateInteresCompuesto(): void
    {
        try {
            $data = $this->form->getState();

            // Contar campos vacíos (excluyendo resultado y frecuencia que tiene default)
            $camposVacios = 0;
            $campoVacio = '';

            if (empty($data['capital'])) {
                $camposVacios++;
                $campoVacio = 'capital';
            }
            if (empty($data['monto_final'])) {
                $camposVacios++;
                $campoVacio = 'monto_final';
            }
            if (empty($data['tasa_interes'])) {
                $camposVacios++;
                $campoVacio = 'tasa_interes';
            }
            if (empty($data['tiempo'])) {
                $camposVacios++;
                $campoVacio = 'tiempo';
            }

            // Validar que solo haya un campo vacío
            if ($camposVacios === 0) {
                Notification::make()
                    ->title('Error de validación')
                    ->danger()
                    ->body('Debe dejar vacío exactamente un campo para que sea calculado.')
                    ->send();

                return;
            }

            if ($camposVacios > 1) {
                Notification::make()
                    ->title('Error de validación')
                    ->danger()
                    ->body('Solo puede dejar vacío un campo. Actualmente hay '.$camposVacios.' campos vacíos.')
                    ->send();

                return;
            }

            // Valores por defecto
            $frecuencia = $data['frecuencia'] ?? 12; // Mensual por defecto

            // Convertir porcentaje a decimal
            $tasaDecimal = ! empty($data['tasa_interes']) ? $data['tasa_interes'] / 100 : null;

            $resultado = 0;
            $mensajeResultado = '';

            // Calcular según el campo vacío
            switch ($campoVacio) {
                case 'capital':
                    // P = A / (1 + r/n)^(n*t)
                    $resultado = $data['monto_final'] / pow(1 + ($tasaDecimal / $frecuencia), $frecuencia * $data['tiempo']);
                    $mensajeResultado = 'Capital inicial requerido: $'.number_format($resultado, 2);
                    break;

                case 'monto_final':
                    // A = P(1 + r/n)^(n*t)
                    $resultado = $data['capital'] * pow(1 + ($tasaDecimal / $frecuencia), $frecuencia * $data['tiempo']);
                    $mensajeResultado = 'Monto final obtenido: $'.number_format($resultado, 2);
                    break;

                case 'tasa_interes':
                    // r = n * ((A/P)^(1/(n*t)) - 1)
                    $tasaCalculada = $frecuencia * (pow($data['monto_final'] / $data['capital'], 1 / ($frecuencia * $data['tiempo'])) - 1);
                    $resultado = $tasaCalculada * 100; // Convertir a porcentaje
                    $mensajeResultado = 'Tasa de interés requerida: '.number_format($resultado, 2).'%';
                    break;

                case 'tiempo':
                    // t = ln(A/P) / (n * ln(1 + r/n))
                    $resultado = log($data['monto_final'] / $data['capital']) / ($frecuencia * log(1 + ($tasaDecimal / $frecuencia)));
                    $mensajeResultado = 'Tiempo requerido: '.number_format($resultado, 2).' años';
                    break;
            }

            // Actualizar el formulario con el resultado
            $data['resultado'] = number_format($resultado, 2);
            $this->form->fill($data);

            // Mostrar notificación de éxito
            Notification::make()
                ->title('¡Cálculo completado!')
                ->success()
                ->body($mensajeResultado)
                ->send();

        } catch (\Exception $e) {
            Notification::make()
                ->title('Error en el cálculo')
                ->danger()
                ->body('Verifique que todos los valores sean válidos: ')
                ->send();
        }
    }

    public function calculateInteresSimple(): void
    {
        try {
            $data = $this->form->getState();

            // Contar campos vacíos
            $camposVacios = 0;
            $campoVacio = '';

            if (empty($data['capital'])) {
                $camposVacios++;
                $campoVacio = 'capital';
            }
            if (empty($data['monto_final'])) {
                $camposVacios++;
                $campoVacio = 'monto_final';
            }
            if (empty($data['tasa_interes'])) {
                $camposVacios++;
                $campoVacio = 'tasa_interes';
            }
            if (empty($data['tiempo'])) {
                $camposVacios++;
                $campoVacio = 'tiempo';
            }

            // Validar que solo haya un campo vacío
            if ($camposVacios === 0) {
                Notification::make()
                    ->title('Error de validación')
                    ->danger()
                    ->body('Debe dejar vacío exactamente un campo para que sea calculado.')
                    ->send();

                return;
            }

            if ($camposVacios > 1) {
                Notification::make()
                    ->title('Error de validación')
                    ->danger()
                    ->body('Solo puede dejar vacío un campo. Actualmente hay '.$camposVacios.' campos vacíos.')
                    ->send();

                return;
            }

            // Convertir porcentaje a decimal
            $tasaDecimal = ! empty($data['tasa_interes']) ? $data['tasa_interes'] / 100 : null;

            $resultado = 0;
            $mensajeResultado = '';

            // Calcular según el campo vacío - Fórmula: A = P(1 + r*t)
            switch ($campoVacio) {
                case 'capital':
                    // P = A / (1 + r*t)
                    $resultado = $data['monto_final'] / (1 + ($tasaDecimal * $data['tiempo']));
                    $mensajeResultado = 'Capital inicial requerido: $'.number_format($resultado, 2);
                    break;

                case 'monto_final':
                    // A = P(1 + r*t)
                    $resultado = $data['capital'] * (1 + ($tasaDecimal * $data['tiempo']));
                    $mensajeResultado = 'Monto final obtenido: $'.number_format($resultado, 2);
                    break;

                case 'tasa_interes':
                    // r = (A/P - 1) / t
                    $tasaCalculada = (($data['monto_final'] / $data['capital']) - 1) / $data['tiempo'];
                    $resultado = $tasaCalculada * 100; // Convertir a porcentaje
                    $mensajeResultado = 'Tasa de interés requerida: '.number_format($resultado, 2).'%';
                    break;

                case 'tiempo':
                    // t = (A/P - 1) / r
                    $resultado = (($data['monto_final'] / $data['capital']) - 1) / $tasaDecimal;
                    $mensajeResultado = 'Tiempo requerido: '.number_format($resultado, 2).' años';
                    break;
            }

            // Actualizar el formulario con el resultado
            $data['resultado'] = number_format($resultado, 2);
            $this->form->fill($data);

            // Mostrar notificación de éxito
            Notification::make()
                ->title('¡Cálculo completado!')
                ->success()
                ->body($mensajeResultado)
                ->send();

        } catch (\Exception $e) {
            Notification::make()
                ->title('Error en el cálculo')
                ->danger()
                ->body('Verifique que todos los valores sean válidos: '.$e->getMessage())
                ->send();
        }
    }

    public function calculateAnualidad(): void
    {
        try {
            $data = $this->form->getState();

            // Contar campos vacíos
            $camposVacios = 0;
            $camposVaciosArray = [];

            if (empty($data['pago_periodico'])) {
                $camposVacios++;
                $camposVaciosArray[] = 'pago_periodico';
            }
            if (empty($data['valor_presente'])) {
                $camposVacios++;
                $camposVaciosArray[] = 'valor_presente';
            }
            if (empty($data['valor_futuro'])) {
                $camposVacios++;
                $camposVaciosArray[] = 'valor_futuro';
            }
            if (empty($data['tasa_interes'])) {
                $camposVacios++;
                $camposVaciosArray[] = 'tasa_interes';
            }
            if (empty($data['numero_pagos'])) {
                $camposVacios++;
                $camposVaciosArray[] = 'numero_pagos';
            }

            // Validar que haya exactamente 1 o 2 campos vacíos
            if ($camposVacios === 0) {
                Notification::make()
                    ->title('Error de validación')
                    ->danger()
                    ->body('Debe dejar vacío 1 o 2 campos para que sean calculados.')
                    ->send();

                return;
            }

            if ($camposVacios > 2) {
                Notification::make()
                    ->title('Error de validación')
                    ->danger()
                    ->body('Solo puede dejar vacíos máximo 2 campos. Actualmente hay '.$camposVacios.' campos vacíos.')
                    ->send();

                return;
            }

            // Convertir porcentaje a decimal
            $tasaDecimal = ! empty($data['tasa_interes']) ? $data['tasa_interes'] / 100 : null;

            $mensajes = [];

            // Si solo hay un campo vacío
            if ($camposVacios === 1) {
                $campoVacio = $camposVaciosArray[0];

                switch ($campoVacio) {
                    case 'pago_periodico':
                        if (! empty($data['valor_presente'])) {
                            // PMT desde VP: PMT = PV * [r(1+r)^n] / [(1+r)^n - 1]
                            $factor = pow(1 + $tasaDecimal, $data['numero_pagos']);
                            $resultado = $data['valor_presente'] * ($tasaDecimal * $factor) / ($factor - 1);
                            $data['pago_periodico'] = number_format($resultado, 2);
                            $mensajes[] = 'Pago periódico (desde VP): $'.number_format($resultado, 2);
                        } elseif (! empty($data['valor_futuro'])) {
                            // PMT desde VF: PMT = FV * r / [(1+r)^n - 1]
                            $factor = pow(1 + $tasaDecimal, $data['numero_pagos']);
                            $resultado = $data['valor_futuro'] * $tasaDecimal / ($factor - 1);
                            $data['pago_periodico'] = number_format($resultado, 2);
                            $mensajes[] = 'Pago periódico (desde VF): $'.number_format($resultado, 2);
                        }
                        break;

                    case 'valor_presente':
                        // PV = PMT * [(1+r)^n - 1] / [r(1+r)^n]
                        $factor = pow(1 + $tasaDecimal, $data['numero_pagos']);
                        $resultado = $data['pago_periodico'] * ($factor - 1) / ($tasaDecimal * $factor);
                        $data['valor_presente'] = number_format($resultado, 2);
                        $mensajes[] = 'Valor presente: $'.number_format($resultado, 2);
                        break;

                    case 'valor_futuro':
                        // FV = PMT * [(1+r)^n - 1] / r
                        $factor = pow(1 + $tasaDecimal, $data['numero_pagos']);
                        $resultado = $data['pago_periodico'] * ($factor - 1) / $tasaDecimal;
                        $data['valor_futuro'] = number_format($resultado, 2);
                        $mensajes[] = 'Valor futuro: $'.number_format($resultado, 2);
                        break;

                    case 'numero_pagos':
                        if (! empty($data['valor_presente'])) {
                            // n = ln(1 + (PV * r) / PMT) / ln(1 + r)
                            $resultado = log(1 + ($data['valor_presente'] * $tasaDecimal) / $data['pago_periodico']) / log(1 + $tasaDecimal);
                            $data['numero_pagos'] = number_format($resultado, 0);
                            $mensajes[] = 'Número de pagos: '.number_format($resultado, 0);
                        } elseif (! empty($data['valor_futuro'])) {
                            // n = ln(1 + (FV * r) / PMT) / ln(1 + r)
                            $resultado = log(($data['valor_futuro'] * $tasaDecimal) / $data['pago_periodico'] + 1) / log(1 + $tasaDecimal);
                            $data['numero_pagos'] = number_format($resultado, 0);
                            $mensajes[] = 'Número de pagos: '.number_format($resultado, 0);
                        }
                        break;
                }
            }
            // Si hay dos campos vacíos
            else {
                // Casos comunes de dos campos vacíos
                if (in_array('valor_presente', $camposVaciosArray) && in_array('valor_futuro', $camposVaciosArray)) {
                    // Calcular VP y VF conociendo PMT, tasa y número de pagos
                    $factor = pow(1 + $tasaDecimal, $data['numero_pagos']);

                    // VP = PMT * [(1+r)^n - 1] / [r(1+r)^n]
                    $vp = $data['pago_periodico'] * ($factor - 1) / ($tasaDecimal * $factor);
                    $data['valor_presente'] = number_format($vp, 2);
                    $mensajes[] = 'Valor presente: $'.number_format($vp, 2);

                    // VF = PMT * [(1+r)^n - 1] / r
                    $vf = $data['pago_periodico'] * ($factor - 1) / $tasaDecimal;
                    $data['valor_futuro'] = number_format($vf, 2);
                    $mensajes[] = 'Valor futuro: $'.number_format($vf, 2);
                } elseif (in_array('pago_periodico', $camposVaciosArray) && in_array('numero_pagos', $camposVaciosArray)) {
                    // Este caso requiere más datos específicos o aproximaciones
                    Notification::make()
                        ->title('Cálculo complejo')
                        ->warning()
                        ->body('Este cálculo requiere métodos iterativos. Proporcione más información.')
                        ->send();

                    return;
                } else {
                    // Otros casos pueden requerir métodos más complejos
                    Notification::make()
                        ->title('Combinación no soportada')
                        ->warning()
                        ->body('Esta combinación de campos vacíos no está soportada actualmente.')
                        ->send();

                    return;
                }
            }

            // Actualizar resultado combinado
            $data['resultado'] = implode(' | ', $mensajes);
            $this->form->fill($data);

            // Mostrar notificación de éxito
            Notification::make()
                ->title('¡Cálculo completado!')
                ->success()
                ->body(implode('. ', $mensajes))
                ->send();

        } catch (\Exception $e) {
            Notification::make()
                ->title('Error en el cálculo')
                ->danger()
                ->body('Verifique que todos los valores sean válidos: '.$e->getMessage())
                ->send();
        }
    }

    public function calculate(CalculationType $calculationType)
    {
        if ($calculationType === CalculationType::ANUALIDAD) {
            $this->calculateAnualidad();
        }
        if ($calculationType === CalculationType::SIMPLE) {
            $this->calculateInteresSimple();
        }
        if ($calculationType === CalculationType::COMPUESTO) {
            $this->calculateInteresCompuesto();
        }
    }

    public function limpiar(): void
    {
        $this->form->fill([]);

        Notification::make()
            ->title('Formulario limpiado')
            ->info()
            ->send();
    }
}
