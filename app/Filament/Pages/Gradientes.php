<?php

namespace App\Filament\Pages;

use App\Enums\PageGroupType;
use App\Filament\Schemas\GradientesSchema;
use App\Traits\FormCalculations;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Width;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\View\View;

class Gradientes extends Page
{
    use FormCalculations;

    protected static string|null|\BackedEnum $navigationIcon = 'heroicon-o-arrow-trending-up';

    protected static string|null|\BackedEnum $activeNavigationIcon = 'heroicon-s-arrow-trending-up';

    protected static ?string $navigationLabel = 'Sistemas Gradientes';

    protected static string|null|\UnitEnum $navigationGroup = PageGroupType::ADVANCE->value;

    protected static ?int $navigationSort = 3;

    protected string $view = 'filament.pages.gradientes';

    public function getHeading(): Htmlable|string
    {
        return '';
    }

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Schema $schema): Schema
    {
        return GradientesSchema::configure($schema)
            ->statePath('data')
            ->operation('calculate');
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
