<?php

namespace App\Traits;

use App\Enums\CalculationType;
use Filament\Notifications\Notification;

trait FormCalculations
{
    use AnualidadFormula;
    use InteresSimpleFormula;
    use InteresCompuestoFormula;

    /**
     * Public Properties
     */
    public array $result = [];

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
