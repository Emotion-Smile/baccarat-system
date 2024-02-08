<?php

use App\Kravanh\Application\FeedbackCustomerController;
use App\Kravanh\Application\Member\Controllers\BettingController;
use App\Kravanh\Application\Member\Controllers\BettingHistoryController;
use App\Kravanh\Application\Member\Controllers\CockfightController;
use App\Kravanh\Application\Member\Controllers\DepositWithdrawController;
use App\Kravanh\Application\Member\Controllers\GetMessageController;
use App\Kravanh\Application\Member\Controllers\LogoutManagerController;
use App\Kravanh\Application\Member\Controllers\MarkMessageAsReadController;
use App\Kravanh\Application\Member\Controllers\MessageController;
use App\Kravanh\Application\Member\Controllers\SwitchGroupController;
use App\Kravanh\Application\Member\Controllers\TicketPrintController;
use App\Kravanh\Application\Member\Controllers\TotalBetCurrentMatchController;
use App\Kravanh\Application\SetLocaleController;

Route::middleware([
    'auth:sanctum',
    'AllowUserType:member',
])->group(function () {

    Route::get('/member', CockfightController::class)->name('member');

    //not used
    //Route::get('/member/video-histories', VideoHistoryController::class)->name('video.histories');

    Route::get('/member/betting', BettingHistoryController::class)->name('member.betting.history');
    Route::get('/member/deposit', DepositWithdrawController::class)->name('member.deposit');
    Route::get('/member/print/{id}', TicketPrintController::class)->name('member.print');
    Route::post('/member/match/betting', BettingController::class)->name('member.match.betting');

    Route::get('/refresh-balance', function () {

        $user = request()->user();

        return [
            'balance' => priceFormat($user?->getCurrentBalance() ?? 0, $user?->currency),
        ];
    })
        ->name('balance.refresh');

    Route::get('/total-bet-current-match', TotalBetCurrentMatchController::class)->name('member.match.total-bet');

    Route::get('/messages', GetMessageController::class)->name('member.get.messages');
    Route::post('/message/{message}/mark-as-read', MarkMessageAsReadController::class)->name('member.mark.message.as.read');
    Route::get('/member/messages', MessageController::class)->name('member.messages');

    Route::get('/feedback', [FeedbackCustomerController::class, 'create'])->name('member.feedback');
    Route::post('/feedback', [FeedbackCustomerController::class, 'store'])->name('member.feedback.store');

    Route::put('/switch-group', SwitchGroupController::class)->name('member.switch-group');

    Route::get('/should-logout', LogoutManagerController::class)->name('member.should-logout');

    Route::post('/locale', SetLocaleController::class)->name('locale');
});
