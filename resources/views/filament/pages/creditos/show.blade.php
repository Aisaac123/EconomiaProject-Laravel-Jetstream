<x-filament-panels::page>
    <div class="space-y-4 my-4">
        {{-- Información del Deudor --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Card Principal: Información Básica --}}
            <div class="lg:col-span-2 bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700">
                <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-700/50">
                    <div class="flex items-center gap-3">
                        <x-heroicon-o-user-circle class="w-5 h-5 text-primary-600 dark:text-primary-400" />
                        <h3 class="text-sm font-bold text-slate-900 dark:text-white">Información del Deudor</h3>
                    </div>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div>
                            <p class="text-xs text-slate-600 dark:text-slate-400 mb-1">Nombre Completo</p>
                            <p class="text-lg font-bold text-slate-900 dark:text-white">
                                {{ $this->record->debtor_names }} {{ $this->record->debtor_last_names }}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-600 dark:text-slate-400 mb-1">Número de Cédula</p>
                            <div class="flex items-center gap-2">
                                <p class="text-lg font-bold text-slate-900 dark:text-white font-mono">
                                    {{ $this->record->debtor_id_number }}
                                </p>
                                <button
                                    onclick="navigator.clipboard.writeText('{{ $this->record->debtor_id_number }}')"
                                    class="p-1 hover:bg-slate-100 dark:hover:bg-slate-700 rounded transition-colors"
                                    title="Copiar cédula">
                                    <x-heroicon-o-document-duplicate class="w-4 h-4 text-slate-500 dark:text-slate-400" />
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Card Lateral: Metadatos --}}
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700">
                <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-700/50">
                    <div class="flex items-center gap-3">
                        <x-heroicon-o-information-circle class="w-5 h-5 text-primary-600 dark:text-primary-400" />
                        <h3 class="text-sm font-bold text-slate-900 dark:text-white">Detalles</h3>
                    </div>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <p class="text-xs text-slate-600 dark:text-slate-400 mb-1">Código de Referencia</p>
                        <div class="flex items-center gap-2">
                            <p class="text-sm font-mono font-bold text-primary-600 dark:text-primary-400">
                                {{ $this->record->reference_code }}
                            </p>
                            <button
                                onclick="navigator.clipboard.writeText('{{ $this->record->reference_code }}')"
                                class="p-1 hover:bg-slate-100 dark:hover:bg-slate-700 rounded transition-colors">
                                <x-heroicon-o-document-duplicate class="w-4 h-4 text-slate-500 dark:text-slate-400" />
                            </button>
                        </div>
                    </div>

                    <div>
                        <p class="text-xs text-slate-600 dark:text-slate-400 mb-1">Tipo de Cálculo</p>
                        <p class="text-sm font-bold text-slate-900 dark:text-white">
                            @switch($this->record->type)
                                @case(\App\Enums\CalculationType::SIMPLE)
                                    Interés Simple
                                    @break
                                @case(\App\Enums\CalculationType::COMPUESTO)
                                    Interés Compuesto
                                    @break
                                @case(\App\Enums\CalculationType::ANUALIDAD)
                                    Anualidad
                                    @break
                                @case(\App\Enums\CalculationType::TIR)
                                    Tasa Interna de Retorno (TIR)
                                    @break
                                @case(\App\Enums\CalculationType::GRADIENTES)
                                    Gradiente
                                    @break
                                @case(\App\Enums\CalculationType::AMORTIZACION)
                                    Amortización
                                    @break
                                @default
                                    {{ $this->record->type->value }}
                            @endswitch
                        </p>
                    </div>

                    <div>
                        <p class="text-xs text-slate-600 dark:text-slate-400 mb-1">Estado</p>
                        <span class="px-2.5 py-1 text-xs font-semibold rounded-full
                            @if($this->record->status === 'calculated') bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400
                            @elseif($this->record->status === 'pending') dark:bg-amber-900/30 text-amber-700 dark:text-amber-400
                            @elseif($this->record->status === 'paid') dark:bg-sky-900/30 text-sky-700 dark:text-sky-400
                            @else dark:bg-slate-700 text-slate-700 dark:text-slate-400
                            @endif">
                            {{ match($this->record->status) {
                                'calculated' => '✓ Calculado',
                                'pending' => '⏳ Pendiente',
                                'paid' => '✓ Pagado',
                                'cancelled' => '✗ Cancelado',
                                default => $this->record->status,
                            } }}
                        </span>
                    </div>

                    <div>
                        <p class="text-xs text-slate-600 dark:text-slate-400 mb-1">Creado Por</p>
                        <p class="text-sm font-semibold text-slate-900 dark:text-white">
                            {{ $this->record->user?->name ?? 'N/A' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs text-slate-600 dark:text-slate-400 mb-1">Última Actualización</p>
                        <p class="text-sm font-semibold text-slate-900 dark:text-white">
                            {{ $this->record->calculated_at?->format('d/m/Y H:i') ?? 'N/A' }}
                        </p>
                        <p class="text-xs text-slate-600 dark:text-slate-400 mt-1">
                            {{ $this->record->calculated_at?->diffForHumans() ?? 'N/A' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Resultados del Cálculo --}}
        <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700">
            <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-700/50">
                <div class="flex items-center gap-3">
                    <x-heroicon-o-chart-bar class="w-5 h-5 text-primary-600 dark:text-primary-400" />
                    <h3 class="text-sm font-bold text-slate-900 dark:text-white">Resultados del Cálculo</h3>
                </div>
            </div>
            <div class="p-6">
                @php
                    $inputs = $this->record->inputs ?? [];
                    // Extraer datos de results
                    $resultadoCalculado = $inputs['resultado_calculado'] ?? null;
                    $resultadosCalculados = $inputs['resultados_calculados'] ?? null;
                @endphp
                @if($resultadoCalculado || $resultadosCalculados)
                    {{-- Usar el método buildResultHtml de InteresSimpleSchema --}}
                    {!! \App\Filament\Schemas\CreditSchemaFactory::buildResultHtml($this->record->type->value, $this->record->inputs ?? []) !!}
                @else
                    <div class="text-center py-12 text-slate-500 dark:text-slate-400">
                        <div class="text-5xl mb-4">📊</div>
                        <h3 class="text-lg font-semibold mb-2">Sin resultados disponibles</h3>
                        <p class="text-sm">No hay datos de cálculo para mostrar</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Datos JSON (Debug - Opcional) --}}
        <details class="bg-slate-50 dark:bg-slate-900/50 rounded-lg p-4 border border-slate-200 dark:border-slate-700">
            <summary class="cursor-pointer font-semibold text-slate-700 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white transition-colors">
                ℹ️ Datos JSON (Expandir)
            </summary>
            <div class="mt-4 space-y-4">
                <div>
                    <p class="text-xs font-semibold text-slate-600 dark:text-slate-400 mb-2">Inputs</p>
                    <pre class="bg-white dark:bg-slate-800 p-3 rounded border border-slate-200 dark:border-slate-700 text-xs overflow-auto">{{ json_encode($this->record->inputs, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-600 dark:text-slate-400 mb-2">Results</p>
                    <pre class="bg-white dark:bg-slate-800 p-3 rounded border border-slate-200 dark:border-slate-700 text-xs overflow-auto">{{ json_encode($this->record->results, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                </div>
            </div>
        </details>
    </div>
</x-filament-panels::page>
