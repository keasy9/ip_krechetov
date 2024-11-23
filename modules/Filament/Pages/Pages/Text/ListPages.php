<?php

namespace Modules\Filament\Pages\Pages\Text;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Modules\Filament\Resources\PageResource;

class ListPages extends ListRecords
{
    protected static string $resource = PageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
