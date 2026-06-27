<?php

use App\Http\Controllers\Admin\ThemeController;
use Illuminate\Support\Facades\Route;

Route::middleware('permission:themes.view')->group(function () {
    Route::resource('themes', ThemeController::class)->except(['show']);
});
