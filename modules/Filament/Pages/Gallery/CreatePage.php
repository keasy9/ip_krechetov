<?php

namespace Modules\Filament\Pages\Gallery;

use Filament\Resources\Pages\CreateRecord;
use Modules\Filament\Resources\GalleryResource;
use Modules\Filament\Resources\PageResource;

class CreatePage extends CreateRecord
{
    protected static string $resource = GalleryResource::class;
}
