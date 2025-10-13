<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;

class Amortizacion extends Page
{
    protected static string|null|\BackedEnum $navigationIcon = 'heroicon-o-calculator';

    protected static string|null|\BackedEnum $activeNavigationIcon = 'heroicon-s-calculator';

    protected static ?string $navigationLabel = 'Amortización';

    protected static string|null|\UnitEnum $navigationGroup = 'Segundo Corte';

    protected static ?int $navigationSort = 1;

    protected string $view = 'filament.pages.amortizacion';

    public function getHeading(): Htmlable|string
    {
        return '';
    }
}
