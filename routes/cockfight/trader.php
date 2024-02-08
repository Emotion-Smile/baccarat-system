<?php

use App\Kravanh\Application\Trader\Controllers\AcceptTicketController;
use App\Kravanh\Application\Trader\Controllers\BlockMemberWithdrawBalanceController;
use App\Kravanh\Application\Trader\Controllers\CreateNewOrAdjustPayoutMatchController;
use App\Kravanh\Application\Trader\Controllers\DisableBetActionController;
use App\Kravanh\Application\Trader\Controllers\EndMatchController;
use App\Kravanh\Application\Trader\Controllers\OpenBetController;
use App\Kravanh\Application\Trader\Controllers\ToggleBetButtonController;
use App\Kravanh\Application\Trader\Controllers\ToggleBetController;
use App\Kravanh\Application\Trader\Controllers\UpdateTicketTypeController;

Route::middleware([
    'auth:sanctum',
    'AllowUserType:trader,cockfight'
])->group(function () {


    Route::post('/disable-bet', DisableBetActionController::class)->name('match.disable-bet');
    Route::get('/open-bet', OpenBetController::class)->name('open-bet');
    Route::get('/accept-ticket', AcceptTicketController::class)->name('accept-ticket');
    Route::put('/ticket/update-type', UpdateTicketTypeController::class)->name('ticket.update-type');

    Route::prefix('match')
        ->group(function () {
            Route::post('/toggle-bet', ToggleBetController::class)->name('match.toggle-bet');
            Route::post('/member-type-toggle-button', ToggleBetButtonController::class)->name('match.toggle-bet-button');
            Route::post('/', CreateNewOrAdjustPayoutMatchController::class)->name('match.create-new');
            Route::put('/end', EndMatchController::class)->name('match.end');
            Route::post('/block-member-withdraw-balance', BlockMemberWithdrawBalanceController::class)->name('match.block-member-withdraw-balance');
        });
});
