<?php

use App\Kravanh\Domain\Integration\Http\Controllers\Api\BettingController;
use App\Kravanh\Domain\Integration\Http\Controllers\Api\LoginController;
use App\Kravanh\Domain\Integration\Http\Controllers\Api\LogoutController;
use App\Kravanh\Domain\Integration\Http\Controllers\Api\MarkAsMemberOfflineController;
use App\Kravanh\Domain\Integration\Http\Controllers\Api\MemberDetailController;
use App\Kravanh\Domain\Integration\Http\Controllers\Api\PayoutController;
use App\Kravanh\Domain\Integration\Http\Controllers\Api\RollbackPayoutController;
use App\Kravanh\Domain\Integration\Http\Middleware\AllowMemberOnly;
use Illuminate\Support\Facades\Route;

Route::post('/login', LoginController::class);

Route::middleware(['auth:sanctum', AllowMemberOnly::class])
    ->group(function () {
        Route::get('/member/detail', MemberDetailController::class);
        Route::post('/member/betting', BettingController::class);
        Route::post('/member/payout', PayoutController::class);
        Route::post('/member/rollback/payout', RollbackPayoutController::class);
        Route::post('/member/mark/as/offline', MarkAsMemberOfflineController::class);
        Route::post('/logout', LogoutController::class);
    });