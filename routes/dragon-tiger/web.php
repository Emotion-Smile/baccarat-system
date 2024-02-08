<?php

use App\Kravanh\Application\FeedbackCustomerController;
use App\Kravanh\Application\Member\Controllers\DepositWithdrawController;
use App\Kravanh\Domain\DragonTiger\App\Member\Controllers\DragonTigerGameMemberBettingHistoryController;
use App\Kravanh\Domain\DragonTiger\App\Member\Controllers\DragonTigerGameMemberController;
use App\Kravanh\Domain\DragonTiger\App\Member\Controllers\DragonTigerGameMemberSwitchTableActionController;
use App\Kravanh\Domain\DragonTiger\App\Trader\Controllers\DragonTigerGameDealerController;
use App\Kravanh\Domain\DragonTiger\App\Trader\Controllers\DragonTigerGameTraderController;
use App\Kravanh\Domain\DragonTiger\Support\Middleware\EnsureDragonTigerGameTableIdOnMember;
use App\Kravanh\Domain\DragonTiger\Support\Middleware\EnsureUserCanPlayDragonTigerGame;
use Illuminate\Support\Facades\Route;

// Member
Route::middleware([
    EnsureUserCanPlayDragonTigerGame::class,
    EnsureDragonTigerGameTableIdOnMember::class,
    'AllowUserType:member',
])->group(function () {

    Route::get('/', DragonTigerGameMemberController::class)->name('dragon-tiger');
    Route::get('/betting-history', DragonTigerGameMemberBettingHistoryController::class)->name('dragon-tiger.betting.history');
    Route::get('/feedback', [FeedbackCustomerController::class, 'create'])->name('dragon-tiger.feedback');
    Route::get('/deposit', DepositWithdrawController::class)->name('dragon-tiger.deposit');
    Route::put('/switch-table', DragonTigerGameMemberSwitchTableActionController::class)->name('dragon-tiger.switch-table');

    //Moving file from local storage to cloud S3
    Route::get('/test', function (Illuminate\Http\Request $request) {
        $files = Storage::disk('srv')->allFiles();
        foreach ($files as $file) {
            // Retrieve file contents from local storage
            $contents = Storage::disk('srv')->get($file);
            // Store the file in S3 with its original name
            Storage::disk('s3')->put('music/'.basename($file), $contents);
            // Set the file permissions to publicly readable
            Storage::disk('s3')->setVisibility('music/'.basename($file), 'public');
        }
    });
});

Route::middleware([
    'AllowUserType:trader,dragonTiger',
])->group(function () {
    Route::get('/trader', DragonTigerGameTraderController::class)
        ->name('dragon-tiger.trader');

    Route::get('/dealer', DragonTigerGameDealerController::class)
        ->name('dragon-tiger.dealer');
});
