<?php

use App\Http\Controllers\Admin\DocumentController;
use Illuminate\Support\Facades\Route;

Route::middleware('permission:documents.view')->group(function () {
    Route::resource('documents', DocumentController::class);

    // Hapus lampiran dari dokumen
    Route::delete('documents/{document}/attachments/{attachment}', [DocumentController::class, 'destroyAttachment'])
        ->name('documents.attachments.destroy')
        ->middleware('permission:documents.update');
});
