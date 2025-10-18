<?php

namespace App\Filament\Pages;

use App\Enums\PageGroupType;
use Filament\Pages\Page;
use Filament\Support\Enums\Width;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\View\View;

class Capitalizacion extends Page
{
    protected static string|null|\BackedEnum $navigationIcon = 'heroicon-o-chart-bar';

    protected static string|null|\BackedEnum $activeNavigationIcon = 'heroicon-s-chart-bar';

    protected static ?string $navigationLabel = 'Sistemas de Capitalización';

    protected static string|null|\UnitEnum $navigationGroup = PageGroupType::ADVANCE->value;

    protected static ?int $navigationSort = 2;

    protected string $view = 'filament.pages.capitalizacion';

    public function getHeading(): Htmlable|string
    {
        return '';
    }

    public function getFooter(): ?View
    {
        return view('filament.settings.custom-footer');
    }

    public function getMaxContentWidth(): Width
    {
        return Width::ScreenTwoExtraLarge;
    }
}
