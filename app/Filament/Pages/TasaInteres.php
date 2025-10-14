<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class TasaInteres extends Page
{
    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-currency-dollar';
    protected static string|null|\UnitEnum $navigationGroup = 'Primer Corte';
    protected static string|\BackedEnum|null $activeNavigationIcon = 'heroicon-s-currency-dollar';

    protected static ?string $navigationLabel = 'Tasa de Interés';

    protected static ?string $title = '';

    protected string $view = 'filament.pages.tasa-interes';
}
