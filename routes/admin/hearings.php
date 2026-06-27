<?php

use App\Http\Controllers\Admin\PublicHearingController;
use Illuminate\Support\Facades\Route;

Route::resource('hearings', PublicHearingController::class);
