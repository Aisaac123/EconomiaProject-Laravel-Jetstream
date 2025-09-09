{{-- filepath: resources/views/filament/pages/interes-simple.blade.php --}}
<x-filament::page>
    <div class="space-y-4">
        {{-- Formulario generado desde el Schema --}}
        {{ $this->form }}

        {{-- Resultado si existe --}}
        @if($data['resultado'] ?? false)
            <x-filament::card>
                <div class="text-lg font-bold">
                    Resultado (Interés Simple): ${{ number_format($data['resultado'], 2) }}
                </div>
            </x-filament::card>
        @endif
    </div>
</x-filament::page>
