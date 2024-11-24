<?php

namespace App\Models;

use App\Traits\CacheTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;

class MenuItem extends Model
{
    use HasFactory;
    use CacheTrait;

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
            return $this->url ?: 'javascript:void(0);';
        }
    }

    public static function tree($code = false, bool $filterActive = false): ?static
    {
        $list = static::getAll($filterActive);

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
            return $list->keyBy('code')->get($code);
        }

        return static::createRoot($tree);
    }

    public static function getAll(bool $front = true): Collection
    {
        return Cache::tags([
            static::getCacheTag(),
            $front ? 'menu_front' : 'menu_all'
        ])
            ->remember(
                'menu_item_get_' . $front ? 'front' : 'all',
                static::getCacheTime(),
                function () use ($front) {
                    $query = static::query()
                        ->with(['icon', 'roles'])
                        ->orderBy('sort');

                    if ($front) {
                        $query->where('active', 1);
                    }

                    return $query->get()
                        ->keyBy('id');
                }
            );
    }

    protected static function createRoot($children): static
    {
        $root = new static();
        $root->children = $children;
        return $root;
    }
}
