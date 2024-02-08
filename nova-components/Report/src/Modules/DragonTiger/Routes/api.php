<?php

use Illuminate\Support\Facades\Route;
use KravanhEco\Report\Modules\DragonTiger\Http\Controllers\BalanceStatementController;
use KravanhEco\Report\Modules\DragonTiger\Http\Controllers\BetDetailController;
use KravanhEco\Report\Modules\DragonTiger\Http\Controllers\WinLoseController;

Route::get('/win-lose', WinLoseController::class);
Route::get('/bet-detail/{user}', BetDetailController::class);
Route::get('/balance/statement/{member}', BalanceStatementController::class);