<?php

namespace App\Http\Controllers;

class IndexController extends Controller
{
    public function index()
    {
        $data = [
            'slider' => [

            ],
        ];

        return view('welcome', $data);
    }
}
