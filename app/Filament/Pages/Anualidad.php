<?php

namespace App\Filament\Pages;

use App\Filament\Schemas\AnualidadSchema;
use App\Traits\FormCalculations;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Illuminate\Contracts\Support\Htmlable;

class Anualidad extends Page
{
    use FormCalculations;

    protected static string|null|\BackedEnum $navigationIcon = 'heroicon-o-calendar-days';

    protected static string|null|\BackedEnum $activeNavigationIcon = 'heroicon-s-calendar-days';

    protected string $view = 'filament.pages.anualidad';

    public static function getNavigationSort(): ?int
    {
        return 4;
    }

    public function getHeading(): Htmlable|string
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
