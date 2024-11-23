<?php

declare(strict_types=1);

namespace Modules\TextContent\Providers;

use App\Providers\BaseModuleProvider;
use Modules\TextContent\Models\Page;

class ModuleServiceProvider extends BaseModuleProvider
{

    protected function getModuleName(): string
    {
        return 'TextContent';
    }

    protected function getProviderList(): array
    {
        return [];
    }

    public function boot()
    {
        parent::boot();
        Page::unguard();
    }
}
