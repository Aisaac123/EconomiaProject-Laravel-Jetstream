<?php

namespace App\Filament\Pages\Creditos;

use App\Enums\CalculationType;
use App\Enums\PageGroupType;
use App\Filament\Schemas\AmortizacionSchema;
use App\Filament\Schemas\AnualidadSchema;
use App\Filament\Schemas\GradientesSchema;
use App\Filament\Schemas\InteresCompuestoSchema;
use App\Filament\Schemas\InteresSimpleSchema;
use App\Filament\Schemas\TasaInternaRetornoSchema;
use App\Models\Credit;
use App\Traits\FormCalculations;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Width;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\View\View;
use Str;

class Create extends Page implements HasForms
{
    use FormCalculations;
    use InteractsWithForms;

    protected string $view = 'filament.pages.creditos.create';

    protected static ?string $slug = 'creditos/registrar';

    protected static string|null|\BackedEnum $navigationIcon = 'heroicon-o-document-plus';

    protected static string|null|\BackedEnum $activeNavigationIcon = 'heroicon-s-document-plus';

    protected static string|null|\UnitEnum $navigationGroup = PageGroupType::CREDIT->value;

    public string $calculationType = 'simple';

    public string $debtorNames = '';

    public string $debtorLastNames = '';

    public string $debtorIdNumber = '';

    public static function getNavigationSort(): ?int
    {
        return 4;
    }

    public static function getNavigationLabel(): string
    {
        return 'Registrar crédito';
    }

    public function getHeading(): Htmlable|string
    {
        return '';
    }

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Schema $schema): Schema
    {
        // Seleccionar el schema según el tipo de cálculo
        $schemaClass = match ($this->calculationType) {
            'compuesto' => InteresCompuestoSchema::class,
            'anualidad' => AnualidadSchema::class,
            'amortizacion' => AmortizacionSchema::class,
            'tir' => TasaInternaRetornoSchema::class,
            'gradientes' => GradientesSchema::class,
            default => InteresSimpleSchema::class,
        };

        return $schemaClass::configure($schema, true)
            ->statePath('data')
            ->operation('calculate');
    }

    public function updatedCalculationType(): void
    {

        // Limpiar los datos del formulario al cambiar de tipo
        $this->data = [];

        // Reiniciar el formulario con el nuevo schema
        $this->form->fill();

        // Forzar el re-render completo del componente
        $this->dispatch('$refresh');
    }

    public function getCalculationTypeOptions(): array
    {
        return [
            'simple' => 'Interés Simple',
            'compuesto' => 'Interés Compuesto',
            'anualidad' => 'Anualidad',
            'amortizacion' => 'Amortización',
            'tir' => 'Tasa Interna de Retorno (TIR)',
            'gradientes' => 'Gradientes',
        ];
    }

    public function formSubmit(): void
    {
        // Convertir el string a enum CalculationType
        $calculationTypeEnum = CalculationType::from($this->calculationType);

        // Obtener los datos del formulario
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

        // Llamar al método calculate del trait
        $result = $this->calculate($calculationTypeEnum, $formData);

        if ($result['error']) {
            Notification::make()
                ->title('Error de validación')
                ->danger()
                ->body($result['message'])
                ->send();

            return;
        }

        // Actualizar $this->data con los resultados
        $this->data = array_merge($this->data, $result['data']);

        // Llenar el formulario con los datos actualizados
        $this->form->fill($this->data);

        Notification::make()
            ->title('¡Cálculo completado!')
            ->success()
            ->body($result['message'])
            ->send();
    }

    public function limpiar(): void
    {
        $this->data = [];
        $this->form->fill($this->data);
    }

    public function getFooter(): ?View
    {
        return view('filament.settings.custom-footer');
    }

    public function getMaxContentWidth(): Width
    {
        return Width::ScreenTwoExtraLarge;
    }

    public function saveCredito()
    {
        $validationErrors = [];

        if (empty(trim($this->debtorNames))) {
            $validationErrors[] = 'El campo Nombres es obligatorio';
        }

        if (empty(trim($this->debtorLastNames))) {
            $validationErrors[] = 'El campo Apellidos es obligatorio';
        }

        if (empty(trim($this->debtorIdNumber))) {
            $validationErrors[] = 'El campo Cédula es obligatorio';
        }

        // Validar formato de cédula (solo números)
        if (! empty($this->debtorIdNumber) && ! ctype_digit($this->debtorIdNumber)) {
            $validationErrors[] = 'La Cédula debe contener solo números';
        }

        // Si hay errores de validación del deudor
        if (! empty($validationErrors)) {
            Notification::make()
                ->title('Datos del deudor incompletos')
                ->body(implode('<br>', $validationErrors))
                ->warning()
                ->send();

            return;
        }

        if ((isset($this->data['resultado_calculado']) &&
                $this->data['resultado_calculado'] !== null) ||
            (isset($this->data['resultados_calculados']) &&
                $this->data['resultados_calculados'] !== null)) {

            try {
                // Generar código de referencia
                $prefix = match ($this->calculationType) {
                    CalculationType::SIMPLE->value => 'IS',
                    CalculationType::COMPUESTO->value => 'IC',
                    CalculationType::ANUALIDAD->value => 'AN',
                    CalculationType::TASA_INTERES->value => 'TI',
                    CalculationType::AMORTIZACION->value => 'AM',
                    CalculationType::CAPITALIZACION->value => 'CP',
                    CalculationType::TIR->value => 'TR',
                    CalculationType::GRADIENTES->value => 'GR',
                };

                $referenceCode = $prefix.'-'.now()->format('Ymd').'-'.strtoupper(Str::random(6));

                // Guardar el crédito
                Credit::create([
                    'user_id' => auth()->id(),
                    'debtor_names' => trim($this->debtorNames),
                    'debtor_last_names' => trim($this->debtorLastNames),
                    'debtor_id_number' => trim($this->debtorIdNumber),
                    'type' => CalculationType::from($this->calculationType),
                    'inputs' => $this->data,
                    'results' => null,
                    'status' => 'calculated',
                    'reference_code' => $referenceCode,
                    'calculated_at' => now(),
                ]);

                Notification::make()
                    ->title('Crédito guardado')
                    ->body('Código: '.$referenceCode)
                    ->success()
                    ->send();

            } catch (\Exception $e) {
                Notification::make()
                    ->title('Error al guardar')
                    ->body($e->getMessage())
                    ->danger()
                    ->send();
            }
        } else {
            Notification::make()
                ->title('Error al guardar')
                ->body('Por favor, primero calcula el resultado antes de guardarlo.')
                ->warning()
                ->send();
        }

    }
}
