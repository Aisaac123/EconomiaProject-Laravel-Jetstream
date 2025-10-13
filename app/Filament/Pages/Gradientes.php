<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;

class Gradientes extends Page
{
    protected static string|null|\BackedEnum $navigationIcon = 'heroicon-o-arrow-trending-up';

    protected static string|null|\BackedEnum $activeNavigationIcon = 'heroicon-s-arrow-trending-up';

    protected static ?string $navigationLabel = 'Gradientes';

    protected static string|null|\UnitEnum $navigationGroup = 'Segundo Corte';

    protected static ?int $navigationSort = 3;

    protected string $view = 'filament.pages.gradientes';

    public function getHeading(): Htmlable|string
    {
        return 'Calculadora de Gradientes';
    }

    public function getSubheading(): Htmlable|string
    {
        return 'Cálculo de series con crecimiento aritmético y geométrico';
    }
}
