<?php

namespace App\Filament\Pages;

use App\Enums\PageGroupType;
use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;

class TasaInternaRetorno extends Page
{
    protected static string|null|\BackedEnum $navigationIcon = 'heroicon-o-currency-dollar';

    protected static string|null|\BackedEnum $activeNavigationIcon = 'heroicon-s-currency-dollar';

    protected static ?string $navigationLabel = 'Tasa Interna de Retorno';

    protected static string|null|\UnitEnum $navigationGroup = PageGroupType::ADVANCE->value;

    protected static ?int $navigationSort = 4;

    protected string $view = 'filament.pages.tasa-interna-retorno';

    public function getHeading(): Htmlable|string
    {
        return 'Calculadora de Tasa Interna de Retorno';
    }

    public function getSubheading(): Htmlable|string
    {
        return 'Análisis de inversiones y evaluación de proyectos';
    }
}
