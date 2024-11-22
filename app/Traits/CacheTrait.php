<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

/**
 * Очистка кеша по тегу на любое изменение в таблице сущности
 * использовать getCacheTag() везде где кешируются данные из этой модели
 *
 * @mixin Model
 */
trait CacheTrait
{
    protected static string $cachePrefix = "ct_";
    protected static int $cacheTime = 3600;

    public static function bootCacheTrait()
    {
        static::saved(fn (Model $model) => static::cacheFlush());
//        static::updated(fn (Model $model) => static::cacheFlush());
//        static::created(fn (Model $model) => static::cacheFlush());
        static::deleted(fn (Model $model) => static::cacheFlush());
    }

    public static function cacheFlush()
    {
        if (Cache::supportsTags()) {
            Cache::tags(static::getCacheTag())->flush();
        }
    }

    public static function getCacheTag(): string
    {
        return static::$cachePrefix . static::make()->getTable();
    }

    public static function getCacheTime(): int
    {
        return static::$cacheTime;
    }
}
