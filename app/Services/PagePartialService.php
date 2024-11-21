<?php

namespace App\Services;

use App\Enums\PageEnum;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use App\Models\PagePartial;

class PagePartialService
{
    /**
     * @param string $pageCode
     * @return Collection<string,PagePartial>
     */
    public static function get(PageEnum $pageCode, ?array $with = []): Collection
    {
        return app(static::class)->loadPartials($pageCode->name, $with);
    }

    protected function loadPartials(string $pageCode, ?array $with = []): Collection
    {
        return Cache::tags([PagePartial::getCacheTag()])
            ->remember('page-partials.' . $pageCode . implode('_', $with), PagePartial::getCacheTime(), function () use ($pageCode, $with) {
                return PagePartial::wherePageCode($pageCode)
                    ->with($with)
                    ->get()
                    ->keyBy('slug');
            });
    }
}
