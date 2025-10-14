<?php

namespace App\Filament\Pages;

use App\Filament\Schemas\InteresCompuestoSchema;
use App\Traits\FormCalculations;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Filament\Schemas\Schema;

class InteresCompuesto extends Page implements HasForms
{
    use FormCalculations;
    use InteractsWithForms;

    protected static string|null|\BackedEnum $navigationIcon = 'heroicon-o-arrow-trending-up';
    protected static string|null|\UnitEnum $navigationGroup = 'Primer Corte';

    protected static string|null|\BackedEnum $activeNavigationIcon = 'heroicon-c-arrow-trending-up';

    protected string $view = 'filament.pages.interes-compuesto';

    public function getHeading(): \Illuminate\Contracts\Support\Htmlable|string
    {
        return '';
    }

    public static function getNavigationSort(): ?int
    {
        return 3;
    }

    public static function getNavigationLabel(): string
    {
        return 'Interés Compuesto';
    }


    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Schema $schema): Schema
    {
        return InteresCompuestoSchema::configure($schema)
            ->statePath('data')
            ->operation('calculate');
    }
}
