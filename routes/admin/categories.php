<?php

use App\Http\Controllers\Admin\CategoryController;
use Illuminate\Support\Facades\Route;

Route::middleware('permission:categories.view')->group(function () {
    Route::resource('categories', CategoryController::class)->except(['show']);
});
