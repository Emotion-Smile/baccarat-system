<?php

use Illuminate\Support\Facades\Route;
use KravanhEco\Report\Modules\Core\Http\Controllers\BalanceStatementController;

Route::get('/balance/statement/{member}', BalanceStatementController::class);