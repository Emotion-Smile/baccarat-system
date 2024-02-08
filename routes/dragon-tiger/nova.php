<?php

use App\Kravanh\Domain\DragonTiger\App\Nova\Http\Controllers\GetGameConditionFieldController;
use App\Kravanh\Domain\DragonTiger\App\Nova\Http\Controllers\SaveGameConditionController;
use Illuminate\Support\Facades\Route;

Route::get('/actions/{user}/fields', GetGameConditionFieldController::class)->name('dragon-tiger.get.game.condition.fields');
Route::post('/save', SaveGameConditionController::class)->name('dragon-tiger.save.game.condition');