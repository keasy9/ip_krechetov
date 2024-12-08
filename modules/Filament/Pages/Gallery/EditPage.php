<?php

namespace Modules\Filament\Pages\Gallery;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Modules\Filament\Resources\GalleryResource;
use Modules\Filament\Resources\PageResource;

class EditPage extends EditRecord
{
    protected static string $resource = GalleryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()->label('Архивировать'),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
