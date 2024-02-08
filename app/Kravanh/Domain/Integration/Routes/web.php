<?php

use App\Kravanh\Domain\Integration\Http\Controllers\Member\GatewayController;
// use App\Kravanh\Domain\Integration\Http\Controllers\Member\GameEmbedController;
// use App\Kravanh\Domain\Integration\Http\Middleware\VerifyGameEmbed;
use Illuminate\Support\Facades\Route;

Route::middleware([
    'auth:sanctum', 
    'AllowUserType:member',
])
    ->group(function () {
        Route::get('/gateway/{site}', GatewayController::class)
            ->name('member.integration.gateway');

        // Route::get('/game/{game}', GameEmbedController::class)
        //     ->middleware([VerifyGameEmbed::class])
        //     ->name('member.game.embed');
    });