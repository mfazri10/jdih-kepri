<?php

use App\Http\Controllers\Admin\LoginLogController;
use Illuminate\Support\Facades\Route;

Route::get('login-logs', [LoginLogController::class, 'index'])->name('login-logs.index');
