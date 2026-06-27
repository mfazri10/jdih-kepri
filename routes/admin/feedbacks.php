<?php

use App\Http\Controllers\Admin\FeedbackController;
use Illuminate\Support\Facades\Route;

Route::get('feedbacks', [FeedbackController::class, 'index'])->name('feedbacks.index');
Route::get('feedbacks/{feedback}', [FeedbackController::class, 'show'])->name('feedbacks.show');
Route::post('feedbacks/{feedback}/reply', [FeedbackController::class, 'reply'])->name('feedbacks.reply');
Route::delete('feedbacks/{feedback}', [FeedbackController::class, 'destroy'])->name('feedbacks.destroy');
