<?php

namespace App\View\Composers;

use App\Enums\PageEnum;
use App\Services\PagePartialService;
use Illuminate\View\View;

class BaseComposer
{

    public function __construct(protected PagePartialService $pagePartialService)
    {
    }

    public function compose(View $view)
    {
        $view->with('baseData', $this->pagePartialService::get(PageEnum::shared)->toArray(),);
    }
}
