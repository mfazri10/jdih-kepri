<?php

use App\Http\Controllers\Admin\RoleController;
use Illuminate\Support\Facades\Route;

Route::resource('roles', RoleController::class)
    ->except('show');
