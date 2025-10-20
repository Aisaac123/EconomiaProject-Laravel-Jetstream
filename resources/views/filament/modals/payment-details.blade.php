<div class="space-y-6">
    {{-- Información Principal --}}
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-950/50 dark:to-indigo-950/50 rounded-lg p-5 border border-blue-200 dark:border-blue-800">
        <div class="flex items-center gap-3 mb-4">
            <span class="text-3xl">💳</span>
            <div>
                <h3 class="text-lg font-bold text-blue-900 dark:text-blue-100">Pago #{{ $payment->id }}</h3>
                <p class="text-sm text-blue-700 dark:text-blue-300">{{ $payment->payment_date->format('d/m/Y') }}</p>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <p class="text-xs text-slate-600 dark:text-slate-400 mb-1">Monto Total</p>
                <p class="text-2xl font-bold text-blue-900 dark:text-blue-100">
                    ${{ number_format($payment->amount, 2) }}
                </p>
            </div>
            <div>
                <p class="text-xs text-slate-600 dark:text-slate-400 mb-1">Estado</p>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold
                    @if($payment->status === 'completed') bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-200
                    @elseif($payment->status === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-200
                    @else bg-gray-100 text-gray-800 dark:bg-gray-900/50 dark:text-gray-200
                    @endif">
                    {{ match($payment->status) {
                        'completed' => '✅ Completado',
                        'pending' => '⏳ Pendiente',
                        'reversed' => '↩️ Revertido',
                        default => ucfirst($payment->status)
                    } }}
                </span>
            </div>
        </div>
    </div>

    {{-- Distribución del Pago --}}
    <div class="bg-white dark:bg-slate-900 rounded-lg p-5 border border-slate-200 dark:border-slate-700">
        <h4 class="text-sm font-bold text-slate-700 dark:text-slate-300 mb-4 flex items-center gap-2">
            <span>📊</span> Distribución del Pago
        </h4>

        @if($payment->credit->type !== \App\Enums\CalculationType::GRADIENTES)
        <div class="grid grid-cols-2 gap-4">
            <div class="bg-green-50 dark:bg-green-950/30 rounded-lg p-4 border border-green-200 dark:border-green-800">
                <p class="text-xs text-green-700 dark:text-green-300 mb-1">Capital Pagado</p>
                <p class="text-xl font-bold text-green-900 dark:text-green-100">
                    ${{ number_format($payment->principal_paid, 2) }}
                </p>
                <p class="text-xs text-green-600 dark:text-green-400 mt-1">
                    {{ $payment->capital_proportion }}% del pago
                </p>
            </div>

                <div class="bg-amber-50 dark:bg-amber-950/30 rounded-lg p-4 border border-amber-200 dark:border-amber-800">
                    <p class="text-xs text-amber-700 dark:text-amber-300 mb-1">Interés Pagado</p>
                    <p class="text-xl font-bold text-amber-900 dark:text-amber-100">
                        ${{ number_format($payment->interest_paid, 2) }}
                    </p>
                    <p class="text-xs text-amber-600 dark:text-amber-400 mt-1">
                        {{ $payment->interest_proportion }}% del pago
                    </p>
                </div>
        </div>
            <div class="mt-4 bg-red-50 dark:bg-red-950/30 rounded-lg p-4 border border-red-200 dark:border-red-800">
                <p class="text-xs text-red-700 dark:text-red-300 mb-1">Saldo Restante Después del Pago</p>
                <p class="text-2xl font-bold text-red-900 dark:text-red-100">
                    ${{ number_format($payment->remaining_balance, 2) }}
                </p>
                @if($payment->is_final_payment)
                    <p class="text-xs text-green-600 dark:text-green-400 mt-1 flex items-center gap-1">
                        <span>🎉</span> ¡Último pago! Crédito liquidado
                    </p>
                @endif
            </div>
        @else
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <div class="bg-green-50 dark:bg-green-950/30 rounded-lg p-4 border border-green-200 dark:border-green-800">
                        <p class="text-xs text-green-700 dark:text-green-300 mb-1">Capital Pagado</p>
                        <p class="text-xl font-bold text-green-900 dark:text-green-100">
                            ${{ number_format($payment->principal_paid, 2) }}
                        </p>
                        <p class="text-xs text-green-600 dark:text-green-400 mt-1">
                            {{ $payment->capital_proportion }}% del pago
                        </p>
                    </div>
                </div>
                <div class="bg-red-50 dark:bg-red-950/30 rounded-lg p-4 border border-red-200 dark:border-red-800">
                    <p class="text-xs text-red-700 dark:text-red-300 mb-1">Saldo Restante Después del Pago</p>
                    <p class="text-2xl font-bold text-red-900 dark:text-red-100">
                        ${{ number_format($payment->remaining_balance, 2) }}
                    </p>
                    @if($payment->is_final_payment)
                        <p class="text-xs text-green-600 dark:text-green-400 mt-1 flex items-center gap-1">
                            <span>🎉</span> ¡Último pago! Crédito liquidado
                        </p>
                    @endif
                </div>
            </div>
        @endif
    </div>

    {{-- Metadata --}}
    @if($payment->metadata && count($payment->metadata) > 0)
        <div class="bg-slate-50 dark:bg-slate-900/50 rounded-lg p-5 border border-slate-200 dark:border-slate-700">
            <h4 class="text-sm font-bold text-slate-700 dark:text-slate-300 mb-4 flex items-center gap-2">
                <span>📋</span> Información Adicional
            </h4>

            <div class="grid grid-cols-2 gap-3 text-sm">
                @if(isset($payment->metadata['numero_pago']))
                    <div class="flex justify-between py-2 border-b border-slate-200 dark:border-slate-700">
                        <span class="text-slate-600 dark:text-slate-400">Número de Pago:</span>
                        <span class="font-semibold text-slate-900 dark:text-slate-100">
                    #{{ $payment->metadata['numero_pago'] }}
                </span>
                    </div>
                @endif

                @if(isset($payment->metadata['porcentaje_completado']))
                    <div class="flex justify-between py-2 border-b border-slate-200 dark:border-slate-700">
                        <span class="text-slate-600 dark:text-slate-400">% Completado:</span>
                        <span class="font-semibold text-slate-900 dark:text-slate-100">
                    {{ number_format($payment->metadata['porcentaje_completado'], 2) }}%
                </span>
                    </div>
                @endif

                @if(isset($payment->metadata['saldo_anterior']))
                    <div class="flex justify-between py-2 border-b border-slate-200 dark:border-slate-700">
                        <span class="text-slate-600 dark:text-slate-400">Saldo Anterior:</span>
                        <span class="font-semibold text-slate-900 dark:text-slate-100">
                    ${{ number_format($payment->metadata['saldo_anterior'], 2) }}
                </span>
                    </div>
                @endif

                @if(isset($payment->metadata['capital_pendiente_anterior']))
                    <div class="flex justify-between py-2 border-b border-slate-200 dark:border-slate-700">
                        <span class="text-slate-600 dark:text-slate-400">Capital Pendiente Anterior:</span>
                        <span class="font-semibold text-slate-900 dark:text-slate-100">
                    ${{ number_format($payment->metadata['capital_pendiente_anterior'], 2) }}
                </span>
                    </div>
                @endif

                @if(isset($payment->metadata['interes_pendiente_anterior']))
                    <div class="flex justify-between py-2 border-b border-slate-200 dark:border-slate-700">
                        <span class="text-slate-600 dark:text-slate-400">Interés Pendiente Anterior:</span>
                        <span class="font-semibold text-slate-900 dark:text-slate-100">
                    ${{ number_format($payment->metadata['interes_pendiente_anterior'], 2) }}
                </span>
                    </div>
                @endif

                @if(isset($payment->metadata['fecha_registro']))
                    <div class="flex justify-between py-2 border-b border-slate-200 dark:border-slate-700">
                        <span class="text-slate-600 dark:text-slate-400">Fecha de Registro:</span>
                        <span class="font-semibold text-slate-900 dark:text-slate-100">
                    {{ \Carbon\Carbon::parse($payment->metadata['fecha_registro'])->format('d/m/Y H:i') }}
                </span>
                    </div>
                @endif
            </div>
        </div>
    @endif

    {{-- Timestamps --}}
    <div class="bg-slate-50 dark:bg-slate-900/50 rounded-lg p-4 border border-slate-200 dark:border-slate-700">
        <div class="grid grid-cols-2 gap-4 text-xs text-slate-600 dark:text-slate-400">
            <div>
                <span class="font-semibold">Creado:</span>
                {{ $payment->created_at->format('d/m/Y H:i') }}
            </div>
            <div>
                <span class="font-semibold">Actualizado:</span>
                {{ $payment->updated_at->format('d/m/Y H:i') }}
            </div>
        </div>
    </div>
</div>
