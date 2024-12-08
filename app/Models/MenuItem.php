<?php

namespace App\Models;

use App\Traits\CacheTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;

class MenuItem extends Model
{
    use HasFactory;
    use CacheTrait;
    use SoftDeletes;

    protected ?Collection $children = null;
    public int $level = 0;
    protected $guarded = ['id'];
    protected $casts = [
        'route_params' => 'array',
    ];

    public function getChildren(): Collection
    {
        if (!$this->children) {
            $this->children = new Collection();
        }

        return $this->children;
    }

    public function isCurrent(): bool
    {
        if ($this->url) {
            return str_starts_with(request()->getRequestUri(), $this->url);
        } elseif ($this->route) {
            return Route::is($this->route);
        }
        return false;
    }

    public function getUrl(): string
    {
        if (!empty($this->route) && Route::has($this->route)) {
            return route($this->route, $this->route_params ?? [], false);
        } else {
            return $this->url ?: '';
        }
    }

    public static function tree($code = false, bool $front = false): Collection
    {
        $list = static::getAllCached($front);

        $tree = new Collection();

        foreach ($list as $item) {
            if ($item->parent_id && $list->get($item->parent_id)) {
                $menuItem = $list->get($item->parent_id);
                $menuItem->getChildren()->push($item);
                continue;
            }
            $tree->push($item);
        }

        if ($code) {
            return $tree->where('menu_type', $code);
        }

        return $tree;
    }

    public static function getAll(bool $front = true): Collection
    {
        return static::query()
            ->withTrashed(!$front)
            ->orderBy('sort')
            ->get()
            ->keyBy('id');
    }

    public static function getAllCached(bool $front = true): Collection
    {
        if (!Cache::supportsTags()) {
            return static::getAll($front);
        }

        return Cache::tags([
            static::getCacheTag(),
            $front ? 'menu_front' : 'menu_all',
        ])
            ->remember(
                'menu_item_get_' . $front ? 'front' : 'all',
                static::getCacheTime(),
                fn() => static::getAll($front),
            );
    }

    public static function treeList($code = false, bool $front = false): Collection
    {
        $tree = static::tree($code, $front);

        $stack = collect();
        foreach ($tree as $item) {

            $item->isFirst = $item->is($tree->first());
            $item->isLast = $item->is($tree->last());

            $stack->push($item);
            $item->pushChildrenRecursively($stack);
        }

        return $stack;
    }

    protected function pushChildrenRecursively(Collection $stack): void
    {
        if ($this->children?->isNotEmpty()) {
            $this->children->each(function (MenuItem $item) use ($stack) {
                $item->level = $this->level + 1;
                $stack->push($item);

                $item->isFirst = $item->is($this->children->first());
                $item->isLast = $item->is($this->children->last());

                $item->pushChildrenRecursively($stack);
            });
        }
    }

    public function up(): bool
    {
        $prevItem = static::query()
            ->whereParentId($this->parent_id)
            ->withTrashed()
            ->where('sort', '<=', $this->sort)
            ->whereNot('id', $this->getKey())
            ->orderByDesc('sort')
            ->orderByDesc('id')
            ->first();

        if (!$prevItem) return false;

        $prevSort = $prevItem->sort;
        $currentSort = $this->sort;
        if ($this->sort === $prevSort) {
            $prevSort -= 1;
        }
        $this->sort = $prevSort;
        $prevItem->sort = $currentSort;

        return $prevItem->save() && $this->save();
    }

    public function down(): bool
    {
        $nextItem = static::query()
            ->whereParentId($this->parent_id)
            ->withTrashed()
            ->where('sort', '>=', $this->sort)
            ->whereNot('id', $this->getKey())
            ->orderBy('sort')
            ->orderBy('id')
            ->first();

        if (!$nextItem) return false;

        $nextSort = $nextItem->sort;
        $currentSort = $this->sort;
        if ($this->sort === $nextSort) {
            $nextSort += 1;
        }
        $this->sort = $nextSort;
        $nextItem->sort = $currentSort;

        return $nextItem->save() && $this->save();
    }
}
