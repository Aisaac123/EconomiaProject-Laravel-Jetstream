<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class Anualidad extends Page
{
    protected static string|null|\BackedEnum $navigationIcon = 'heroicon-o-calendar';

    protected static string|null|\BackedEnum $activeNavigationIcon = 'heroicon-s-calendar';

    protected string $view = 'filament.pages.anualidad';

    public static function getNavigationSort(): ?int
    {
        return 4;
    }
}
