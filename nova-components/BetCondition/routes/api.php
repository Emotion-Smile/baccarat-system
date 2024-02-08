<?php

use Illuminate\Support\Facades\Route;
use Kravanh\BetCondition\Controllers\BetConditionCreateController;
use Kravanh\BetCondition\Controllers\GetConditionController;
use Kravanh\BetCondition\Controllers\GetGroupsController;

Route::get('/get-groups', GetGroupsController::class);
Route::get('/get-condition/{groupId}/{memberId}/{parentId}', GetConditionController::class);
Route::post('/bet-condition', BetConditionCreateController::class);

