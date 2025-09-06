<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class InteresSimple extends Page
{
    protected static string|null|\BackedEnum $navigationIcon = 'heroicon-o-chart-bar';

    protected static string|null|\BackedEnum $activeNavigationIcon = 'heroicon-c-chart-bar';

    protected string $view = 'filament.pages.interes-simple';

    public static function getNavigationSort(): ?int
    {
        return 2;
    }

    public static function getNavigationLabel(): string
    {
        return 'Interés Simple';
    }
}
