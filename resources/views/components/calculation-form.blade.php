<div class="grid grid-cols-12 gap-x-4 mt-4">
            {{-- Calculadora --}}
            <div class="space-y-6 col-span-9 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                <div class="flex items-start space-x-3">
                    <x-heroicon-o-information-circle class="h-5 w-5 text-primary-600 dark:text-primary-400 flex-shrink-0 mt-0.5" />
                    <div class="text-sm">
                        <p class="text-gray-900 dark:text-white font-medium">
                            ¿Cómo usar la calculadora?
                        </p>
                        <p class="text-gray-700 dark:text-gray-300 mt-1">
                            Complete todos los campos conocidos y deje vacío <strong>exactamente uno</strong> que desee calcular.
                        </p>
                    </div>
                </div>
                <form wire:submit="calculate('compuesto')" class="space-y-6">
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
            </div>

            {{-- Explicación de la fórmula --}}
            <div class="bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-4 col-span-3">
                <h3 class="font-medium dark:text-white mb-2 text-primary-700">
                    Fórmula del Interés Compuesto
                </h3>
                <div class="text-sm text-gray-600 dark:text-gray-400 space-y-1">
                    <p><strong>A = P(1 + r/n)^(n×t)</strong></p>
                    <p><strong>A:</strong> Monto final</p>
                    <p><strong>P:</strong> Capital inicial</p>
                    <p><strong>r:</strong> Tasa de interés anual (decimal)</p>
                    <p><strong>n:</strong> Frecuencia de capitalización por año</p>
                    <p><strong>t:</strong> Tiempo en años</p>
                </div>
            </div>
        </div>
