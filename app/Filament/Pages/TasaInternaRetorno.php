<?php

namespace App\Filament\Pages;

use App\Enums\PageGroupType;
use App\Filament\Schemas\TasaInternaRetornoSchema;
use App\Traits\FormCalculations;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Illuminate\Contracts\Support\Htmlable;

class TasaInternaRetorno extends Page
{
    use FormCalculations;

    protected static string|null|\BackedEnum $navigationIcon = 'heroicon-o-currency-dollar';

    protected static string|null|\BackedEnum $activeNavigationIcon = 'heroicon-s-currency-dollar';

    protected static ?string $navigationLabel = 'Tasa Interna de Retorno';

    protected static string|null|\UnitEnum $navigationGroup = PageGroupType::ADVANCE->value;

    protected static ?int $navigationSort = 4;

    protected string $view = 'filament.pages.tasa-interna-retorno';

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
        return TasaInternaRetornoSchema::configure($schema)
            ->statePath('data')
            ->operation('calculate');
    }
}
