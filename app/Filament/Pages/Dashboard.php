<?php

namespace App\Filament\Pages;
use \Filament\Jetstream\Pages\Dashboard as BaseDashboard;


class Dashboard extends BaseDashboard
{
    protected string $view = 'filament.pages.dashboard';
    public function getHeading(): \Illuminate\Contracts\Support\Htmlable|string
    {
        return '';
    }
}
