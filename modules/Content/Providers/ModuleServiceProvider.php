<?php

declare(strict_types=1);

namespace Modules\Content\Providers;

use App\Providers\BaseModuleProvider;
use Modules\Content\Models\Gallery;
use Modules\Content\Models\Page;
use Modules\Content\Observers\GalleryObserver;

class ModuleServiceProvider extends BaseModuleProvider
{

    protected function getModuleName(): string
    {
        return 'Content';
    }

    protected function getProviderList(): array
    {
        return [];
    }

    public function boot()
    {
        parent::boot();
        Page::unguard();
        Gallery::observe(GalleryObserver::class);
    }
}
