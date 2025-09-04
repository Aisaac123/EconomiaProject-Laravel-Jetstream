<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class TasaInteres extends Page
{
    protected static string|null|\BackedEnum $navigationIcon = 'heroicon-o-percent-badge';

    protected static string|null|\BackedEnum $activeNavigationIcon = 'heroicon-c-percent-badge';

    protected string $view = 'filament.pages.tasa-interes';

    public static function getNavigationSort(): ?int
    {
        return 1;
    }

    public static function getNavigationLabel(): string
    {
        return 'Tasa de interés';
    }
}
