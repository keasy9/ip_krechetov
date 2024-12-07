<?php

namespace Modules\Main\Http\Controllers;

use App\Http\Controllers\Controller;

class IndexController extends Controller
{
    public function index()
    {
        $data = [
            'slider' => [

            ],
        ];

        return view('main::welcome', $data);
    }
}
