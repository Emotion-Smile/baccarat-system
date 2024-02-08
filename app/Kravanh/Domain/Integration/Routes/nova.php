<?php

use App\Kravanh\Domain\Integration\Nova\Http\Controllers\T88\GetGameConditionFieldController;
use App\Kravanh\Domain\Integration\Nova\Http\Controllers\T88\SaveGameConditionController;
use Illuminate\Support\Facades\Route;

Route::get('/actions/{user}/fields', GetGameConditionFieldController::class)->name('t88.get.game.condition.fields');
Route::post('/save', SaveGameConditionController::class)->name('t88.save.game.condition');