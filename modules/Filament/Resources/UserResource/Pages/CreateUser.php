<?php

namespace modules\Filament\Resources\UserResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use modules\Filament\Resources\UserResource;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;
}
