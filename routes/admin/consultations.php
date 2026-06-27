<?php

use App\Http\Controllers\Admin\ConsultationController;
use Illuminate\Support\Facades\Route;

Route::get('consultations', [ConsultationController::class, 'index'])->name('consultations.index');
Route::get('consultations/{consultation}', [ConsultationController::class, 'show'])->name('consultations.show');
Route::post('consultations/{consultation}/answer', [ConsultationController::class, 'answer'])->name('consultations.answer');
Route::delete('consultations/{consultation}', [ConsultationController::class, 'destroy'])->name('consultations.destroy');
