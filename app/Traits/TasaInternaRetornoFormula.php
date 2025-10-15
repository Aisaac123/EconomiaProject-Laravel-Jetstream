<?php

namespace App\Traits;

trait TasaInternaRetornoFormula
{
    use HelpersFormula;

    private function calculateTIR(array $data): array
    {
        $camposCalculados = [];
        $resultados = [];
        $messages = [];

        // Validar tipo de flujo
        $tipoFlujo = $data['tipo_flujo'] ?? 'constantes';

        // Obtener flujos de caja
        $flujos = [];
        $inversionInicial = ! empty($data['inversion_inicial']) ? (float) $data['inversion_inicial'] : null;

        if ($tipoFlujo === 'constantes') {
            $flujoConstante = ! empty($data['flujo_constante']) ? (float) $data['flujo_constante'] : null;
            $numeroPeriodos = ! empty($data['numero_periodos']) ? (int) $data['numero_periodos'] : null;

            if ($flujoConstante === null || $numeroPeriodos === null) {
                return [
                    'error' => true,
                    'message' => 'Debes ingresar el flujo constante y el número de períodos.',
                ];
            }

            // Crear array de flujos constantes
            for ($i = 1; $i <= $numeroPeriodos; $i++) {
                $flujos[$i] = $flujoConstante;
            }
        } else {
            // Flujos variables - CORRECCIÓN AQUÍ
            $flujosVariables = $data['flujos_variables'] ?? [];

            if (empty($flujosVariables)) {
                return [
                    'error' => true,
                    'message' => 'Debes ingresar al menos un flujo de caja.',
                ];
            }

            // Ordenar por período y construir array correctamente
            $flujosOrdenados = [];
            foreach ($flujosVariables as $flujoData) {
                if (isset($flujoData['periodo']) && isset($flujoData['flujo'])) {
                    $periodo = (int) $flujoData['periodo'];
                    $flujosOrdenados[$periodo] = (float) $flujoData['flujo'];
                }
            }

            // Ordenar por clave (período)
            ksort($flujosOrdenados);

            // Asignar al array de flujos
            $flujos = $flujosOrdenados;
        }

        if ($inversionInicial === null) {
            return [
                'error' => true,
                'message' => 'Debes ingresar la inversión inicial.',
            ];
        }

        // Convertir inversión inicial a negativo si es positiva (inversión = salida de dinero)
        $inversionInicialNegativa = $inversionInicial > 0 ? -$inversionInicial : $inversionInicial;

        // Agregar inversión inicial como período 0
        $flujos[0] = $inversionInicialNegativa;
        ksort($flujos);

        $periodicidadTasa = $data['periodicidad_tasa'] ?? 1;
        $tasaDescuento = ! empty($data['tasa_descuento']) ? (float) $data['tasa_descuento'] : null;

        // Nuevo: Determinar tipo de cálculo (TIR o TIRM)
        $tipoCalculo = $data['tipo_calculo'] ?? 'tir';
        $tasaFinanciamiento = null;
        $tasaReinversion = null;

        if ($tipoCalculo === 'tirm') {
            $tasaFinanciamiento = ! empty($data['tasa_financiamiento']) ? (float) $data['tasa_financiamiento'] / 100 : null;
            $tasaReinversion = ! empty($data['tasa_reinversion']) ? (float) $data['tasa_reinversion'] / 100 : null;

            if ($tasaFinanciamiento === null || $tasaReinversion === null) {
                return [
                    'error' => true,
                    'message' => 'Para calcular TIRM debes ingresar la tasa de financiamiento y la tasa de reinversión.',
                ];
            }
        }

        // Contar cambios de signo para advertencias
        $cambiosSigno = $this->contarCambiosDeSigno($flujos);

        try {
            // Calcular según el tipo seleccionado
            if ($tipoCalculo === 'tirm') {
                // ========== CALCULAR TIRM ==========
                $tirm = $this->calcularTIRM($flujos, $tasaFinanciamiento, $tasaReinversion);

                if ($tirm === null) {
                    return [
                        'error' => true,
                        'message' => 'No se pudo calcular la TIRM. Verifique los flujos y las tasas ingresadas.',
                    ];
                }

                $resultados['tirm'] = round($tirm, 4);
                $resultados['tasa_financiamiento'] = ($tasaFinanciamiento * 100);
                $resultados['tasa_reinversion'] = ($tasaReinversion * 100);
                $camposCalculados[] = 'tirm';
                $messages[] = 'TIRM calculada: '.number_format($resultados['tirm'], 4).'%';

                // Si hay tasa de descuento, calcular VPN también
                if ($tasaDescuento !== null) {
                    $tasaAnual = $tasaDescuento;
                    if ($periodicidadTasa != 1) {
                        $tasaAnual = $tasaDescuento * $periodicidadTasa;
                    }
                    $tasaPorPeriodo = $tasaAnual / 100;

                    $vpn = $this->calcularVPN($flujos, $tasaPorPeriodo);
                    $resultados['vpn'] = round($vpn, 2);
                    $camposCalculados[] = 'vpn';
                    $messages[] = 'VPN calculado: $'.number_format($resultados['vpn'], 2);
                    $resultados['tasa_descuento'] = $tasaDescuento;
                } else {
                    // Usar TIRM para la tabla de flujos
                    $tasaPorPeriodo = $tirm / 100;
                }

            } else {
                // ========== CALCULAR TIR (código original) ==========
                $tir = $this->calcularTIR($flujos, $periodicidadTasa);

                if ($tir !== null) {
                    $resultados['tir'] = round($tir, 4);
                    $camposCalculados[] = 'tir';
                    $messages[] = 'TIR calculada: '.number_format($resultados['tir'], 4).'%';

                    // Advertencia si hay múltiples cambios de signo
                    if ($cambiosSigno > 1) {
                        $messages[] = '⚠️ Los flujos tienen '.$cambiosSigno.' cambios de signo. Pueden existir múltiples TIRs. Considere usar TIRM para mayor precisión.';
                    }
                }

                // Determinar qué más calcular
                if ($tasaDescuento === null) {
                    // Solo TIR
                    if ($tir === null) {
                        return [
                            'error' => true,
                            'message' => 'No se pudo calcular la TIR. Verifique que los flujos permitan obtener una tasa de retorno.',
                        ];
                    }

                    // Usar TIR para generar la tabla de flujos
                    $tasaPorPeriodo = ($tir / 100);

                } else {
                    // Calcular VPN con tasa dada
                    $tasaAnual = $tasaDescuento;
                    if ($periodicidadTasa != 1) {
                        $tasaAnual = $tasaDescuento * $periodicidadTasa;
                    }
                    $tasaPorPeriodo = $tasaAnual / 100;

                    $vpn = $this->calcularVPN($flujos, $tasaPorPeriodo);
                    $resultados['vpn'] = round($vpn, 2);
                    $camposCalculados[] = 'vpn';
                    $messages[] = 'VPN calculado: $'.number_format($resultados['vpn'], 2);

                    $resultados['tasa_descuento'] = $tasaDescuento;
                }
            }

            // Calcular valores adicionales (común para TIR y TIRM)
            $resultados['inversion_inicial'] = abs($inversionInicial);
            $resultados['numero_periodos'] = count($flujos) - 1;
            $resultados['suma_flujos'] = round(array_sum(array_slice($flujos, 1)), 2);

            // Calcular ROI
            $sumaFlujos = $resultados['suma_flujos'];
            $inversionAbs = abs($inversionInicial);
            $resultados['roi'] = round((($sumaFlujos - $inversionAbs) / $inversionAbs) * 100, 2);

            // Calcular Payback Period
            $resultados['payback_period'] = $this->calcularPaybackPeriod($flujos);

            // Calcular Rentabilidad (solo si tenemos VPN)
            if (isset($resultados['vpn'])) {
                $resultados['rentabilidad'] = round(($resultados['vpn'] / $inversionAbs) * 100, 2);
            }

            // Generar tabla de flujos
            $tablaFlujos = $this->generarTablaFlujos($flujos, $tasaPorPeriodo);

            // Decisión de inversión
            $toleranciaVPN = 0.01;
            $toleranciaVALOR = 0.01;

            if ($tipoCalculo === 'tirm') {
                // Decisiones con TIRM
                if (isset($resultados['vpn']) && $tasaDescuento !== null) {
                    // TIRM + VPN
                    $vpn = (float) $resultados['vpn'];
                    $tirm = (float) $resultados['tirm'];
                    $tasaDesc = (float) $tasaDescuento;

                    $vpnCritico = abs($vpn) < $toleranciaVPN;
                    $tirmCritica = abs($tirm - $tasaDesc) < $toleranciaVALOR;

                    if ($vpnCritico || $tirmCritica) {
                        $resultados['decision'] = 'Crítico';
                        $resultados['justificacion'] = 'Proyecto en punto crítico. La TIRM ('.number_format($tirm, 4).'%) está cerca de la tasa de descuento o el VPN es cercano a cero. NO se recomienda por falta de margen de seguridad.';
                    } elseif ($vpn > 0 && $tirm > $tasaDesc) {
                        $resultados['decision'] = 'Aceptar';
                        $margen = $tirm - $tasaDesc;
                        $resultados['justificacion'] = 'El VPN es positivo ($'.number_format($vpn, 2).') y la TIRM ('.number_format($tirm, 4).'%) supera la tasa de descuento ('.number_format($tasaDesc, 4).'%) por '.number_format($margen, 2).' puntos porcentuales. Proyecto viable.';
                    } else {
                        $resultados['decision'] = 'Rechazar';
                        $resultados['justificacion'] = 'El VPN es negativo o la TIRM no supera la tasa de descuento. Proyecto no viable.';
                    }
                } else {
                    // Solo TIRM sin tasa de descuento
                    $tirm = (float) $resultados['tirm'];
                    $tirmCritica = abs($tirm) < $toleranciaVALOR;

                    if ($tirmCritica) {
                        $resultados['decision'] = 'Crítico';
                        $resultados['justificacion'] = 'La TIRM es prácticamente cero ('.number_format($tirm, 4).'%). El proyecto apenas recupera la inversión. NO recomendado.';
                    } elseif ($tirm > 0) {
                        $resultados['decision'] = 'Aceptar';
                        $resultados['justificacion'] = 'La TIRM ('.number_format($tirm, 4).'%) es positiva. Proyecto rentable si supera su costo de oportunidad.';
                    } else {
                        $resultados['decision'] = 'Rechazar';
                        $resultados['justificacion'] = 'La TIRM ('.number_format($tirm, 4).'%) es negativa. Proyecto no viable.';
                    }
                }
            } else {
                // Decisiones con TIR (código que ya tenías, lo dejo igual)
                if (isset($resultados['vpn']) && isset($resultados['tir']) && $tasaDescuento !== null) {
                    // ... tu código existente de decisiones TIR ...
                    $vpn = (float) $resultados['vpn'];
                    $tir = (float) $resultados['tir'];
                    $tasaDesc = (float) $tasaDescuento;

                    $vpnCritico = abs($vpn) < $toleranciaVPN;
                    $tirCritica = abs($tir - $tasaDesc) < $toleranciaVALOR;

                    if ($vpnCritico || $tirCritica) {
                        $resultados['decision'] = 'Crítico';
                        $partes = [];

                        if ($vpnCritico) {
                            $partes[] = 'el VPN es prácticamente cero ($'.number_format($vpn, 2).')';
                        }
                        if ($tirCritica) {
                            $partes[] = 'la TIR ('.number_format($tir, 4).'%) está en el punto de equilibrio con la tasa de descuento ('.number_format($tasaDesc, 4).'%)';
                        }

                        $resultados['justificacion'] = 'Proyecto en punto crítico: '.implode(' y ', $partes).'. El proyecto no genera ni destruye valor significativo. En la práctica, NO se recomienda debido a la falta de margen de seguridad y riesgo de pérdidas ante variaciones.';

                    } elseif ($vpn > 0 && $tir > $tasaDesc) {
                        $resultados['decision'] = 'Aceptar';
                        $margenTIR = $tir - $tasaDesc;
                        $resultados['justificacion'] = 'El VPN es positivo ($'.number_format($vpn, 2).') y la TIR ('.number_format($tir, 4).'%) supera la tasa de descuento ('.number_format($tasaDesc, 4).'%) por '.number_format($margenTIR, 2).' puntos porcentuales. El proyecto genera valor.';

                    } elseif ($vpn < 0 || $tir < $tasaDesc) {
                        $resultados['decision'] = 'Rechazar';
                        $diferenciaTIR = $tasaDesc - $tir;
                        $resultados['justificacion'] = 'El VPN es negativo ($'.number_format($vpn, 2).') y/o la TIR ('.number_format($tir, 4).'%) es inferior a la tasa de descuento ('.number_format($tasaDesc, 4).'%) por '.number_format($diferenciaTIR, 2).' puntos porcentuales. El proyecto destruye valor.';
                    }

                } elseif (isset($resultados['vpn']) && $tasaDescuento !== null) {
                    // ... resto del código existente ...
                    $vpn = (float) $resultados['vpn'];
                    $tasaDesc = (float) $tasaDescuento;
                    $vpnCritico = abs($vpn) < $toleranciaVPN;

                    if ($vpnCritico) {
                        $resultados['decision'] = 'Crítico';
                        $resultados['justificacion'] = 'Proyecto en punto crítico: el VPN es prácticamente cero ($'.number_format($vpn, 2).') a la tasa de descuento del '.number_format($tasaDesc, 4).'%. El proyecto no genera ni destruye valor significativo. En la práctica, NO se recomienda por falta de margen de seguridad.';

                    } elseif ($vpn > 0) {
                        $resultados['decision'] = 'Aceptar';
                        $resultados['justificacion'] = 'El VPN es positivo ($'.number_format($vpn, 2).'), el proyecto genera valor a la tasa de descuento del '.number_format($tasaDesc, 4).'%.';

                    } else {
                        $resultados['decision'] = 'Rechazar';
                        $resultados['justificacion'] = 'El VPN es negativo ($'.number_format($vpn, 2).'), el proyecto destruye valor a la tasa de descuento del '.number_format($tasaDesc, 4).'%.';
                    }

                } elseif (isset($resultados['tir']) && $tasaDescuento === null) {
                    $tir = (float) $resultados['tir'];
                    $tirCritica = abs($tir) < $toleranciaVALOR;

                    if ($tirCritica) {
                        $resultados['decision'] = 'Crítico';
                        $resultados['justificacion'] = 'Proyecto en punto crítico: la TIR es prácticamente cero ('.number_format($tir, 4).'%). El proyecto apenas recupera la inversión sin generar rentabilidad. En la práctica, NO se recomienda ejecutar este tipo de proyectos por falta de retorno y exposición al riesgo.';

                    } elseif ($tir > 0) {
                        $resultados['decision'] = 'Aceptar';
                        $resultados['justificacion'] = 'La TIR ('.number_format($tir, 4).'%) es positiva. El proyecto es rentable si esta tasa supera su costo de oportunidad y compárela con alternativas de inversión disponibles.';

                    } else {
                        $resultados['decision'] = 'Rechazar';
                        $resultados['justificacion'] = 'La TIR ('.number_format($tir, 4).'%) es negativa. El proyecto genera pérdidas y destruye capital. NO es viable financieramente.';
                    }
                }
            }

        } catch (\Throwable $e) {
            return [
                'error' => true,
                'message' => 'Error en cálculo: '.$e->getMessage(),
            ];
        }

        return [
            'error' => false,
            'data' => array_merge($data, [
                'campos_calculados' => json_encode($camposCalculados),
                'resultados_calculados' => json_encode($resultados),
                'tabla_flujos' => json_encode($tablaFlujos),
                'mensaje_calculado' => implode('. ', $messages),
            ]),
            'message' => implode('. ', $messages),
        ];
    }

    /**
     * Calcula el período de recuperación (Payback Period)
     */
    private function calcularPaybackPeriod(array $flujos): ?float
    {
        $acumulado = 0;
        $periodoAnterior = 0;
        $acumuladoAnterior = 0;

        foreach ($flujos as $periodo => $flujo) {
            $acumulado += $flujo;

            // Cuando el acumulado se vuelve positivo o cero, hemos recuperado la inversión
            if ($acumulado >= 0 && $periodo > 0) {
                // Interpolación lineal para calcular la fracción exacta del período
                if ($acumuladoAnterior < 0) {
                    $fraccion = abs($acumuladoAnterior) / abs($flujo);

                    return round($periodoAnterior + $fraccion, 2);
                }

                return (float) $periodo;
            }

            $periodoAnterior = $periodo;
            $acumuladoAnterior = $acumulado;
        }

        // Si nunca se recupera, retornar null
        return null;
    }

    /**
     * Calcula la TIR usando el método de Newton-Raphson mejorado
     */
    private function calcularTIR(array $flujos, int $periodicidadTasa): ?float
    {
        // Validar que haya al menos dos flujos (inversión + al menos un flujo)
        if (count($flujos) < 2) {
            return null;
        }

        // Caso especial: solo inversión inicial y un flujo
        if (count($flujos) == 2) {
            $f0 = $flujos[0]; // Inversión (negativa)
            $f1 = $flujos[1]; // Flujo del período 1

            // Resolver: f0 + f1/(1+r) = 0
            // f1/(1+r) = -f0
            // 1+r = f1/(-f0)
            // r = (f1/(-f0)) - 1

            if ($f0 != 0) {
                $r = ($f1 / (-$f0)) - 1;

                return $r * 100; // Convertir a porcentaje
            }
        }

        // Validar que tengamos cambios de signo (deseable para TIR)
        $tienePositivos = false;
        $tieneNegativos = false;

        foreach ($flujos as $flujo) {
            if ($flujo > 0) {
                $tienePositivos = true;
            }
            if ($flujo < 0) {
                $tieneNegativos = true;
            }
        }

        // Si todos los flujos tienen el mismo signo, no hay TIR real
        if (! $tienePositivos || ! $tieneNegativos) {
            return null;
        }

        // Estimaciones iniciales inteligentes
        $inversionInicial = abs($flujos[0]);
        $flujosPositivos = array_filter(array_slice($flujos, 1), fn ($f) => $f > 0);

        if (! empty($flujosPositivos)) {
            $sumaFlujos = array_sum($flujosPositivos);
            $promedioFlujos = $sumaFlujos / count($flujosPositivos);

            // Estimación basada en ROI simple
            $estimacionSimple = ($sumaFlujos / $inversionInicial) / count($flujosPositivos) - 1;
            $estimacionesIniciales = [
                $estimacionSimple,
                0.1,
                0.5,
                -0.5,
                -0.9,
                1.0,
            ];
        } else {
            $estimacionesIniciales = [0.1, -0.5, 0.5, -0.9];
        }

        // Intentar con múltiples estimaciones iniciales
        foreach ($estimacionesIniciales as $estimacion) {
            $resultado = $this->newtonRaphson($flujos, $estimacion);
            if ($resultado !== null) {
                return $resultado * 100; // Convertir a porcentaje
            }
        }

        return null;
    }

    /**
     * Calcula la TIRM (Tasa Interna de Retorno Modificada)
     */
    private function calcularTIRM(array $flujos, float $tasaFinanciamiento, float $tasaReinversion): ?float
    {
        $n = count($flujos) - 1; // Número de períodos (sin contar período 0)

        if ($n <= 0) {
            return null;
        }

        // Separar flujos positivos y negativos
        $vpNegativos = 0;
        $vfPositivos = 0;

        foreach ($flujos as $periodo => $flujo) {
            if ($flujo < 0) {
                // Traer al presente los flujos negativos con tasa de financiamiento
                $vpNegativos += $flujo / pow(1 + $tasaFinanciamiento, $periodo);
            } else {
                // Llevar al futuro los flujos positivos con tasa de reinversión
                $vfPositivos += $flujo * pow(1 + $tasaReinversion, $n - $periodo);
            }
        }

        // Validar que haya inversión neta
        if ($vpNegativos >= 0 || $vfPositivos <= 0) {
            return null;
        }

        // Calcular TIRM: (VF/VP)^(1/n) - 1
        $tirm = pow(abs($vfPositivos / $vpNegativos), 1 / $n) - 1;

        return $tirm * 100; // Convertir a porcentaje
    }

    /**
     * Cuenta los cambios de signo en los flujos
     */
    private function contarCambiosDeSigno(array $flujos): int
    {
        $cambios = 0;
        $signoAnterior = null;

        foreach ($flujos as $flujo) {
            $signoActual = $flujo <=> 0; // -1, 0, o 1

            if ($signoAnterior !== null &&
                $signoActual !== 0 &&
                $signoAnterior !== 0 &&
                $signoActual !== $signoAnterior) {
                $cambios++;
            }

            if ($signoActual !== 0) {
                $signoAnterior = $signoActual;
            }
        }

        return $cambios;
    }

    /**
     * Método de Newton-Raphson para encontrar la raíz
     */
    private function newtonRaphson(array $flujos, float $estimacionInicial): ?float
    {
        $r = $estimacionInicial;
        $maxIter = 500;
        $tol = 1e-10;

        for ($i = 0; $i < $maxIter; $i++) {
            if (abs(1 + $r) < 1e-12) {
                $r = -0.999999; // Evitar división por cero
            }

            // Calcular VPN y su derivada
            $vpn = 0;
            $derivada = 0;

            try {
                foreach ($flujos as $periodo => $flujo) {
                    $base = 1 + $r;

                    // Validar que no estemos en un punto singular
                    if (abs($base) < 1e-12) {
                        break 2;
                    }

                    $denominador = pow($base, $periodo);

                    // Protección contra overflow/underflow
                    if (! is_finite($denominador) || abs($denominador) < 1e-15) {
                        break 2;
                    }

                    $vpn += $flujo / $denominador;

                    if ($periodo > 0) {
                        $derivadaTermino = ($periodo * $flujo) / pow($base, $periodo + 1);
                        if (is_finite($derivadaTermino)) {
                            $derivada -= $derivadaTermino;
                        }
                    }
                }
            } catch (\Throwable $e) {
                break;
            }

            // Verificar convergencia del VPN
            if (abs($vpn) < $tol) {
                // Validar que la solución sea matemáticamente válida
                // Permitimos TIR muy negativas (hasta -99.99%) y muy positivas (hasta 10000%)
                if ($r > -0.9999 && $r < 100) {
                    return $r;
                }
                break;
            }

            // Verificar que la derivada no sea cero
            if (abs($derivada) < 1e-12) {
                break;
            }

            // Calcular siguiente iteración
            $r_new = $r - $vpn / $derivada;

            // Verificar convergencia de r
            if (abs($r_new - $r) < $tol && abs($vpn) < $tol * 10) {
                if ($r_new > -0.9999 && $r_new < 100) {
                    return $r_new;
                }
                break;
            }

            $r = $r_new;

            // Limitar r para evitar overflow, pero con rangos más amplios
            if ($r < -0.9999) {
                $r = -0.9999;
            }
            if ($r > 100) {
                $r = 100;
            }
        }

        return null;
    }

    /**
     * Calcula el VPN dados los flujos y una tasa
     */
    private function calcularVPN(array $flujos, float $tasaPorPeriodo): float
    {
        $vpn = 0;

        foreach ($flujos as $periodo => $flujo) {
            if (abs($tasaPorPeriodo) < 1e-12) {
                $vpn += $flujo;
            } else {
                $vpn += $flujo / pow(1 + $tasaPorPeriodo, $periodo);
            }
        }

        return $vpn;
    }

    /**
     * Genera la tabla de flujos de caja con sus valores presentes
     */
    private function generarTablaFlujos(array $flujos, float $tasaPorPeriodo): array
    {
        $tabla = [];
        $vpAcumulado = 0;

        foreach ($flujos as $periodo => $flujo) {
            if (abs($tasaPorPeriodo) < 1e-12) {
                $factorDescuento = 1;
                $valorPresente = $flujo;
            } else {
                $factorDescuento = 1 / pow(1 + $tasaPorPeriodo, $periodo);
                $valorPresente = $flujo * $factorDescuento;
            }

            $vpAcumulado += $valorPresente;

            $tabla[] = [
                'periodo' => $periodo,
                'flujo' => round($flujo, 2),
                'factor_descuento' => round($factorDescuento, 6),
                'valor_presente' => round($valorPresente, 2),
                'vp_acumulado' => round($vpAcumulado, 2),
            ];
        }

        return $tabla;
    }
}
