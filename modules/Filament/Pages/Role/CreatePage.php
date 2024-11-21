<?php

namespace Modules\Filament\Pages\Role;

use Filament\Resources\Pages\CreateRecord;
use Modules\Filament\Resources\RoleResource;

class CreatePage extends CreateRecord
{
    protected static string $resource = RoleResource::class;
}
