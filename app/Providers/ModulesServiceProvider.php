<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class ModulesServiceProvider extends ServiceProvider
{
    public function register()
    {
        $modules = [
            \Modules\Filament\Providers\ModuleServiceProvider::class,
            \Modules\TextContent\Providers\ModuleServiceProvider::class,
        ];

        foreach ($modules as $module) {
            $this->app->register($module);
        }
    }
}
