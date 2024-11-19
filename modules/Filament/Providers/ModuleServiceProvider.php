<?php

declare(strict_types=1);

namespace Modules\Filament\Providers;

use App\Providers\BaseModuleProvider;

class ModuleServiceProvider extends BaseModuleProvider
{

    protected function getModuleName(): string
    {
        return 'Filament';
    }

    protected function getProviderList(): array
    {
        return [
            AdminPanelProvider::class,
        ];
    }

    public function register()
    {
        parent::register();
    }
}
