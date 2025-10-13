<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;

class Capitalizacion extends Page
{
    protected static string|null|\BackedEnum $navigationIcon = 'heroicon-o-chart-bar';

    protected static string|null|\BackedEnum $activeNavigationIcon = 'heroicon-s-chart-bar';

    protected static ?string $navigationLabel = 'Capitalización';

    protected static string|null|\UnitEnum $navigationGroup = 'Segundo Corte';

    protected static ?int $navigationSort = 2;

    protected string $view = 'filament.pages.capitalizacion';

    public function getHeading(): Htmlable|string
    {
        return 'Calculadora de Capitalización';
    }

    public function getSubheading(): Htmlable|string
    {
        return 'Cálculo de interés compuesto y valor futuro';
    }
}
