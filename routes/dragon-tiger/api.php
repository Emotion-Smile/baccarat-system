<?php

use App\Kravanh\Domain\DragonTiger\App\Member\Controllers\Api\DragonTigerGameBetController;
use App\Kravanh\Domain\DragonTiger\App\Member\Controllers\Api\DragonTigerGameRebettingController;
use App\Kravanh\Domain\DragonTiger\App\Trader\Controllers\Api\DragonTigerGameCardScannerController;
use App\Kravanh\Domain\DragonTiger\App\Trader\Controllers\Api\DragonTigerGameCreateNewController;
use App\Kravanh\Domain\DragonTiger\App\Trader\Controllers\Api\DragonTigerGameResubmitResultController;
use App\Kravanh\Domain\DragonTiger\App\Trader\Controllers\Api\DragonTigerGameSubmitCancelResultController;
use App\Kravanh\Domain\DragonTiger\App\Trader\Controllers\Api\DragonTigerGameSubmitResultController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::middleware(['AllowUserType:member'])
    ->group(function () {
        Route::post('/betting', DragonTigerGameBetController::class)->name('dragon-tiger.betting');
        Route::post('/rebetting', DragonTigerGameRebettingController::class)->name('dragon-tiger.rebetting');

        Route::get('/music/{fileName}', function ($fileName) {
            $storage = Storage::disk('s3');
            $fileName = "music/$fileName";

            $isMissing = $storage->missing("$fileName.webm");
            $fileName .= ! $isMissing ? '.webm' : '.mp3';

            if (! $storage->exists($fileName)) {
                abort(404);
            }

            return $storage->get($fileName);

        })->name('music.track');
    });

Route::middleware(['AllowUserType:trader,dragonTiger'])
    ->group(function () {
        Route::post('/create-new-game', DragonTigerGameCreateNewController::class)->name('dragon-tiger.create-new-game');
        Route::put('/submit-result', DragonTigerGameSubmitResultController::class)->name('dragon-tiger.submit-result');
        Route::put('/resubmit-result', DragonTigerGameResubmitResultController::class)->name('dragon-tiger.resubmit-result');
        Route::put('/submit-cancel-result', DragonTigerGameSubmitCancelResultController::class)->name('dragon-tiger.submit-cancel-result');

        Route::post('/card-scanner', DragonTigerGameCardScannerController::class)->name('dragon-tiger.card-scanner');

    });
