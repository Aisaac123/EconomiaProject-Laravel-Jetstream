<x-filament-panels::page>
    @if(isset($credit) && $credit->payments()->count() > 0)
        {{-- Mensaje de crédito ya iniciado --}}
        <div class="mb-6 flex h-[700px] items-center justify-center">
            <div class="rounded-lg border border-yellow-200 bg-yellow-50 dark:border-yellow-900/30 dark:bg-yellow-900/20 p-6">
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0">
                        <x-heroicon-o-exclamation-triangle class="w-6 h-6 text-yellow-600 dark:text-yellow-500" />
                    </div>
                    <div class="flex-1">
                        <h3 class="text-base font-semibold text-yellow-900 dark:text-yellow-100 mb-1">
                            Crédito con pagos registrados
                        </h3>
                        <p class="text-sm text-yellow-800 dark:text-yellow-200 mb-4">
                            Este crédito ya ha sido iniciado con pagos registrados. Por razones de integridad financiera y auditoría, no es posible editar los parámetros principales del crédito.
                        </p>
                        <p class="text-sm text-yellow-800 dark:text-yellow-200 mb-4">
                            Si necesita realizar cambios, debe:
                        </p>
                        <ul class="text-sm text-yellow-800 dark:text-yellow-200 space-y-2 list-disc pl-5">
                            <li>Eliminar todos los pagos registrados, O</li>
                            <li>Crear un nuevo crédito con los parámetros actualizados</li>
                        </ul>
                        <div class="mt-4 flex gap-3">
                            <x-filament::button
                                tag="a"
                                href="{{ url(\App\Filament\Pages\Creditos\ListCredits::getUrl()) }}"
                                color="gray"
                                icon="heroicon-o-arrow-left"
                            >
                                Volver al Listado
                            </x-filament::button>

                            <x-filament::button
                                tag="a"
                                href="{{ url(\App\Filament\Pages\Creditos\CreateCredit::getUrl()) }}"
                                color="warning"
                                icon="heroicon-o-document-plus"
                            >
                                Crear Nuevo Crédito
                            </x-filament::button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        {{-- Contenido normal --}}
        <div class="space-y-6">
            {{-- 🔹 Título principal --}}
            <x-sections.heading-title
                :title="isset($credit) ? 'Editar Crédito' : 'Registrar Crédito'"
                :quote="isset($credit)
                    ? 'Revisar y ajustar un crédito es proteger la estabilidad financiera futura. — Banco de España'
                    : 'El crédito bien otorgado es el primer paso hacia una relación financiera saludable. — CONDUSEF'"
                :button-text="isset($credit) ? 'Volver a Créditos' : 'Gestionar Créditos'"
                href="{{ url(\App\Filament\Pages\Creditos\ListCredits::getUrl()) }}"
            >
                <x-slot:icon>
                    <x-heroicon-c-document-plus class="size-16 text-white" aria-hidden="true" />
                </x-slot:icon>
            </x-sections.heading-title>

            {{-- 🔹 Información del crédito --}}
            <x-sections.content title="Información del crédito" :is-collapsible="false" class="grid grid-cols-12 gap-4">
                {{-- Modelo financiero --}}
                <div class="lg:col-span-5 col-span-12" id="registrar">
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
                                        Seleccione el modelo de cálculo financiero que desea {{ isset($credit) ? 'actualizar o revisar' : 'realizar' }}
                                    </p>
                                    <label for="calculation-type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Modelo <span class="text-red-500">*</span>
                                    </label>
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

                {{-- Datos del cliente --}}
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
                                        {{ isset($credit) ? 'Revise o modifique los datos del deudor' : 'Complete los datos personales del deudor o cliente' }}
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
                                            :disabled="isset($credit)"
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
                                            :disabled="isset($credit)"
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
                                            :disabled="isset($credit)"
                                        />
                                    </x-filament::input.wrapper>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </x-sections.content>

            {{-- 🔹 Formulario dinámico --}}
            <x-sections.content :title="isset($credit) ? 'Actualizar Crédito' : 'Registrar Crédito'" :is-collapsible="false">
                <form wire:submit.prevent="formSubmit" class="space-y-6">
                    <div class="flex justify-between items-start gap-4">
                        <div class="flex items-start gap-3 flex-1">
                            <div class="flex-shrink-0">
                                <x-heroicon-o-information-circle class="w-5 h-5 text-primary-600 dark:text-primary-400 mt-0.5" />
                            </div>
                            <div class="text-sm">
                                <p class="text-gray-900 dark:text-white font-medium mb-2">
                                    {{ isset($credit) ? '¿Cómo actualizar el crédito?' : '¿Cómo usar el Sistema de créditos?' }}
                                </p>
                                <ul class="text-gray-700 dark:text-gray-300 space-y-1.5 list-disc pl-5">
                                    <li>Seleccione el método de cálculo en el selector de arriba</li>
                                    <li>Complete todos los campos conocidos y deje vacío el campo que desee calcular</li>
                                    @if(!isset($credit))
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
    @endif
</x-filament-panels::page>
