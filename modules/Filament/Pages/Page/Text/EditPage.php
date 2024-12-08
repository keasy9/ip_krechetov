<?php

namespace Modules\Filament\Pages\Page\Text;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Modules\Filament\Resources\PageResource;

class EditPage extends EditRecord
{
    protected static string $resource = PageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()->label('Архивировать'),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
