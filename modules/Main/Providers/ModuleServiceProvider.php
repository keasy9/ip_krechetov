<?php

declare(strict_types=1);

namespace Modules\Main\Providers;

use App\Providers\BaseModuleProvider;

class ModuleServiceProvider extends BaseModuleProvider
{

    protected function getModuleName(): string
    {
        return 'Main';
    }

    protected function getProviderList(): array
    {
        return [
            RouteServiceProvider::class,
        ];
    }
}
