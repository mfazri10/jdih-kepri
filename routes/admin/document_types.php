<?php

use App\Http\Controllers\Admin\DocumentTypeController;
use Illuminate\Support\Facades\Route;

Route::middleware('permission:document-types.view')->group(function () {
    Route::resource('document-types', DocumentTypeController::class)->except(['show']);
});
