<?php

namespace App\Models;

use App\Enums\CalculationType;
use Carbon\Carbon;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Illuminate\Database\Eloquent\Model;

class Credit extends Model
{
    protected $fillable = [
        'user_id',
        'debtor_names',
        'debtor_last_names',
        'debtor_id_number',
        'type',
        'inputs',
        'results',
        'status',
        'reference_code',
        'calculated_at',
    ];

    public bool $isCalculatingPayment = false;

    protected $casts = [
        'inputs' => 'array',
        'results' => 'array',
        'calculated_at' => 'datetime',
        'type' => CalculationType::class,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Métodos para datos de pagos
    |--------------------------------------------------------------------------
    */

    /**
     * Prepara los datos para buildPagosHtml según el tipo de crédito
     */
    public function getPagosData(?array $data = null): array
    {
        return match ($this->type) {
            CalculationType::SIMPLE => $this->getPagosDataSimple($data['amount'] ?? null),
            CalculationType::COMPUESTO => $this->getPagosDataCompuesto($data['amount'] ?? null),
            CalculationType::AMORTIZACION => $this->getPagosDataAmortizacion($data),
            CalculationType::ANUALIDAD => $this->getPagosDataAnualidad($data['amount']),
            CalculationType::GRADIENTES => $this->getPagosDataGradientes($data['amount']),
            CalculationType::TIR => $this->getPagosDataTIR($data['amount']),
            default => [],
        };
    }

    public function calculatePaymentDistribution(Get $get, Set $set, ?array $data = null): void
    {
        match ($this->type) {
            CalculationType::SIMPLE => $this->calculatePaymentDistributionSimple($get, $set, $data),
            CalculationType::COMPUESTO => $this->calculatePaymentDistributionCompuesto($get, $set, $data),
            CalculationType::AMORTIZACION => $this->calculatePaymentDistributionAmortizacion($get, $set, $data),
            CalculationType::ANUALIDAD => $this->getPagosDataAnualidad($data['amount']),
            CalculationType::GRADIENTES => $this->getPagosDataGradientes($data['amount']),
            CalculationType::TIR => $this->getPagosDataTIR($data['amount']),
            default => [],
        };
    }

    /**
     * Genera mensaje contextual enfocado en pagos
     */
    public function generarMensajeFinalPagos(
        string $estadoCredito,
        float $saldoRestante,
        int $numeroPagos,
        float $porcentajePagado,
        ?int $diasRestantes
    ): ?string {
        switch (strtolower($estadoCredito)) {
            case 'pagado':
            case 'liquidado':
                return "¡Crédito liquidado! Total de {$numeroPagos} pagos realizados exitosamente.";

            case 'vencido':
                return 'Crédito vencido. Saldo pendiente: $'.number_format($saldoRestante, 2).". Se han realizado {$numeroPagos} pagos hasta la fecha.";

            case 'activo':
            case 'vigente':
                if ($numeroPagos === 0) {
                    if ($diasRestantes !== null && $diasRestantes > 0) {
                        return "Crédito activo. Aún no se han registrado pagos. Quedan {$diasRestantes} días para el vencimiento.";
                    }

                    return 'Crédito activo. Aún no se han registrado pagos.';
                }

                $mensaje = "Progreso: {$porcentajePagado}% completado con {$numeroPagos} pago(s) realizado(s).";

                if ($diasRestantes !== null) {
                    if ($diasRestantes > 0) {
                        $mensaje .= " Quedan {$diasRestantes} días para el vencimiento.";
                    } elseif ($diasRestantes === 0) {
                        $mensaje .= ' ¡Vence hoy!';
                    }
                }

                return $mensaje;

            case 'cancelado':
                return "Crédito cancelado. Se realizaron {$numeroPagos} pago(s) antes de la cancelación.";

            default:
                return null;
        }
    }

    /**
     * Datos para Interés Simple
     */
    public function getPagosDataSimple(?float $amount = null): array
    {
        $inputs = $this->inputs ?? [];

        // Datos del crédito original
        $capitalInicial = $inputs['capital'] ?? 0;
        $montoTotal = $inputs['resultado_calculado'] ?? $inputs['monto_final'] ?? 0;
        $interesTotal = $inputs['interes_generado_calculado'] ?? $inputs['interes_generado'] ?? 0;

        // Obtener todos los pagos completados
        $pagosCompletados = $this->payments()->where('status', 'completed')->orderBy('payment_date')->get();

        // Calcular totales de pagos
        $totalPagado = $pagosCompletados->sum('amount');
        $totalCapitalPagado = $pagosCompletados->sum('principal_paid');
        $totalInteresPagado = $pagosCompletados->sum('interest_paid');
        $numeroPagosRealizados = $pagosCompletados->count();

        // Calcular saldos pendientes
        $saldoRestante = max(0, $montoTotal - $totalPagado);
        $capitalPendiente = max(0, $capitalInicial - $totalCapitalPagado);
        $interesPendiente = max(0, $interesTotal - $totalInteresPagado);

        // Si se pasó un amount, calcular distribución del pago y retornar datos para Payment
        if ($amount !== null) {
            // Primero se paga el interés pendiente
            $interesPagado = min($amount, $interesPendiente);

            // Lo que sobra se aplica al capital
            $capitalPagado = max(0, $amount - $interesPagado);

            // Calcular nuevo saldo
            $nuevoSaldo = max(0, $saldoRestante - $amount);

            // Calcular porcentaje completado
            $porcentajeCompletado = $montoTotal > 0
                ? round((($totalPagado + $amount) / $montoTotal) * 100, 2)
                : 0;

            // Retornar array listo para Payment::create()
            return [
                'principal_paid' => round($capitalPagado, 2),
                'interest_paid' => round($interesPagado, 2),
                'remaining_balance' => round($nuevoSaldo, 2),
                'metadata' => [
                    'saldo_anterior' => $saldoRestante,
                    'saldo_nuevo' => round($nuevoSaldo, 2),
                    'capital_pendiente_anterior' => $capitalPendiente,
                    'capital_pendiente_nuevo' => max(0, $capitalPendiente - $capitalPagado),
                    'interes_pendiente_anterior' => $interesPendiente,
                    'interes_pendiente_nuevo' => max(0, $interesPendiente - $interesPagado),
                    'numero_pago' => $numeroPagosRealizados + 1,
                    'porcentaje_completado' => $porcentajeCompletado,
                    'tipo_calculo' => $this->type->value,
                    'fecha_registro' => now()->toDateTimeString(),
                ],
            ];
        }

        // Si no se pasó amount, retornar información general (comportamiento actual)
        $ultimoPago = $pagosCompletados->last();
        $fechaUltimoPago = $ultimoPago ? $ultimoPago->payment_date->format('Y-m-d') : null;
        $montoUltimoPago = $ultimoPago ? $ultimoPago->amount : null;

        $promedioMontoPago = $numeroPagosRealizados > 0 ? $totalPagado / $numeroPagosRealizados : 0;
        $pagosEstimadosRestantes = $promedioMontoPago > 0 ? ceil($saldoRestante / $promedioMontoPago) : null;

        // Calcular fechas
        $fechaInicio = null;
        $fechaVencimiento = null;

        if ($inputs['usar_fechas_tiempo'] ?? false) {
            $fechaInicio = $inputs['fecha_inicio'] ?? null;
            $fechaVencimiento = $inputs['fecha_final'] ?? null;
        } else {
            if ($this->created_at) {
                $fechaInicio = $this->created_at->format('Y-m-d');
                $carbon = $this->created_at->copy();

                if ($inputs['anio'] ?? null) {
                    $carbon->addYears($inputs['anio']);
                }
                if ($inputs['mes'] ?? null) {
                    $carbon->addMonths($inputs['mes']);
                }
                if ($inputs['dia'] ?? null) {
                    $carbon->addDays($inputs['dia']);
                }

                $fechaVencimiento = $carbon->format('Y-m-d');
            }
        }

        $estadoCredito = $this->determinarEstadoCredito($saldoRestante, $fechaVencimiento);
        $porcentajePagado = $montoTotal > 0 ? round(($totalPagado / $montoTotal) * 100, 2) : 0;
        $porcentajeCapitalPagado = $capitalInicial > 0 ? round(($totalCapitalPagado / $capitalInicial) * 100, 2) : 0;
        $porcentajeInteresPagado = $interesTotal > 0 ? round(($totalInteresPagado / $interesTotal) * 100, 2) : 0;

        $diasTranscurridos = $this->created_at ? smartRound($this->created_at->diffInDays(now())) : null;
        $diasRestantes = $fechaVencimiento ? smartRound(now()->diffInDays(\Carbon\Carbon::parse($fechaVencimiento), false)) : null;

        $mensajeFinal = $this->generarMensajeFinalPagos(
            $estadoCredito,
            $saldoRestante,
            $numeroPagosRealizados,
            $porcentajePagado,
            $diasRestantes
        );

        return [
            'capital_inicial' => $capitalInicial,
            'monto_total_credito' => $montoTotal,
            'interes_total_credito' => $interesTotal,
            'total_pagado' => $totalPagado,
            'capital_pagado' => $totalCapitalPagado,
            'interes_pagado' => $totalInteresPagado,
            'saldo_restante' => $saldoRestante,
            'capital_pendiente' => $capitalPendiente,
            'interes_pendiente' => $interesPendiente,
            'numero_pagos_realizados' => $numeroPagosRealizados,
            'promedio_monto_pago' => $promedioMontoPago,
            'pagos_estimados_restantes' => $pagosEstimadosRestantes,
            'fecha_ultimo_pago' => $fechaUltimoPago,
            'monto_ultimo_pago' => $montoUltimoPago,
            'porcentaje_pagado' => $porcentajePagado,
            'porcentaje_capital_pagado' => $porcentajeCapitalPagado,
            'porcentaje_interes_pagado' => $porcentajeInteresPagado,
            'fecha_inicio' => $fechaInicio,
            'fecha_vencimiento' => $fechaVencimiento,
            'dias_transcurridos' => $diasTranscurridos,
            'dias_restantes' => $diasRestantes,
            'estado_credito' => $estadoCredito,
            'mensaje_final' => $mensajeFinal,
        ];
    }

    private function calculatePaymentDistributionSimple(Get $get, Set $set, ?array $data = null): void
    {
        $amount = isset($data['amount']) ? (float) $data['amount'] : null;
        $interest = isset($data['interest_paid']) ? (float) $data['interest_paid'] : null;
        $principal = isset($data['principal_paid']) ? (float) $data['principal_paid'] : null;

        // Caso 1: el usuario ingresa el monto total
        if ($amount !== null && $amount > 0) {
            $pagosData = $this->getPagosDataSimple($amount);

            $interestPaid = (float) ($pagosData['interest_paid'] ?? 0);
            $principalPaid = (float) ($pagosData['principal_paid'] ?? 0);
            $remainingBalance = (float) ($pagosData['remaining_balance'] ?? 0);

            $set('interest_paid', round($interestPaid, 2));
            $set('principal_paid', round($principalPaid, 2));
            $set('remaining_balance', round($remainingBalance, 2));

            return;
        }

        // Caso 2: el usuario ingresa interés y/o capital
        $interest = $interest ?? 0.0;
        $principal = $principal ?? 0.0;
        $total = $interest + $principal;
        $set('amount', round($total, 2));
        $pagosData = $this->getPagosDataSimple($total);
        $saldoRestante = (float) ($pagosData['remaining_balance'] ?? 0);

        $set('remaining_balance', round($saldoRestante, 2));
    }

    /**
     * Datos para Interés Compuesto (placeholder - implementar según necesidad)
     */
    public function getPagosDataCompuesto(?float $amount = null): array
    {
        $inputs = $this->inputs ?? [];

        // Datos del crédito original
        $capitalInicial = $inputs['capital'] ?? 0;
        $montoTotal = $inputs['resultado_calculado'] ?? $inputs['monto_final'] ?? 0;
        $interesTotal = $inputs['interes_generado_calculado'] ?? $inputs['interes_generado'] ?? 0;

        // Obtener todos los pagos completados
        $pagosCompletados = $this->payments()->where('status', 'completed')->orderBy('payment_date')->get();

        // Calcular totales de pagos
        $totalPagado = $pagosCompletados->sum('amount');
        $totalCapitalPagado = $pagosCompletados->sum('principal_paid');
        $totalInteresPagado = $pagosCompletados->sum('interest_paid');
        $numeroPagosRealizados = $pagosCompletados->count();

        // Calcular saldos pendientes
        $saldoRestante = max(0, $montoTotal - $totalPagado);
        $capitalPendiente = max(0, $capitalInicial - $totalCapitalPagado);
        $interesPendiente = max(0, $interesTotal - $totalInteresPagado);

        // Si se pasó un amount, calcular distribución del pago y retornar datos para Payment
        if ($amount !== null) {
            // En interés compuesto, también se paga primero el interés
            $interesPagado = min($amount, $interesPendiente);

            // Lo que sobra se aplica al capital
            $capitalPagado = max(0, $amount - $interesPagado);

            // Calcular nuevo saldo
            $nuevoSaldo = max(0, $saldoRestante - $amount);

            // Calcular porcentaje completado
            $porcentajeCompletado = $montoTotal > 0
                ? round((($totalPagado + $amount) / $montoTotal) * 100, 2)
                : 0;

            // Retornar array listo para Payment::create()
            return [
                'principal_paid' => round($capitalPagado, 2),
                'interest_paid' => round($interesPagado, 2),
                'remaining_balance' => round($nuevoSaldo, 2),
                'metadata' => [
                    'saldo_anterior' => $saldoRestante,
                    'saldo_nuevo' => round($nuevoSaldo, 2),
                    'capital_pendiente_anterior' => $capitalPendiente,
                    'capital_pendiente_nuevo' => max(0, $capitalPendiente - $capitalPagado),
                    'interes_pendiente_anterior' => $interesPendiente,
                    'interes_pendiente_nuevo' => max(0, $interesPendiente - $interesPagado),
                    'numero_pago' => $numeroPagosRealizados + 1,
                    'porcentaje_completado' => $porcentajeCompletado,
                    'tipo_calculo' => $this->type->value,
                    'fecha_registro' => now()->toDateTimeString(),
                ],
            ];
        }

        // Si no se pasó amount, retornar información general
        $ultimoPago = $pagosCompletados->last();
        $fechaUltimoPago = $ultimoPago ? $ultimoPago->payment_date->format('Y-m-d') : null;
        $montoUltimoPago = $ultimoPago ? $ultimoPago->amount : null;

        $promedioMontoPago = $numeroPagosRealizados > 0 ? $totalPagado / $numeroPagosRealizados : 0;
        $pagosEstimadosRestantes = $promedioMontoPago > 0 ? ceil($saldoRestante / $promedioMontoPago) : null;

        // Calcular fechas
        $fechaInicio = null;
        $fechaVencimiento = null;

        if ($inputs['usar_fechas_tiempo'] ?? false) {
            $fechaInicio = $inputs['fecha_inicio'] ?? null;
            $fechaVencimiento = $inputs['fecha_final'] ?? null;
        } else {
            if ($this->created_at) {
                $fechaInicio = $this->created_at->format('Y-m-d');
                $carbon = $this->created_at->copy();

                if ($inputs['anio'] ?? null) {
                    $carbon->addYears($inputs['anio']);
                }
                if ($inputs['mes'] ?? null) {
                    $carbon->addMonths($inputs['mes']);
                }
                if ($inputs['dia'] ?? null) {
                    $carbon->addDays($inputs['dia']);
                }

                $fechaVencimiento = $carbon->format('Y-m-d');
            }
        }

        $estadoCredito = $this->determinarEstadoCredito($saldoRestante, $fechaVencimiento);
        $porcentajePagado = $montoTotal > 0 ? round(($totalPagado / $montoTotal) * 100, 2) : 0;
        $porcentajeCapitalPagado = $capitalInicial > 0 ? round(($totalCapitalPagado / $capitalInicial) * 100, 2) : 0;
        $porcentajeInteresPagado = $interesTotal > 0 ? round(($totalInteresPagado / $interesTotal) * 100, 2) : 0;

        $diasTranscurridos = $this->created_at ? smartRound($this->created_at->diffInDays(now())) : null;
        $diasRestantes = $fechaVencimiento ? smartRound(now()->diffInDays(\Carbon\Carbon::parse($fechaVencimiento), false)) : null;

        $mensajeFinal = $this->generarMensajeFinalPagos(
            $estadoCredito,
            $saldoRestante,
            $numeroPagosRealizados,
            $porcentajePagado,
            $diasRestantes
        );

        return [
            'capital_inicial' => $capitalInicial,
            'monto_total_credito' => $montoTotal,
            'interes_total_credito' => $interesTotal,
            'total_pagado' => $totalPagado,
            'capital_pagado' => $totalCapitalPagado,
            'interes_pagado' => $totalInteresPagado,
            'saldo_restante' => $saldoRestante,
            'capital_pendiente' => $capitalPendiente,
            'interes_pendiente' => $interesPendiente,
            'numero_pagos_realizados' => $numeroPagosRealizados,
            'promedio_monto_pago' => $promedioMontoPago,
            'pagos_estimados_restantes' => $pagosEstimadosRestantes,
            'fecha_ultimo_pago' => $fechaUltimoPago,
            'monto_ultimo_pago' => $montoUltimoPago,
            'porcentaje_pagado' => $porcentajePagado,
            'porcentaje_capital_pagado' => $porcentajeCapitalPagado,
            'porcentaje_interes_pagado' => $porcentajeInteresPagado,
            'fecha_inicio' => $fechaInicio,
            'fecha_vencimiento' => $fechaVencimiento,
            'dias_transcurridos' => $diasTranscurridos,
            'dias_restantes' => $diasRestantes,
            'estado_credito' => $estadoCredito,
            'mensaje_final' => $mensajeFinal,
        ];
    }

    private function calculatePaymentDistributionCompuesto(Get $get, Set $set, ?array $data = null): void
    {
        $amount = isset($data['amount']) ? (float) $data['amount'] : null;
        $interest = isset($data['interest_paid']) ? (float) $data['interest_paid'] : null;
        $principal = isset($data['principal_paid']) ? (float) $data['principal_paid'] : null;

        // Caso 1: el usuario ingresa el monto total
        if ($amount !== null && $amount > 0) {
            $paymentData = $this->getPagosDataCompuesto($amount);

            $interestPaid = (float) ($paymentData['interest_paid'] ?? 0);
            $principalPaid = (float) ($paymentData['principal_paid'] ?? 0);
            $remainingBalance = (float) ($paymentData['remaining_balance'] ?? 0);

            $set('interest_paid', round($interestPaid, 2));
            $set('principal_paid', round($principalPaid, 2));
            $set('remaining_balance', round($remainingBalance, 2));

            return;
        }

        // Caso 2: el usuario ingresa interés y/o capital manualmente
        $interest = $interest ?? 0.0;
        $principal = $principal ?? 0.0;

        $total = $interest + $principal;
        $saldoRestante = (float) ($this->record->saldo_restante ?? 0);

        // En compuesto también solo se reduce el saldo por el capital
        $nuevoSaldo = max(0, $saldoRestante - $principal);

        $set('amount', round($total, 2));
        $set('remaining_balance', round($nuevoSaldo, 2));
    }

    /**
     * Datos para Amortización - Con recálculo dinámico basado en pagos reales
     */
    public function getPagosDataAmortizacion(?array $data = null): array
    {
        $inputs = $this->inputs ?? [];

        // Obtener resultados calculados originales
        $resultados = is_string($inputs['resultados_calculados'] ?? null)
            ? json_decode($inputs['resultados_calculados'], true)
            : ($inputs['resultados_calculados'] ?? []);

        // Datos originales del crédito
        $capitalInicial = $resultados['monto_prestamo'] ?? $inputs['monto_prestamo'] ?? 0;
        $tasaInteres = ($inputs['tasa_interes'] ?? 0) / 100;
        $numeroCuotasOriginal = $resultados['numero_pagos'] ?? $inputs['numero_pagos'] ?? 0;
        $sistema = $resultados['sistema'] ?? $inputs['sistema_amortizacion'] ?? 'Francés';

        // Obtener todos los pagos completados ordenados por fecha
        $pagosCompletados = $this->payments()
            ->where('status', 'completed')
            ->orderBy('payment_date')
            ->get();

        // RECALCULAR tabla de amortización basada en pagos reales Y SISTEMA
        $tablaRecalculada = $this->recalcularTablaAmortizacionPorSistema(
            $capitalInicial,
            $tasaInteres,
            $numeroCuotasOriginal,
            $sistema,
            $resultados,
            $pagosCompletados
        );

        // Calcular totales
        $totalPagado = $pagosCompletados->sum('amount');
        $totalCapitalPagado = $pagosCompletados->sum('principal_paid');
        $totalInteresPagado = $pagosCompletados->sum('interest_paid');
        $numeroPagosRealizados = $pagosCompletados->count();

        // Obtener cuota actual
        $cuotaActual = $this->obtenerCuotaActualAmortizacion($tablaRecalculada);

        // Totales del crédito
        $numeroCuotasTotal = count($tablaRecalculada);
        $montoTotalPendiente = array_sum(array_column(array_filter($tablaRecalculada, fn ($c) => ! $c['pagado']), 'cuota'));
        $montoTotal = $totalPagado + $montoTotalPendiente;
        $interesTotal = $montoTotal - $capitalInicial;

        // Saldos actuales
        $saldoRestante = $cuotaActual ? $cuotaActual['saldo_inicial'] : 0;
        $capitalPendiente = $saldoRestante;
        $interesPendiente = $cuotaActual ? $cuotaActual['interes'] : 0;

        // Si se pasó data, calcular distribución del pago
        if ($data !== null) {
            // VERIFICAR SI ES MODO MANUAL
            if (isset($data['interest_paid']) && isset($data['principal_paid'])) {
                return $this->calcularDistribucionPagoManual(
                    $data,
                    $cuotaActual,
                    $saldoRestante,
                    $totalPagado,
                    $montoTotal,
                    $numeroPagosRealizados,
                    $capitalPendiente,
                    $interesPendiente
                );
            }

            // MODO AUTOMÁTICO
            $amount = $data['amount'] ?? 0;

            return $this->calcularDistribucionPagoAmortizacion(
                $amount,
                $cuotaActual,
                $saldoRestante,
                $tasaInteres,
                $totalPagado,
                $montoTotal,
                $numeroPagosRealizados,
                $capitalPendiente,
                $interesPendiente,
                $sistema
            );
        }

        // Resto del código para información general...
        $ultimoPago = $pagosCompletados->last();
        $fechaUltimoPago = $ultimoPago ? $ultimoPago->payment_date->format('Y-m-d') : null;
        $montoUltimoPago = $ultimoPago ? $ultimoPago->amount : 0;

        $promedioMontoPago = $numeroPagosRealizados > 0 ? $totalPagado / $numeroPagosRealizados : 0;
        $cuotasRestantes = $numeroCuotasTotal - $numeroPagosRealizados;

        $fechaInicio = $inputs['fecha_inicio'] ?? ($this->created_at ? $this->created_at->format('Y-m-d') : null);
        $fechaVencimiento = null;
        if ($fechaInicio && $numeroCuotasTotal > 0) {
            $fechaVencimiento = Carbon::parse($fechaInicio)->addMonths($numeroCuotasTotal)->format('Y-m-d');
        }

        $estadoCredito = $this->determinarEstadoCredito($saldoRestante, $fechaVencimiento);
        $porcentajePagado = $montoTotal > 0 ? round(($totalPagado / $montoTotal) * 100, 2) : 0;
        $porcentajeCapitalPagado = $capitalInicial > 0 ? round(($totalCapitalPagado / $capitalInicial) * 100, 2) : 0;
        $porcentajeInteresPagado = $interesTotal > 0 ? round(($totalInteresPagado / $interesTotal) * 100, 2) : 0;

        $diasTranscurridos = $this->created_at ? smartRound($this->created_at->diffInDays(now())) : null;
        $diasRestantes = $fechaVencimiento ? smartRound(now()->diffInDays(Carbon::parse($fechaVencimiento), false)) : null;

        $mensajeFinal = $this->generarMensajeFinalPagos(
            $estadoCredito,
            $saldoRestante,
            $numeroPagosRealizados,
            $porcentajePagado,
            $diasRestantes
        );

        return [
            'capital_inicial' => $capitalInicial,
            'monto_total_credito' => round($montoTotal, 2),
            'interes_total_credito' => round($interesTotal, 2),
            'numero_cuotas_original' => $numeroCuotasOriginal,
            'numero_cuotas_actual' => $numeroCuotasTotal,
            'cuotas_pagadas' => $numeroPagosRealizados,
            'cuotas_restantes' => $cuotasRestantes,
            'sistema_amortizacion' => $sistema,
            'total_pagado' => $totalPagado,
            'capital_pagado' => $totalCapitalPagado,
            'interes_pagado' => $totalInteresPagado,
            'saldo_restante' => $saldoRestante,
            'capital_pendiente' => $capitalPendiente,
            'interes_pendiente' => $interesPendiente,
            'numero_pagos_realizados' => $numeroPagosRealizados,
            'promedio_monto_pago' => $promedioMontoPago,
            'pagos_estimados_restantes' => $cuotasRestantes,
            'fecha_ultimo_pago' => $fechaUltimoPago,
            'monto_ultimo_pago' => $montoUltimoPago,
            'porcentaje_pagado' => $porcentajePagado,
            'porcentaje_capital_pagado' => $porcentajeCapitalPagado,
            'porcentaje_interes_pagado' => $porcentajeInteresPagado,
            'fecha_inicio' => $fechaInicio,
            'fecha_vencimiento' => $fechaVencimiento,
            'dias_transcurridos' => $diasTranscurridos,
            'dias_restantes' => $diasRestantes,
            'estado_credito' => $estadoCredito,
            'mensaje_final' => $mensajeFinal,
            'tabla_amortizacion' => $tablaRecalculada,
            'tabla_modificada' => $numeroCuotasTotal !== $numeroCuotasOriginal,
            'cuota_siguiente' => $cuotaActual,
        ];
    }

    private function recalcularTablaAmortizacionPorSistema(
        float $capitalInicial,
        float $tasa,
        int $numeroCuotas,
        string $sistema,
        array $resultados,
        $pagos
    ): array {
        match (strtolower($sistema)) {
            'alemán', 'aleman' => $tabla = $this->recalcularTablaAleman($capitalInicial, $tasa, $numeroCuotas, $resultados, $pagos),
            'americano', 'american' => $tabla = $this->recalcularTablaAmericano($capitalInicial, $tasa, $numeroCuotas, $resultados, $pagos),
            default => $tabla = $this->recalcularTablaFrances($capitalInicial, $tasa, $resultados, $pagos),
        };

        return $tabla;
    }

    private function recalcularTablaFrances(float $capitalInicial, float $tasa, array $resultados, $pagos): array
    {
        $tabla = [];
        $saldoActual = $capitalInicial;
        $periodo = 1;
        $cuotaFija = $resultados['cuota_fija'] ?? 0;

        // Procesar cada pago realizado
        foreach ($pagos as $pago) {
            if ($saldoActual <= 0.01) {
                break;
            }

            $interesCalculado = $saldoActual * $tasa;
            $montoPagado = $pago->amount;
            $interesPagadoReal = $pago->interest_paid;
            $capitalPagadoReal = $pago->principal_paid;
            $nuevoSaldo = max(0, $saldoActual - $capitalPagadoReal);
            $tipoPago = $this->determinarTipoPago($montoPagado, $cuotaFija, $interesCalculado);
            $diferenciaCuota = $montoPagado - $cuotaFija;

            $tabla[] = [
                'periodo' => $periodo,
                'saldo_inicial' => round($saldoActual, 2),
                'cuota' => round($montoPagado, 2),
                'interes' => round($interesCalculado, 2),
                'interes_pagado' => round($interesPagadoReal, 2),
                'amortizacion' => round($capitalPagadoReal, 2),
                'saldo_final' => round($nuevoSaldo, 2),
                'pagado' => true,
                'fecha_pago' => $pago->payment_date->format('d/m/Y'),
                'pago_id' => $pago->id,
                'tipo_pago' => $tipoPago,
                'diferencia_cuota' => round($diferenciaCuota, 2),
                'exceso_interes' => round($interesPagadoReal - $interesCalculado, 2),
                'status' => $pago->status,
            ];

            $saldoActual = $nuevoSaldo;
            $periodo++;
        }

        // Generar cuotas pendientes
        if ($saldoActual > 0.01) {
            $maxCuotas = 1000;
            $cuotasGeneradas = 0;

            while ($saldoActual > 0.01 && $cuotasGeneradas < $maxCuotas) {
                $interes = $saldoActual * $tasa;

                if ($saldoActual + $interes <= $cuotaFija) {
                    $cuotaPendiente = $saldoActual + $interes;
                    $capitalCuota = $saldoActual;
                    $nuevoSaldo = 0;
                } else {
                    $cuotaPendiente = $cuotaFija;
                    $capitalCuota = $cuotaFija - $interes;
                    $nuevoSaldo = $saldoActual - $capitalCuota;
                }

                $tabla[] = [
                    'periodo' => $periodo,
                    'saldo_inicial' => round($saldoActual, 2),
                    'cuota' => round($cuotaPendiente, 2),
                    'interes' => round($interes, 2),
                    'interes_pagado' => 0,
                    'amortizacion' => round($capitalCuota, 2),
                    'saldo_final' => round($nuevoSaldo, 2),
                    'pagado' => false,
                    'fecha_pago' => null,
                    'pago_id' => null,
                    'tipo_pago' => 'normal',
                    'diferencia_cuota' => 0,
                    'exceso_interes' => 0,
                    'status' => 'pending',
                ];

                $saldoActual = $nuevoSaldo;
                $periodo++;
                $cuotasGeneradas++;
            }
        }

        return $tabla;
    }

    private function recalcularTablaAleman(float $capitalInicial, float $tasa, int $numeroCuotas, array $resultados, $pagos): array
    {
        $tabla = [];
        $saldoActual = $capitalInicial;
        $periodo = 1;

        // Amortización constante es la clave del sistema alemán
        $amortizacionConstante = $resultados['amortizacion_constante'] ?? ($capitalInicial / $numeroCuotas);

        // Procesar cada pago realizado
        foreach ($pagos as $pago) {
            if ($saldoActual <= 0.01) {
                break;
            }

            // Interés sobre saldo actual
            $interesCalculado = $saldoActual * $tasa;

            // Interés esperado para este periodo
            $cuotaEsperada = $amortizacionConstante + $interesCalculado;

            $montoPagado = $pago->amount;
            $capitalPagadoReal = $pago->principal_paid;
            $interesPagadoReal = $pago->interest_paid;

            $nuevoSaldo = max(0, $saldoActual - $capitalPagadoReal);
            $tipoPago = $this->determinarTipoPago($montoPagado, $cuotaEsperada, $interesCalculado);
            $diferenciaCuota = $montoPagado - $cuotaEsperada;

            $tabla[] = [
                'periodo' => $periodo,
                'saldo_inicial' => round($saldoActual, 2),
                'cuota' => round($montoPagado, 2),
                'interes' => round($interesCalculado, 2),
                'interes_pagado' => round($interesPagadoReal, 2),
                'amortizacion' => round($capitalPagadoReal, 2),
                'saldo_final' => round($nuevoSaldo, 2),
                'pagado' => true,
                'fecha_pago' => $pago->payment_date->format('d/m/Y'),
                'pago_id' => $pago->id,
                'tipo_pago' => $tipoPago,
                'diferencia_cuota' => round($diferenciaCuota, 2),
                'exceso_interes' => round($interesPagadoReal - $interesCalculado, 2),
                'status' => $pago->status,
            ];

            $saldoActual = $nuevoSaldo;
            $periodo++;
        }

        // Generar cuotas pendientes
        if ($saldoActual > 0.01) {
            while ($saldoActual > 0.01 && $periodo <= $numeroCuotas) {
                $interes = $saldoActual * $tasa;

                // En el último período
                if ($periodo === $numeroCuotas || $saldoActual <= $amortizacionConstante + 0.01) {
                    $capitalCuota = $saldoActual;
                    $cuotaPendiente = $capitalCuota + $interes;
                    $nuevoSaldo = 0;
                } else {
                    $capitalCuota = $amortizacionConstante;
                    $cuotaPendiente = $capitalCuota + $interes;
                    $nuevoSaldo = $saldoActual - $capitalCuota;
                }

                $tabla[] = [
                    'periodo' => $periodo,
                    'saldo_inicial' => round($saldoActual, 2),
                    'cuota' => round($cuotaPendiente, 2),
                    'interes' => round($interes, 2),
                    'interes_pagado' => 0,
                    'amortizacion' => round($capitalCuota, 2),
                    'saldo_final' => round($nuevoSaldo, 2),
                    'pagado' => false,
                    'fecha_pago' => null,
                    'pago_id' => null,
                    'tipo_pago' => 'normal',
                    'diferencia_cuota' => 0,
                    'exceso_interes' => 0,
                    'status' => 'pending',
                ];

                $saldoActual = $nuevoSaldo;
                $periodo++;
            }
        }

        return $tabla;
    }

    private function recalcularTablaAmericano(float $capitalInicial, float $tasa, int $numeroCuotas, array $resultados, $pagos): array
    {
        $tabla = [];
        $saldoActual = $capitalInicial;
        $periodo = 1;

        // Sistema americano: solo interés hasta el final
        $interesPeriodico = $capitalInicial * $tasa;

        // Procesar cada pago realizado
        foreach ($pagos as $pago) {
            if ($saldoActual <= 0.01) {
                break;
            }

            // Interés esperado para este período
            $interesCalculado = $saldoActual * $tasa;
            $montoPagado = $pago->amount;
            $capitalPagadoReal = $pago->principal_paid;
            $interesPagadoReal = $pago->interest_paid;

            // En sistema americano, solo se paga capital en última cuota
            $esUltimaCuota = ($periodo === $numeroCuotas);
            $cuotaEsperada = $esUltimaCuota
                ? $saldoActual + $interesCalculado  // Última: capital + interés
                : $interesCalculado;                // Otras: solo interés

            $nuevoSaldo = max(0, $saldoActual - $capitalPagadoReal);
            $tipoPago = $this->determinarTipoPago($montoPagado, $cuotaEsperada, $interesCalculado);
            $diferenciaCuota = $montoPagado - $cuotaEsperada;

            $tabla[] = [
                'periodo' => $periodo,
                'saldo_inicial' => round($saldoActual, 2),
                'cuota' => round($montoPagado, 2),
                'interes' => round($interesCalculado, 2),
                'interes_pagado' => round($interesPagadoReal, 2),
                'amortizacion' => round($capitalPagadoReal, 2),
                'saldo_final' => round($nuevoSaldo, 2),
                'pagado' => true,
                'fecha_pago' => $pago->payment_date->format('d/m/Y'),
                'pago_id' => $pago->id,
                'tipo_pago' => $tipoPago,
                'diferencia_cuota' => round($diferenciaCuota, 2),
                'exceso_interes' => round($interesPagadoReal - $interesCalculado, 2),
                'status' => $pago->status,
            ];

            $saldoActual = $nuevoSaldo;
            $periodo++;
        }

        // Generar cuotas pendientes
        if ($saldoActual > 0.01) {
            while ($saldoActual > 0.01 && $periodo <= $numeroCuotas) {
                $interes = $saldoActual * $tasa;

                // Última cuota: capital + interés
                if ($periodo === $numeroCuotas) {
                    $capitalCuota = $saldoActual;
                    $cuotaPendiente = $capitalCuota + $interes;
                    $nuevoSaldo = 0;
                } else {
                    // Cuotas intermedias: solo interés
                    $capitalCuota = 0;
                    $cuotaPendiente = $interes;
                    $nuevoSaldo = $saldoActual;
                }

                $tabla[] = [
                    'periodo' => $periodo,
                    'saldo_inicial' => round($saldoActual, 2),
                    'cuota' => round($cuotaPendiente, 2),
                    'interes' => round($interes, 2),
                    'interes_pagado' => 0,
                    'amortizacion' => round($capitalCuota, 2),
                    'saldo_final' => round($nuevoSaldo, 2),
                    'pagado' => false,
                    'fecha_pago' => null,
                    'pago_id' => null,
                    'tipo_pago' => 'normal',
                    'diferencia_cuota' => 0,
                    'exceso_interes' => 0,
                    'status' => 'pending',
                ];

                $saldoActual = $nuevoSaldo;
                $periodo++;
            }
        }

        return $tabla;
    }

    private function calcularDistribucionPagoManual(
        array $data,
        ?array $cuotaActual,
        float $saldoRestante,
        float $totalPagado,
        float $montoTotal,
        int $numeroPagos,
        float $capitalPendiente,
        float $interesPendiente
    ): array {
        $interesPagado = (float) ($data['interest_paid'] ?? 0);
        $capitalPagado = (float) ($data['principal_paid'] ?? 0);
        $amount = $interesPagado + $capitalPagado;

        $nuevoSaldo = max(0, $saldoRestante - $capitalPagado);

        $cuotaPropuesta = $cuotaActual ? $cuotaActual['cuota'] : 0;
        $diferencia = $amount - $cuotaPropuesta;

        if (abs($diferencia) < 0.01) {
            $tipoPago = 'normal';
        } elseif ($diferencia > 0.01) {
            $tipoPago = 'abono_extra';
        } else {
            $tipoPago = 'pago_parcial';
        }

        if ($nuevoSaldo <= 0.01) {
            $tipoPago = 'liquidacion';
            $nuevoSaldo = 0;
        }

        $porcentajeCompletado = $montoTotal > 0
            ? round((($totalPagado + $amount) / $montoTotal) * 100, 2)
            : 0;

        return [
            'principal_paid' => round($capitalPagado, 2),
            'interest_paid' => round($interesPagado, 2),
            'remaining_balance' => round($nuevoSaldo, 2),
            'metadata' => [
                'saldo_anterior' => round($saldoRestante, 2),
                'saldo_nuevo' => round($nuevoSaldo, 2),
                'capital_pendiente_anterior' => round($capitalPendiente, 2),
                'capital_pendiente_nuevo' => round($nuevoSaldo, 2),
                'interes_pendiente_anterior' => round($interesPendiente, 2),
                'interes_pendiente_nuevo' => round(max(0, $interesPendiente - $interesPagado), 2),
                'numero_pago' => $numeroPagos + 1,
                'cuota_numero' => $cuotaActual ? $cuotaActual['periodo'] : null,
                'cuota_propuesta' => round($cuotaPropuesta, 2),
                'tipo_pago' => $tipoPago,
                'diferencia_cuota' => round($diferencia, 2),
                'porcentaje_completado' => $porcentajeCompletado,
                'modo_ingreso' => 'manual',
                'tipo_calculo' => $this->type->value,
                'fecha_registro' => now()->toDateTimeString(),
            ],
        ];
    }

    private function calcularDistribucionPagoAmortizacion(
        float $amount,
        ?array $cuotaActual,
        float $saldoRestante,
        float $tasa,
        float $totalPagado,
        float $montoTotal,
        int $numeroPagos,
        float $capitalPendiente,
        float $interesPendiente,
        string $sistema
    ): array {
        if (! $cuotaActual || $amount <= 0) {
            return [
                'principal_paid' => 0,
                'interest_paid' => 0,
                'remaining_balance' => $saldoRestante,
                'metadata' => [],
            ];
        }

        $interesCuota = $cuotaActual['interes'];
        $capitalCuota = $cuotaActual['amortizacion'];
        $cuotaPropuesta = $cuotaActual['cuota'];

        // CASO 1: Pago exacto de la cuota
        if (abs($amount - $cuotaPropuesta) < 0.01) {
            $interesPagado = $interesCuota;
            $capitalPagado = $capitalCuota;
            $nuevoSaldo = $cuotaActual['saldo_final'];
            $tipoPago = 'normal';
        }
        // CASO 2: Pago mayor a la cuota (abono extra a capital)
        elseif ($amount > $cuotaPropuesta) {
            $interesPagado = $interesCuota;
            $capitalCuotaNormal = $capitalCuota;
            $excedenteCapital = $amount - $cuotaPropuesta;
            $capitalPagado = $capitalCuotaNormal + $excedenteCapital;
            $nuevoSaldo = max(0, $saldoRestante - $capitalPagado);
            $tipoPago = 'abono_extra';
        }
        // CASO 3: Pago menor a la cuota (pago parcial)
        elseif ($amount < $cuotaPropuesta) {
            if ($amount <= $interesCuota) {
                $interesPagado = $amount;
                $capitalPagado = 0;
            } else {
                $interesPagado = $interesCuota;
                $capitalPagado = $amount - $interesCuota;
            }
            $nuevoSaldo = max(0, $saldoRestante - $capitalPagado);
            $tipoPago = 'pago_parcial';
        }
        // CASO 4: Liquidación
        else {
            $interesPagado = min($amount, $interesCuota);
            $capitalPagado = min($amount - $interesPagado, $saldoRestante);
            $nuevoSaldo = max(0, $saldoRestante - $capitalPagado);
            $tipoPago = $nuevoSaldo <= 0.01 ? 'liquidacion' : 'normal';
        }

        $porcentajeCompletado = $montoTotal > 0
            ? round((($totalPagado + $amount) / $montoTotal) * 100, 2)
            : 0;

        return [
            'principal_paid' => round($capitalPagado, 2),
            'interest_paid' => round($interesPagado, 2),
            'remaining_balance' => round($nuevoSaldo, 2),
            'metadata' => [
                'saldo_anterior' => round($saldoRestante, 2),
                'saldo_nuevo' => round($nuevoSaldo, 2),
                'capital_pendiente_anterior' => round($capitalPendiente, 2),
                'capital_pendiente_nuevo' => round($nuevoSaldo, 2),
                'interes_pendiente_anterior' => round($interesPendiente, 2),
                'interes_pendiente_nuevo' => round(max(0, $interesPendiente - $interesPagado), 2),
                'numero_pago' => $numeroPagos + 1,
                'cuota_numero' => $cuotaActual['periodo'],
                'cuota_propuesta' => round($cuotaPropuesta, 2),
                'tipo_pago' => $tipoPago,
                'diferencia_cuota' => round($amount - $cuotaPropuesta, 2),
                'porcentaje_completado' => $porcentajeCompletado,
                'tipo_calculo' => $this->type->value,
                'fecha_registro' => now()->toDateTimeString(),
            ],
        ];
    }

    private function determinarTipoPago(float $montoPagado, float $cuotaFija, float $interesCalculado): string
    {
        $diferencia = $montoPagado - $cuotaFija;

        if (abs($diferencia) < 0.01) {
            return 'normal';
        } elseif ($diferencia > 0.01) {
            return 'abono_extra';
        } else {
            return 'pago_parcial';
        }
    }

    private function obtenerCuotaActualAmortizacion(array $tabla): ?array
    {
        foreach ($tabla as $cuota) {
            if (! ($cuota['pagado'] ?? false)) {
                return $cuota;
            }
        }

        return end($tabla) ?: null;
    }

    private function calculatePaymentDistributionAmortizacion(Get $get, Set $set, ?array $data = null): void
    {
        $amount = isset($data['amount']) ? (float) $data['amount'] : null;
        $interest = isset($data['interest_paid']) ? (float) $data['interest_paid'] : null;
        $principal = isset($data['principal_paid']) ? (float) $data['principal_paid'] : null;

        if ($amount !== null && $amount > 0) {
            $paymentData = $this->getPagosDataAmortizacion($data);

            $set('interest_paid', $paymentData['interest_paid']);
            $set('principal_paid', $paymentData['principal_paid']);
            $set('remaining_balance', $paymentData['remaining_balance']);

            return;
        }

        if ($interest !== null || $principal !== null) {
            $interest = $interest ?? 0.0;
            $principal = $principal ?? 0.0;
            $total = $interest + $principal;

            $set('amount', round($total, 2));

            $pagosData = $this->getPagosDataAmortizacion();
            $set('remaining_balance', max(0, ($pagosData['saldo_restante'] ?? 0) - $principal));
        }
    }

    /**
     * Datos para Anualidad (placeholder - implementar según necesidad)
     */
    public function getPagosDataAnualidad(): array
    {
        // Implementar lógica específica para anualidad
        return [];
    }

    /**
     * Datos para Gradientes (placeholder - implementar según necesidad)
     */
    public function getPagosDataGradientes(): array
    {
        // Implementar lógica específica para gradientes
        return [];
    }

    /**
     * Datos para TIR (placeholder - implementar según necesidad)
     */
    public function getPagosDataTIR(): array
    {
        // Implementar lógica específica para TIR
        return [];
    }

    /*
    |--------------------------------------------------------------------------
    | Métodos auxiliares
    |--------------------------------------------------------------------------
    */

    /**
     * Determina el estado del crédito basado en pagos y fechas
     */
    public function determinarEstadoCredito(float $saldoRestante, ?string $fechaVencimiento): string
    {
        // Si está marcado como completado
        if ($this->status === 'completed' || $saldoRestante <= 0) {
            return 'Pagado';
        }

        // Si está cancelado
        if ($this->status === 'cancelled') {
            return 'Cancelado';
        }

        // Verificar si está vencido
        if ($fechaVencimiento) {
            $hoy = now();
            $vencimiento = \Carbon\Carbon::parse($fechaVencimiento);

            if ($hoy->greaterThan($vencimiento) && $saldoRestante > 0) {
                return 'Vencido';
            }
        }

        // Por defecto es activo
        return 'Activo';
    }

    /**
     * Genera un mensaje final personalizado según el estado del crédito
     */
    public function generarMensajeFinal(
        string $estadoCredito,
        float $saldoRestante,
        float $montoFinal,
        float $totalPagado
    ): ?string {
        switch (strtolower($estadoCredito)) {
            case 'pagado':
            case 'liquidado':
                return '¡Felicitaciones! El crédito ha sido pagado en su totalidad.';

            case 'vencido':
                $fechaFinal = $this->inputs['fecha_final'] ?? null;
                if ($fechaFinal) {
                    $diasVencidos = now()->diffInDays(\Carbon\Carbon::parse($fechaFinal));

                    return "El crédito está vencido desde hace {$diasVencidos} días. Saldo pendiente: $".number_format($saldoRestante, 2);
                }

                return 'El crédito está vencido. Saldo pendiente: $'.number_format($saldoRestante, 2);

            case 'activo':
            case 'vigente':
                $porcentajePagado = $montoFinal > 0 ? round(($totalPagado / $montoFinal) * 100, 1) : 0;

                if ($porcentajePagado >= 75) {
                    return "¡Excelente progreso! Has completado el {$porcentajePagado}% del crédito.";
                } elseif ($porcentajePagado >= 50) {
                    return "Vas por buen camino. Has completado el {$porcentajePagado}% del crédito.";
                } elseif ($porcentajePagado >= 25) {
                    return "Crédito en curso. Has completado el {$porcentajePagado}% del total.";
                } else {
                    return 'Mantén tus pagos al día para evitar intereses moratorios.';
                }

            case 'cancelado':
                return 'Este crédito ha sido cancelado.';

            default:
                return null;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors útiles
    |--------------------------------------------------------------------------
    */

    /**
     * Obtiene el total pagado
     */
    public function getTotalPagadoAttribute(): float
    {
        return $this->payments()
            ->where('status', 'completed')
            ->sum('amount');
    }

    /**
     * Obtiene el saldo restante
     */
    public function getSaldoRestanteAttribute(): float
    {
        $montoFinal = $this->inputs['resultado_calculado'] ?? $this->inputs['monto_final'] ?? 0;
        $saldo = $montoFinal - $this->total_pagado;

        return max(0, $saldo);
    }

    /**
     * Verifica si el crédito está completamente pagado
     */
    public function getEstaPagadoAttribute(): bool
    {
        return $this->saldo_restante <= 0;
    }
}
