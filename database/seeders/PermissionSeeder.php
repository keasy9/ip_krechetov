<?php

namespace Database\Seeders;

use App\Enums\PermissionEnum;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;


class PermissionSeeder extends Seeder
{

    public function run(): void
    {
        $permissions = [];

        foreach (PermissionEnum::cases() as $case) {
            $permissions[] = [
                'name'       => $case->value,
                'guard_name' => 'web',
            ];
        }

        Permission::insert($permissions);
    }
}
