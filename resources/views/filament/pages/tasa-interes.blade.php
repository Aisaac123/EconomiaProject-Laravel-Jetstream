{{-- resources/views/filament/pages/tasa-interes.blade.php --}}
<x-filament-panels::page>
    <div class="space-y-6">

        {{-- Hero Section --}}
        <x-sections.heading-title
            title="Tasa de Interés"
            quote="“La tasa de interés es el precio del dinero en el tiempo, reflejando el costo de financiarse o la recompensa de invertir.”"
            button-text="Explorar conceptos"
            href="#conceptos"
        >
            <x-slot:icon>
                <x-heroicon-c-academic-cap class="size-16 text-white" aria-hidden="true" />
            </x-slot:icon>
        </x-sections.heading-title>

        {{-- Contenido principal --}}
        <x-sections.content id="conceptos">

            {{-- Descripción --}}
            <x-sections.contents.description>
                <p>
                    La <strong>tasa de interés</strong> es el porcentaje que se paga o recibe por el uso del dinero en un periodo de tiempo.
                    Representa el <span class="text-primary-600 dark:text-primary-400 font-medium">costo de pedir prestado</span>
                    o el <span class="text-primary-600 dark:text-primary-400 font-medium">beneficio de invertir</span>.
                </p>
                <p class="mt-2">
                    Se utiliza en créditos, ahorros, inversiones, tarjetas de crédito y prácticamente en todas las operaciones financieras.
                </p>
            </x-sections.contents.description>

            {{-- Tipos de Tasa de Interés --}}
            <div class="pt-8">
                <x-sections.contents.types title="Tipos de Tasa de Interés">
                    <x-filament::section collapsible collapsed heading="Tasa Nominal">
                        <p>...</p>
                    </x-filament::section>

                    <x-filament::section collapsible collapsed heading="Tasa Efectiva">
                        <p>...</p>
                    </x-filament::section>

                    <x-filament::section collapsible collapsed heading="Tasa Real">
                        <p>...</p>
                    </x-filament::section>
                </x-sections.contents.types>
            </div>

            {{-- Casos de Uso --}}
            <x-filament::section class="mt-8" heading="Casos de Uso" collapsible collapsed>
                <ul class="list-disc list-inside space-y-2">
                    <li><strong>Créditos:</strong> ...</li>
                    <li><strong>Inversiones:</strong> ...</li>
                    <li><strong>Ahorros:</strong> ...</li>
                    <li><strong>Tarjetas de crédito:</strong> ...</li>
                </ul>
            </x-filament::section>

            {{-- Ejemplos prácticos --}}
            <x-sections.contents.examples>
                <div class="space-y-4">
                    {{-- Ejemplos como ya tienes --}}
                </div>
                <x-slot:advice>
                    💡 Consejo: Siempre compara las tasas <strong>efectivas</strong>.
                </x-slot:advice>
            </x-sections.contents.examples>

            {{-- 🚀 Nueva sección: Interacción --}}
            <x-filament::section class="mt-8" heading="Interacción: Interés Simple vs Compuesto" collapsible>
                @livewire(\App\Filament\Widgets\InteresInteractivoChart::class)
            </x-filament::section>

        </x-sections.content>
    </div>
</x-filament-panels::page>
