<?php

namespace Modules\Filament\Pages\User;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use modules\Filament\Resources\UserResource;

class ListPage extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [

            Actions\CreateAction::make(),
        ];
    }
}
