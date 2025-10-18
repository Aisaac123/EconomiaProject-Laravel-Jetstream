<?php

namespace App\Filament\Pages\Creditos;

use App\Enums\PageGroupType;
use App\Models\Credit;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Pages\Page;
use Filament\Support\Enums\Width;
use Illuminate\Contracts\Support\Htmlable;

class ShowCredit extends Page
{

    public Credit $record;

    public function mount(int $recordId): void
    {
        $this->record = Credit::findOrFail($recordId);
    }
    protected static bool $shouldRegisterNavigation = false;

    protected string $view = 'filament.pages.creditos.show';

    protected static string|null|\BackedEnum $navigationIcon = 'heroicon-o-document-text';

    protected static string|null|\UnitEnum $navigationGroup = PageGroupType::CREDIT->value;

    protected static ?string $slug = 'creditos/{recordId}';


    public static function getNavigationLabel(): string
    {
        return 'Ver Crédito';
    }

    public function getTitle(): string|Htmlable
    {
        return "Crédito #{$this->record->reference_code}";
    }

    public function getHeading(): string|Htmlable
    {
        return '';
    }

    protected function getHeaderActions(): array
    {
        return [

            DeleteAction::make()
                ->label('Eliminar')
                ->icon('heroicon-o-trash')
                ->requiresConfirmation()
                ->modalHeading('Eliminar Crédito')
                ->modalDescription('¿Estás seguro? Esta acción eliminará el crédito permanentemente.')
                ->modalSubmitActionLabel('Sí, eliminar')
                ->modalCancelActionLabel('Cancelar'),

            Action::make('back')
                ->label('Volver')
                ->icon('heroicon-o-arrow-left')
                ->url(back())
                ->tooltip('Volver al listado'),
        ];
    }

    public function getMaxContentWidth(): Width
    {
        return Width::ScreenTwoExtraLarge;
    }
}
