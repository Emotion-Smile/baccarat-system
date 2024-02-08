<?php

use Illuminate\Support\Facades\Route;
use KravanhEco\Report\Modules\Mixed\Http\Controllers\WinLoseController;

Route::get('/win-lose', WinLoseController::class);