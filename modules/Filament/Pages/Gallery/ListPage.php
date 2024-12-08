<?php

namespace Modules\Filament\Pages\Gallery;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Modules\Filament\Resources\GalleryResource;
use Modules\Filament\Resources\PageResource;

class ListPage extends ListRecords
{
    protected static string $resource = GalleryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
