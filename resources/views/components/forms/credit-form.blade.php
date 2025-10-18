@props(['calculationType'])

<form wire:submit.prevent="formSubmit('{{ $calculationType }}')" class="space-y-6">
    <div class="flex justify-between">
        <div class="flex items-start space-x-3">
            <x-heroicon-o-information-circle class="h-5 w-5 text-primary-600 dark:text-primary-400 flex-shrink-0 mt-0.5" />
            <div class="text-sm">
                <p class="text-gray-900 dark:text-white font-medium">
                    ¿Cómo usar el Sistema de créditos?
                </p>
                <ul class="text-gray-700 dark:text-gray-300 mt-1 list-disc pl-5 space-y-1">
                    <li>Seleccione el modelo.</li>
                    <li>Complete todos los campos conocidos <strong>y deje vacío</strong> los campos que desee calcular.</li>
                    <li>Al final, guarde con el botón Guardar.</li>
                </ul>
            </div>
        </div>
        <div class="min-h-4">
            <x-filament::button
                wire:click="limpiar"
                color="gray"
                icon="heroicon-o-arrow-path"
            >
                Limpiar
            </x-filament::button>
        </div>
    </div>

    {{ $this->form }}
</form>
