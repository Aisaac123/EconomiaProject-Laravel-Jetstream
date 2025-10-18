<?php

namespace App\Filament\Pages;

use App\Enums\PageGroupType;
use App\Filament\Schemas\AmortizacionSchema;
use App\Traits\FormCalculations;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Width;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\View\View;

class Amortizacion extends Page
{
    use FormCalculations;

    protected static string|null|\BackedEnum $navigationIcon = 'heroicon-o-calculator';

    protected static string|null|\BackedEnum $activeNavigationIcon = 'heroicon-s-calculator';

    protected static ?string $navigationLabel = 'Sistemas de Amortización';

    protected static string|null|\UnitEnum $navigationGroup = PageGroupType::ADVANCE->value;

    protected static ?int $navigationSort = 1;

    protected string $view = 'filament.pages.amortizacion';

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
        return AmortizacionSchema::configure($schema)
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
