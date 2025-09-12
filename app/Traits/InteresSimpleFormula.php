<?php

namespace App\Traits;

trait InteresSimpleFormula
{
    use HelpersFormula;
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
}
