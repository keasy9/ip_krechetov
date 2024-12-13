<?php

namespace Modules\Content\Views\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\View\Component;
use Modules\Content\Models\Gallery as GalleryModel;

class Gallery extends Component
{
    protected string $template;
    protected ?GalleryModel $gallery;

    public function __construct(int|string|null $galleryId = null, string $defaultTemplate = 'slider')
    {
        $this->gallery = GalleryModel::with(['items' => fn(HasMany $query) => $query->orderBy('order')->with('media')])
            ->find($galleryId);
        $this->template = $this->gallery?->template?->value ?? $defaultTemplate;
    }

    public function shouldRender()
    {
        return $this->gallery?->exists() && $this->gallery->items?->isNotEmpty();
    }

    public function render(): View|Closure|string
    {
        return view("content::gallery.{$this->template}", [
            'gallery' => $this->gallery,
        ]);
    }
}
