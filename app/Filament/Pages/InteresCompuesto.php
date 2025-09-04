<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class InteresCompuesto extends Page
{
    protected static string|null|\BackedEnum $navigationIcon = 'heroicon-o-arrow-trending-up';
    protected static string|null|\BackedEnum $activeNavigationIcon = 'heroicon-c-arrow-trending-up';

    protected string $view = 'filament.pages.interes-compuesto';

    public static function getNavigationSort(): ?int
    {
        return 3;
    }
    public static function getNavigationLabel(): string
    {
        return 'Interés Compuesto';
    }
}
