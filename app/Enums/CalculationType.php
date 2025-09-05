<?php

namespace App\Enums;

enum CalculationType: string
{
    case SIMPLE = 'simple';
    case COMPUESTO = 'compuesto';
    case ANUALIDAD = 'anualidad';
    case TASA_INTERES = 'tasa_interes';
}
