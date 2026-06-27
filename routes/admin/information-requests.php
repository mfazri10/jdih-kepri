<?php

use App\Http\Controllers\Admin\InformationRequestController;
use Illuminate\Support\Facades\Route;

Route::get('information-requests', [InformationRequestController::class, 'index'])->name('information-requests.index');
Route::get('information-requests/{informationRequest}', [InformationRequestController::class, 'show'])->name('information-requests.show');
Route::post('information-requests/{informationRequest}/respond', [InformationRequestController::class, 'respond'])->name('information-requests.respond');
Route::delete('information-requests/{informationRequest}', [InformationRequestController::class, 'destroy'])->name('information-requests.destroy');
