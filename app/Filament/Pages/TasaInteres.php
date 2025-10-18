<?php

namespace App\Filament\Pages;

use App\Enums\PageGroupType;
use Filament\Pages\Page;
use Filament\Support\Enums\Width;
use Illuminate\Contracts\View\View;

class TasaInteres extends Page
{
    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-currency-dollar';

    protected static string|null|\UnitEnum $navigationGroup = PageGroupType::FUNDAMENTAL->value;

    protected static string|\BackedEnum|null $activeNavigationIcon = 'heroicon-s-currency-dollar';

    protected static ?string $navigationLabel = 'Tasa de Interés';

    protected static ?string $title = '';

    protected string $view = 'filament.pages.tasa-interes';

    public function getFooter(): ?View
    {
        return view('filament.settings.custom-footer');
    }

    public function getMaxContentWidth(): Width
    {
        return Width::ScreenTwoExtraLarge;
    }
}
