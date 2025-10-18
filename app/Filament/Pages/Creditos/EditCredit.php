<?php

namespace App\Filament\Pages\Creditos;

use App\Enums\CalculationType;
use App\Enums\CreditStatusType;
use App\Enums\PageGroupType;
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

class EditCredit extends Page implements HasForms
{
    use FormCalculations;
    use InteractsWithForms;

    protected string $view = 'filament.pages.creditos.create'; // ♻️ Reutiliza la vista

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $slug = 'creditos/editar/{recordId}';

    protected static string|null|\BackedEnum $navigationIcon = 'heroicon-o-pencil-square';

    protected static string|null|\BackedEnum $activeNavigationIcon = 'heroicon-s-pencil-square';

    protected static string|null|\UnitEnum $navigationGroup = PageGroupType::CREDIT->value;

    public ?Credit $credit = null;

    public string $calculationType = 'simple';

    public array $creditInputs = []; // Usamos esto como el "data" del form

    public string $debtorNames = '';

    public string $debtorLastNames = '';

    public string $debtorIdNumber = '';

    public static function getNavigationSort(): ?int
    {
        return 5;
    }

    public static function getNavigationLabel(): string
    {
        return 'Editar crédito';
    }

    public function getHeading(): Htmlable|string
    {
        return '';
    }

    public function mount(int $recordId): void
    {
        $this->credit = Credit::findOrFail($recordId);
        $credit = $this->credit;

        // Cargar datos del crédito
        $this->debtorNames = $credit->debtor_names;
        $this->debtorLastNames = $credit->debtor_last_names;
        $this->debtorIdNumber = $credit->debtor_id_number;
        $this->calculationType = $credit->type->value;
        $this->data = $credit->inputs ?? [];

        // Llenar formulario con los datos previos
        $this->form->fill($this->data);
    }

    public function form(Schema $schema): Schema
    {
        $schemaClass = match ($this->calculationType) {
            'compuesto' => \App\Filament\Schemas\InteresCompuestoSchema::class,
            'anualidad' => \App\Filament\Schemas\AnualidadSchema::class,
            'amortizacion' => \App\Filament\Schemas\AmortizacionSchema::class,
            'tir' => \App\Filament\Schemas\TasaInternaRetornoSchema::class,
            'gradientes' => \App\Filament\Schemas\GradientesSchema::class,
            default => \App\Filament\Schemas\InteresSimpleSchema::class,
        };

        return $schemaClass::configure($schema, showUpdateButton: true)
            ->statePath('creditInputs')
            ->operation('update');
    }

    public function updatedCalculationType(): void
    {
        $this->creditInputs = [];
        $this->form->fill();
        $this->dispatch('$refresh');
    }

    public function formSubmit(): void
    {
        $calculationTypeEnum = CalculationType::from($this->calculationType);
        $formData = $this->form->getState();

        // Recalcular si se desea
        $result = $this->calculate($calculationTypeEnum, $formData);

        if ($result['error']) {
            Notification::make()
                ->title('Error de validación')
                ->danger()
                ->body($result['message'])
                ->send();

            return;
        }

        $this->creditInputs = array_merge($this->creditInputs, $result['data']);
        $this->form->fill($this->creditInputs);

        Notification::make()
            ->title('¡Cálculo actualizado!')
            ->success()
            ->body($result['message'])
            ->send();
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

    public function updateCredito()
    {
        if (! $this->credit) {
            Notification::make()
                ->title('Error')
                ->body('No se encontró el crédito a editar.')
                ->danger()
                ->send();

            return;
        }

        // Validaciones básicas
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

        if (! empty($validationErrors)) {
            Notification::make()
                ->title('Datos del deudor incompletos')
                ->body(implode('<br>', $validationErrors))
                ->warning()
                ->send();

            return;
        }

        try {
            $this->credit->update([
                'debtor_names' => trim($this->debtorNames),
                'debtor_last_names' => trim($this->debtorLastNames),
                'debtor_id_number' => trim($this->debtorIdNumber),
                'type' => CalculationType::from($this->calculationType),
                'inputs' => $this->creditInputs,
                'status' => CreditStatusType::CALCULATED_UPDATED->value,
                'calculated_at' => now(),
            ]);

            Notification::make()
                ->title('Crédito actualizado correctamente')
                ->success()
                ->send();

        } catch (\Exception $e) {
            Notification::make()
                ->title('Error al actualizar')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function limpiar(): void
    {
        $this->creditInputs = [];
        $this->form->fill($this->creditInputs);
    }

    public function getMaxContentWidth(): Width
    {
        return Width::ScreenTwoExtraLarge;
    }

    public function getFooter(): ?View
    {
        return view('filament.settings.custom-footer');
    }
}
