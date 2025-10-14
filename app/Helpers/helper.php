<?php

use Carbon\Carbon;

if (! function_exists('smartRound')) {
    function smartRound(float $value): float
    {
        // Redondeamos a 3 decimales
        $rounded = round($value, 3);

        // Convertimos a string y eliminamos ceros finales en decimales
        $str = rtrim(rtrim(number_format($rounded, 3, '.', ''), '0'), '.');

        return (float) $str;
    }
}

if (! function_exists('calcularTiempo')) {
    function calcularTiempo(callable $set, callable $get): void
    {
        $anio = $get('anio');
        $mes = $get('mes');
        $dia = $get('dia');

        $anioConvertido = $anio + ($mes / 12) + ($dia / 365.25);
        $set('tiempo', smartRound($anioConvertido));
    }
}

if (! function_exists('calcularTiempoDesdeFechas')) {
    function calcularTiempoDesdeFechas(callable $set, callable $get): void
    {
        $fechaInicio = $get('fecha_inicio');
        $fechaFinal = $get('fecha_final');

        if ($fechaInicio && $fechaFinal) {
            $inicio = Carbon::parse($fechaInicio);
            $final = Carbon::parse($fechaFinal);

            if ($final->gt($inicio)) {
                // Calcular diferencia en años (más preciso)
                $tiempoEnAnios = $inicio->diffInDays($final) / 365.25;
                $set('tiempo', smartRound($tiempoEnAnios));
            } else {
                $set('tiempo', null);
            }
        }
    }
}
if (! function_exists('calcularNumeroPagosDesdeTiempo')) {
    function calcularNumeroPagosDesdeTiempo(callable $set, callable $get): void
    {
        $modoTiempoPagos = $get('modo_tiempo_pagos');

        if ($modoTiempoPagos === 'anios_frecuencia') {
            $tiempoAnios = $get('tiempo');
            $frecuencia = $get('frecuencia_anios') ?: 1;
            if (is_numeric($tiempoAnios) && is_numeric($frecuencia)) {
                // Calcular número de períodos completos
                $numeroCompletoPagos = calcularPeriodosCompletos($tiempoAnios, $frecuencia);
                $set('numero_pagos_calculado_anios', $numeroCompletoPagos);
                $set('numero_pagos', $numeroCompletoPagos);
            }
        } elseif ($modoTiempoPagos === 'fechas_frecuencia') {
            $tiempoCalculado = $get('tiempo');
            $frecuencia = $get('frecuencia_anios') ?: 1;
            if (is_numeric($tiempoCalculado) && is_numeric($frecuencia)) {
                // Calcular número de períodos completos
                $numeroCompletoPagos = calcularPeriodosCompletos($tiempoCalculado, $frecuencia);
                $set('numero_pagos_calculado_fechas', $numeroCompletoPagos);
                $set('numero_pagos', $numeroCompletoPagos);
            }
        } elseif ($modoTiempoPagos === 'manual') {
            // Asegurar que el campo manual también actualice el campo oculto
            $numeroPagosManual = $get('numero_pagos');
            if (is_numeric($numeroPagosManual)) {
                $set('numero_pagos', $numeroPagosManual);
            }
        }
    }

}
if (! function_exists('calcularPeriodosCompletos')) {
    /**
     * Calcula el número de períodos completos basado en el tiempo y frecuencia
     */
    function calcularPeriodosCompletos(float $tiempoAnios, float $frecuencia): int
    {
        // Duración de cada período en años
        $duracionPeriodoAnios = 1 / $frecuencia;

        // Número de períodos completos que caben en el tiempo dado
        $periodosCompletos = floor($tiempoAnios / $duracionPeriodoAnios);

        return (int) $periodosCompletos;
    }
}
