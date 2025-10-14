<?php

namespace App\Filament\Pages;

use App\Filament\Schemas\AmortizacionSchema;
use App\Traits\FormCalculations;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Illuminate\Contracts\Support\Htmlable;

class Amortizacion extends Page
{
    use FormCalculations;

    protected static string|null|\BackedEnum $navigationIcon = 'heroicon-o-calculator';

    protected static string|null|\BackedEnum $activeNavigationIcon = 'heroicon-s-calculator';

    protected static ?string $navigationLabel = 'Amortización';

    protected static string|null|\UnitEnum $navigationGroup = 'Segundo Corte';

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
}
