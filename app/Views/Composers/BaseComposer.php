<?php

namespace App\Views\Composers;

use App\Services\PagePartialService;
use Illuminate\View\View;
use Modules\Content\Enums\PageEnum;

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
