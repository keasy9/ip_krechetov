<?php

namespace Modules\Main\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\PagePartialService;
use Modules\Content\Enums\PageEnum;

class IndexController extends Controller
{
    public function index(PagePartialService $pagePartialService)
    {
        return view('main::index', [
            'data' => $pagePartialService::get(PageEnum::home)->toArray(),
        ]);
    }
}
