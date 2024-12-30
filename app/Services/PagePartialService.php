<?php

namespace App\Services;

use App\Models\PagePartial;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Modules\Content\Enums\PageEnum;

class PagePartialService
{
    /**
     * @param string $pageCode
     * @return Collection<string,PagePartial>
     */
    public static function get(PageEnum $pageCode, ?array $with = []): Collection
    {
        return app(static::class)
            ->loadPartials($pageCode->name, $with)
            ->pluck('value', 'slug')
            ->map(fn ($item) => json_decode($item, true) ?: $item ?: '');
    }

    protected function loadPartials(string $pageCode, ?array $with = []): Collection
    {
        if (Cache::supportsTags()) {
            return Cache::tags([PagePartial::getCacheTag()])->remember(
                "page-partials.{$pageCode}" . implode('_', $with),
                PagePartial::getCacheTime(),
                fn () => $this->getPartials($pageCode, $with),
            );
        }

        return $this->getPartials($pageCode, $with);
    }

    protected function getPartials(string $pageCode, ?array $with): Collection
    {
        return PagePartial::wherePageCode($pageCode)->with($with)->get()->keyBy('slug');
    }
}
