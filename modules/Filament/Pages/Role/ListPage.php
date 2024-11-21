<?php

namespace Modules\Filament\Pages\Role;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Modules\Filament\Resources\RoleResource;

class ListPage extends ListRecords
{
    protected static string $resource = RoleResource::class;

    protected function getHeaderActions(): array
    {
        return [

            Actions\CreateAction::make(),
        ];
    }
}
