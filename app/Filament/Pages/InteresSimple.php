<?php

namespace App\Filament\Pages;

use App\Filament\Schemas\InteresSimpleSchema;
use App\Traits\FormCalculations;
use Filament\Pages\Page;
use Filament\Schemas\Schema;

class InteresSimple extends Page
{
    use FormCalculations;

    protected static string|null|\BackedEnum $navigationIcon = 'heroicon-o-calculator';

    protected string $view = 'filament.pages.interes-simple';

    public static function getNavigationSort(): ?int
    {
        return 1;
    }

    public ?array $data = [];

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
}
