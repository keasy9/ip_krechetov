<?php

namespace App\Collections;

use Illuminate\Support\Collection;
use App\Models\PagePartial;

class PagePartialCollection extends Collection
{
    public function get($key, $default = null)
    {
        return parent::get($key, $default ?: PagePartial::make());
    }
}
