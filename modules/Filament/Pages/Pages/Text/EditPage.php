<?php

namespace Modules\Filament\Pages\Pages\Text;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Modules\Filament\Resources\PageResource;

class EditPage extends EditRecord
{
    protected static string $resource = PageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
