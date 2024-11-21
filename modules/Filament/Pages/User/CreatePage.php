<?php

namespace Modules\Filament\Pages\User;

use Filament\Resources\Pages\CreateRecord;
use Modules\Filament\Resources\UserResource;

class CreatePage extends CreateRecord
{
    protected static string $resource = UserResource::class;
}
