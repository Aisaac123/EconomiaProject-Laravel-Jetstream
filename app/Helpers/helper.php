<?php

use Carbon\Carbon;

if (! function_exists('smartRound')) {
    function smartRound(float $value): float
    {
        // Si está casi en un entero (±0.01), devolver como entero
        if (abs($value - round($value)) < 0.01) {
            return round($value);
        }

        // Si el segundo decimal es 0 ó >= 5 → 1 decimal
        $oneDecimal = round($value, 1);
        if (abs($value - $oneDecimal) < 0.05) {
            return $oneDecimal;
        }

        // En otros casos → 2 decimales
        return round($value, 2);
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
                $n = $tiempoAnios * $frecuencia;
                $set('numero_pagos_calculado_anios', round($n));
                $set('numero_pagos', round($n));
            }
        } elseif ($modoTiempoPagos === 'fechas_frecuencia') {
            $tiempoCalculado = $get('tiempo');
            $frecuencia = $get('frecuencia_anios') ?: 1;
            if (is_numeric($tiempoCalculado) && is_numeric($frecuencia)) {
                $n = $tiempoCalculado * $frecuencia;
                $set('numero_pagos_calculado_fechas', round($n));
                $set('numero_pagos', round($n));
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

