<?php

namespace Modules\Main\Http\Controllers;

use App\Enums\PageEnum;
use App\Http\Controllers\Controller;
use App\Services\PagePartialService;

class IndexController extends Controller
{
    public function index(PagePartialService $pagePartialService)
    {
        $data = $pagePartialService::get(PageEnum::home)->toArray();

        return view('main::index', [
            'data' => [
                'promo-slider' => $data['promo-slider'],
                'cards'        => $data['cards'],
            ],
        ]);
    }
}
