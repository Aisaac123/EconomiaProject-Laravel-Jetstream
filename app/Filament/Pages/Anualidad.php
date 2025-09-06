<?php

namespace App\Filament\Pages;

use App\Filament\Schemas\AnualidadSchema;
use App\Traits\FormCalculations;
use Filament\Pages\Page;
use Filament\Schemas\Schema;

class Anualidad extends Page
{
    use FormCalculations;

    protected static string|null|\BackedEnum $navigationIcon = 'heroicon-o-calendar';

    protected static string|null|\BackedEnum $activeNavigationIcon = 'heroicon-s-calendar';

    protected string $view = 'filament.pages.anualidad';

    public static function getNavigationSort(): ?int
    {
        return 4;
    }

    public function getHeading(): \Illuminate\Contracts\Support\Htmlable|string
    {
        return '';
    }

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Schema $schema): Schema
    {
        return AnualidadSchema::configure($schema)
            ->statePath('data')
            ->operation('calculate');
    }
}
