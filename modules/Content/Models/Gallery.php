<?php

namespace Modules\Content\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Content\Enums\GalleryTemplateEnum;

class Gallery extends Model
{
    use SoftDeletes;

    protected $casts = [
        'template' => GalleryTemplateEnum::class,
    ];

    public function items(): HasMany
    {
        return $this->hasMany(GalleryItem::class);
    }
}
