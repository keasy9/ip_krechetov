<?php

use Illuminate\Support\Facades\Route;
use Modules\Main\Http\Controllers\IndexController;

Route::get('/', [IndexController::class, 'index'])->name('index');
