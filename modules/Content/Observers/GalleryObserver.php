<?php

namespace Modules\Content\Observers;


use Modules\Content\Models\Gallery;
use Modules\Content\Models\GalleryItem;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class GalleryObserver
{
    public function deleting(Gallery $gallery): void
    {
        if ($gallery->isForceDeleting()) {
            $items = $gallery->items()->getQuery()->with('media')->get();
            $items->pluck('media')->flatten()->each(function (Media $media) {
                $media->delete();
            });
            GalleryItem::whereIn('id', $items->pluck('id'))->delete();
        }
    }
}
