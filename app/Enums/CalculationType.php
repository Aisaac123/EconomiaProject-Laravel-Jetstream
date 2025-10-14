<?php

namespace App\Enums;

enum CalculationType: string
{
    case SIMPLE = 'simple';
    case COMPUESTO = 'compuesto';
    case ANUALIDAD = 'anualidad';
    case TASA_INTERES = 'tasa_interes';

    case AMORTIZACION = 'amortizacion';
    case CAPITALIZACION = 'capitalizacion';
    case TIR = 'tir';
    case GRADIENTES = 'gradientes';
}
