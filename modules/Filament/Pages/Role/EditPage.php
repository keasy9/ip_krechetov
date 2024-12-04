<?php

namespace Modules\Filament\Pages\Role;

use App\Enums\PermissionEnum;
use App\Models\User;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Modules\Filament\Resources\RoleResource;
use Spatie\Permission\Models\Role;

class EditPage extends EditRecord
{
    protected static string $resource = RoleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    public static function canAccess(array $parameters = []): bool
    {
        /** @var Role $role */
        $role = $parameters['record'];

        /** @var User $user */
        $user = auth()->user();

        return $user->can(PermissionEnum::ownRole) || !$user->hasRole($role);
    }
}
