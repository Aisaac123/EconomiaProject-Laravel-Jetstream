<?php

namespace App\Models;

use App\Enums\CalculationType;
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
    | Métodos para datos de pagos (buildPagosHtml)
    |--------------------------------------------------------------------------
    */

    /**
     * Prepara los datos para buildPagosHtml según el tipo de crédito
     */
    public function getPagosData(): array
    {
        return match ($this->type) {
            CalculationType::SIMPLE => $this->getPagosDataSimple(),
            CalculationType::COMPUESTO => $this->getPagosDataCompuesto(),
            CalculationType::AMORTIZACION => $this->getPagosDataAmortizacion(),
            CalculationType::ANUALIDAD => $this->getPagosDataAnualidad(),
            CalculationType::GRADIENTES => $this->getPagosDataGradientes(),
            CalculationType::TIR => $this->getPagosDataTIR(),
            default => [],
        };
    }

    /**
     * Datos para Interés Simple
     */
    /**
     * Datos para Interés Simple - Información de Pagos
     */
    public function getPagosDataSimple(): array
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

        // Obtener último pago
        $ultimoPago = $pagosCompletados->last();
        $fechaUltimoPago = $ultimoPago ? $ultimoPago->payment_date->format('Y-m-d') : null;
        $montoUltimoPago = $ultimoPago ? $ultimoPago->amount : null;

        // Calcular promedio de pago
        $promedioMontoPago = $numeroPagosRealizados > 0 ? $totalPagado / $numeroPagosRealizados : 0;

        // Estimar pagos restantes (si se mantiene el promedio)
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

        // Determinar estado
        $estadoCredito = $this->determinarEstadoCredito($saldoRestante, $fechaVencimiento);

        // Calcular porcentajes
        $porcentajePagado = $montoTotal > 0 ? round(($totalPagado / $montoTotal) * 100, 2) : 0;
        $porcentajeCapitalPagado = $capitalInicial > 0 ? round(($totalCapitalPagado / $capitalInicial) * 100, 2) : 0;
        $porcentajeInteresPagado = $interesTotal > 0 ? round(($totalInteresPagado / $interesTotal) * 100, 2) : 0;

        // Calcular días transcurridos y restantes
        $diasTranscurridos = $this->created_at ? smartRound($this->created_at->diffInDays(now())) : null;
        $diasRestantes = $fechaVencimiento ? smartRound(now()->diffInDays(\Carbon\Carbon::parse($fechaVencimiento), false)) : null;

        // Generar mensaje contextual
        $mensajeFinal = $this->generarMensajeFinalPagos(
            $estadoCredito,
            $saldoRestante,
            $numeroPagosRealizados,
            $porcentajePagado,
            $diasRestantes
        );

        return [
            // Información del crédito
            'capital_inicial' => $capitalInicial,
            'monto_total_credito' => $montoTotal,
            'interes_total_credito' => $interesTotal,

            // Totales pagados
            'total_pagado' => $totalPagado,
            'capital_pagado' => $totalCapitalPagado,
            'interes_pagado' => $totalInteresPagado,

            // Saldos pendientes
            'saldo_restante' => $saldoRestante,
            'capital_pendiente' => $capitalPendiente,
            'interes_pendiente' => $interesPendiente,

            // Información de pagos
            'numero_pagos_realizados' => $numeroPagosRealizados,
            'promedio_monto_pago' => $promedioMontoPago,
            'pagos_estimados_restantes' => $pagosEstimadosRestantes,
            'fecha_ultimo_pago' => $fechaUltimoPago,
            'monto_ultimo_pago' => $montoUltimoPago,

            // Porcentajes
            'porcentaje_pagado' => $porcentajePagado,
            'porcentaje_capital_pagado' => $porcentajeCapitalPagado,
            'porcentaje_interes_pagado' => $porcentajeInteresPagado,

            // Fechas
            'fecha_inicio' => $fechaInicio,
            'fecha_vencimiento' => $fechaVencimiento,
            'dias_transcurridos' => $diasTranscurridos,
            'dias_restantes' => $diasRestantes,

            // Estado y mensaje
            'estado_credito' => $estadoCredito,
            'mensaje_final' => $mensajeFinal,
        ];
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
                return "Crédito vencido. Saldo pendiente: $" . number_format($saldoRestante, 2) . ". Se han realizado {$numeroPagos} pagos hasta la fecha.";

            case 'activo':
            case 'vigente':
                if ($numeroPagos === 0) {
                    if ($diasRestantes !== null && $diasRestantes > 0) {
                        return "Crédito activo. Aún no se han registrado pagos. Quedan {$diasRestantes} días para el vencimiento.";
                    }
                    return "Crédito activo. Aún no se han registrado pagos.";
                }

                $mensaje = "Progreso: {$porcentajePagado}% completado con {$numeroPagos} pago(s) realizado(s).";

                if ($diasRestantes !== null) {
                    if ($diasRestantes > 0) {
                        $mensaje .= " Quedan {$diasRestantes} días para el vencimiento.";
                    } elseif ($diasRestantes === 0) {
                        $mensaje .= " ¡Vence hoy!";
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
     * Datos para Interés Compuesto (placeholder - implementar según necesidad)
     */
    public function getPagosDataCompuesto(): array
    {
        // Implementar lógica específica para compuesto
        return [];
    }

    /**
     * Datos para Amortización (placeholder - implementar según necesidad)
     */
    public function getPagosDataAmortizacion(): array
    {
        // Implementar lógica específica para amortización
        return [];
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
                    return "El crédito está vencido desde hace {$diasVencidos} días. Saldo pendiente: $" . number_format($saldoRestante, 2);
                }
                return "El crédito está vencido. Saldo pendiente: $" . number_format($saldoRestante, 2);

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
                    return "Mantén tus pagos al día para evitar intereses moratorios.";
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
