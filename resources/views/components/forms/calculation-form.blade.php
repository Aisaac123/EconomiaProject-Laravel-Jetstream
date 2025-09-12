@props(['calculationType'])

<form wire:submit.prevent="formSubmit('{{ $calculationType }}')" class="space-y-6">
    <div class="flex items-start space-x-3">
        <x-heroicon-o-information-circle class="h-5 w-5 text-primary-600 dark:text-primary-400 flex-shrink-0 mt-0.5" />
        <div class="text-sm">
            <p class="text-gray-900 dark:text-white font-medium">
                ¿Cómo usar la calculadora?
            </p>
            <p class="text-gray-700 dark:text-gray-300 mt-1">
                Complete todos los campos conocidos<strong> y deje vacío</strong> los campos que desee calcular.
            </p>
        </div>
    </div>

    {{ $this->form }}

    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <div class="flex justify-between items-center">
            <x-filament::button
                wire:click="limpiar"
                color="gray"
                outlined
                icon="heroicon-o-arrow-path"
            >
                Limpiar
            </x-filament::button>

            <x-filament::button
                type="submit"
                color="primary"
                class="text-white"
            >
                <x-slot:icon>
                    <x-heroicon-o-calculator class="size-5 text-white" />
                </x-slot:icon>
                Calcular
            </x-filament::button>
        </div>
    </div>
</form>
