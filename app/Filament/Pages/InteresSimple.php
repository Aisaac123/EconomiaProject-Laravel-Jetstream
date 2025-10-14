<?php

namespace App\Filament\Pages;

use App\Enums\PageGroupType;
use App\Filament\Schemas\InteresSimpleSchema;
use App\Traits\FormCalculations;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Filament\Schemas\Schema;

class InteresSimple extends Page implements HasForms
{
    use FormCalculations;
    use InteractsWithForms;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-banknotes';

    protected static string|null|\UnitEnum $navigationGroup = PageGroupType::FUNDAMENTAL->value;

    protected static string|\BackedEnum|null $activeNavigationIcon = 'heroicon-s-banknotes';

    protected string $view = 'filament.pages.interes-simple';

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Schema $schema): Schema
    {
        return InteresSimpleSchema::configure($schema)
            ->statePath('data')
            ->operation('calculate');
    }

    /**
     * Método invocado por la operación `calculate`.
     * Llama al trait para ejecutar el cálculo de interés simple.
     */
    public static function getNavigationSort(): ?int
    {
        return 2;
    }

    public static function getNavigationLabel(): string
    {
        return 'Interés Simple';
    }

    public function getHeading(): \Illuminate\Contracts\Support\Htmlable|string
    {
        return '';
    }
}
