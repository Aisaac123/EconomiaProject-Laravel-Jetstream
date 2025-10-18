<?php

namespace App\Filament\Pages;

use Filament\Jetstream\Pages\Dashboard as BaseDashboard;
use Filament\Support\Enums\Width;
use Illuminate\Contracts\View\View;

class Dashboard extends BaseDashboard
{
    protected string $view = 'filament.pages.dashboard';

    public function getHeading(): \Illuminate\Contracts\Support\Htmlable|string
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
