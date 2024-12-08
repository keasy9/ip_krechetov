<?php

namespace Modules\Content\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Page extends Model
{
    use SoftDeletes;

    public function getUrl(bool $absolute = false): string
    {
        if ($absolute) {
            return url($this->url);
        }

        return '/' . ltrim($this->url);
    }
}
