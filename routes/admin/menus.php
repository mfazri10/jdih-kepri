<?php

use App\Http\Controllers\Admin\MenuController;
use Illuminate\Support\Facades\Route;

Route::resource('menus', MenuController::class)
    ->except('show');
