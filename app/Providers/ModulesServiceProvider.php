<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Modules\Filament\Providers\ModuleServiceProvider;

class ModulesServiceProvider extends ServiceProvider
{
    public function register()
    {
        $modules = [
            ModuleServiceProvider::class,
        ];

        foreach ($modules as $module) {
            $this->app->register($module);
        }
    }
}
