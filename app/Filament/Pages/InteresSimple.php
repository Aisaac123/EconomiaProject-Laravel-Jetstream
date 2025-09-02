<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class InteresSimple extends Page
{
    protected static string|null|\BackedEnum $navigationIcon = 'heroicon-o-calculator';
    protected static string|null|\BackedEnum $activeNavigationIcon = 'heroicon-c-calculator';

    protected string $view = 'filament.pages.interes-simple';

    public static function getNavigationSort(): ?int
    {
        return 2;
    }
}
