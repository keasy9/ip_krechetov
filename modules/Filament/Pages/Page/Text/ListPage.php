<?php

namespace Modules\Filament\Pages\Page\Text;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Modules\Filament\Resources\PageResource;

class ListPage extends ListRecords
{
    protected static string $resource = PageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
