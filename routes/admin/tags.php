<?php

use App\Http\Controllers\Admin\TagController;
use Illuminate\Support\Facades\Route;

Route::middleware('permission:tags.view')->group(function () {
    Route::resource('tags', TagController::class)->except(['show']);
});
