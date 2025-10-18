<x-filament-panels::page>
    <div class="space-y-6">
        {{-- 🔹 Título principal --}}
        <x-sections.heading-title
            :title="$credit ? 'Editar Registro de Crédito' : 'Simulación Registro de Crédito'"
            quote=''
            :button-text="$credit ? 'Volver a Créditos' : 'Gestionar Créditos'"
            href="{{ url(\App\Filament\Pages\Creditos\ListCredits::getUrl()) }}"
        >
            <x-slot:icon>
                <x-heroicon-c-calendar-days class="size-16 text-white" aria-hidden="true" />
            </x-slot:icon>
        </x-sections.heading-title>

        {{-- 🔹 Información general --}}
        <x-sections.content title="Información del crédito" :is-collapsible="false" class="grid-cols-12 grid gap-4">
            <div class="lg:col-span-5 col-span-12 " id="registrar" :is-collapsible="false">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-300 dark:border-white/10">
                    <div class="py-6 px-4 min-h-[11.5rem]">
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0">
                                <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-primary-100 dark:bg-primary-500/10">
                                    <x-heroicon-o-calculator class="w-6 h-6 text-primary-600 dark:text-primary-400" />
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-1">
                                    Modelo Financiero
                                </h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                                    Seleccione el modelo de cálculo financiero que desea {{ $credit ? 'actualizar o revisar' : 'realizar' }}
                                </p>
                                <label for="debtor_names" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Modelo <span class="text-red-500">*</span>
                                </label>
                                <div class="">
                                    <x-filament::input.wrapper suffix-icon="heroicon-o-building-library" class="mb-3">
                                        <x-filament::input.select
                                            id="calculation-type"
                                            wire:model.live="calculationType"
                                            class="fi-select-input block w-full border-gray-300 rounded-lg shadow-sm transition duration-75 focus:border-primary-600 focus:ring-1 focus:ring-inset focus:ring-primary-600 disabled:opacity-70 dark:border-white/10 dark:bg-white/5 dark:text-white dark:focus:border-primary-500"
                                        >
                                            @foreach($this->getCalculationTypeOptions() as $value => $label)
                                                <option value="{{ $value }}">{{ $label }}</option>
                                            @endforeach
                                        </x-filament::input.select>
                                    </x-filament::input.wrapper>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 🔹 Datos del cliente --}}
            <div class="lg:col-span-7 col-span-12">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-300 dark:border-white/10">
                    <div class="p-6 min-h-[11.5rem]">
                        <div class="flex items-start gap-4 mb-4">
                            <div class="flex-shrink-0">
                                <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-primary-100 dark:bg-primary-500/10">
                                    <x-heroicon-o-user class="w-6 h-6 text-primary-600 dark:text-primary-400" />
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-1">
                                    Información del Cliente
                                </h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    {{ $credit ? 'Revise o modifique los datos del deudor' : 'Complete los datos personales del deudor o cliente' }}
                                </p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            {{-- Nombres --}}
                            <div>
                                <label for="debtor_names" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Nombres <span class="text-red-500">*</span>
                                </label>
                                <x-filament::input.wrapper prefix-icon="heroicon-o-user">
                                    <x-filament::input
                                        id="debtor_names"
                                        type="text"
                                        wire:model="debtorNames"
                                        placeholder="Ej: Juan Carlos"
                                        :disabled="$credit"
                                        class="fi-input block w-full border-gray-300 rounded-lg shadow-sm transition duration-75 focus:border-primary-600 focus:ring-1 focus:ring-inset focus:ring-primary-600 dark:border-white/10 dark:bg-white/5 dark:text-white"
                                    />
                                </x-filament::input.wrapper>
                            </div>

                            {{-- Apellidos --}}
                            <div>
                                <label for="debtor_last_names" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Apellidos <span class="text-red-500">*</span>
                                </label>
                                <x-filament::input.wrapper prefix-icon="heroicon-o-user">
                                    <x-filament::input
                                        id="debtor_last_names"
                                        type="text"
                                        wire:model="debtorLastNames"
                                        placeholder="Ej: Pérez García"
                                        :disabled="$credit"
                                        class="fi-input block w-full border-gray-300 rounded-lg shadow-sm transition duration-75 focus:border-primary-600 focus:ring-1 focus:ring-inset focus:ring-primary-600 dark:border-white/10 dark:bg-white/5 dark:text-white"
                                    />
                                </x-filament::input.wrapper>
                            </div>

                            {{-- Cédula --}}
                            <div>
                                <label for="debtor_id_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Cédula <span class="text-red-500">*</span>
                                </label>
                                <x-filament::input.wrapper prefix-icon="heroicon-o-identification">
                                    <x-filament::input
                                        id="debtor_id_number"
                                        type="text"
                                        wire:model="debtorIdNumber"
                                        placeholder="Ej: 1234567890"
                                        :disabled="$credit"
                                        class="fi-input block w-full border-gray-300 rounded-lg shadow-sm transition duration-75 focus:border-primary-600 focus:ring-1 focus:ring-inset focus:ring-primary-600 dark:border-white/10 dark:bg-white/5 dark:text-white"
                                    />
                                </x-filament::input.wrapper>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </x-sections.content>

        {{-- 🔹 Formulario dinámico --}}
        <x-sections.content :title="$credit ? 'Actualizar Crédito' : 'Registrar Crédito'" :is-collapsible="false">
            <form wire:submit="{{ 'formSubmit' }}" class="space-y-6">
                <div class="flex justify-between items-start gap-4">
                    <div class="flex items-start gap-3 flex-1">
                        <div class="flex-shrink-0">
                            <x-heroicon-o-information-circle class="w-5 h-5 text-primary-600 dark:text-primary-400 mt-0.5" />
                        </div>
                        <div class="text-sm">
                            <p class="text-gray-900 dark:text-white font-medium mb-2">
                                {{ $credit ? '¿Cómo actualizar el crédito?' : '¿Cómo usar el Sistema de créditos?' }}
                            </p>
                            <ul class="text-gray-700 dark:text-gray-300 space-y-1.5 list-disc pl-5">
                                <li>Seleccione el método de cálculo en el selector de arriba</li>
                                <li>Complete todos los campos conocidos y deje vacío el campo que desee calcular</li>
                                <li>El sistema calculará automáticamente el valor faltante</li>
                                @if(!$credit)
                                    <li>Al finalizar, puede guardar con el botón <strong>Guardar</strong></li>
                                @else
                                    <li>Al finalizar, presione <strong>Actualizar</strong> para guardar los cambios</li>
                                @endif
                            </ul>
                            <div class="mt-3 inline-flex items-center gap-2 px-3 py-1.5 bg-primary-50 dark:bg-primary-500/10 rounded-lg">
                                <x-heroicon-s-check-circle class="w-6 h-6 text-primary-600 dark:text-primary-400" />
                                <span class="text-sm font-medium text-primary-700 dark:text-primary-300">
                                    Método actual: {{ $this->getCalculationTypeOptions()[$calculationType] ?? 'N/A' }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="flex-shrink-0">
                        <x-filament::button
                            wire:click="limpiar"
                            color="gray"
                            icon="heroicon-o-arrow-path"
                            type="button"
                        >
                            Limpiar
                        </x-filament::button>
                    </div>
                </div>

                <div class="border-t border-gray-200 dark:border-white/10 pt-6" wire:key="form-{{ $calculationType }}">
                    {{ $this->form }}
                </div>
            </form>
        </x-sections.content>
    </div>
</x-filament-panels::page>
