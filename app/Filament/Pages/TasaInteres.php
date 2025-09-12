<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class TasaInteres extends Page
{
    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-academic-cap';

    protected static string|\BackedEnum|null $activeNavigationIcon = 'heroicon-s-academic-cap';

    protected static ?string $navigationLabel = 'Tasa de Interés';

    protected static ?string $title = 'Tasa de Interés';

    protected string $view = 'filament.pages.tasa-interes';
}
