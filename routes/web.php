<?php

use App\Kravanh\Application\Environment\GroupController;
use App\Kravanh\Application\GetUserBalanceController;
use App\Kravanh\Application\Test\RaceConditionController;
use App\Kravanh\Domain\Baccarat\App\Member\Controllers\BaccaratGameMemberController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {

    if (Auth::guard('member')->guest()) {
        return redirect()->to('login');
    }

    $user = request()->user();

    if ($user->isTraderDragonTiger()) {
        return redirect()->route('dragon-tiger.trader');
    }

    if ($user->isTraderCockfight()) {
        return redirect()->route('accept-ticket');
    }

    return redirect()->route('member');

});

Route::get('/baccarat', BaccaratGameMemberController::class)->name('baccarat');

Route::post('/refresh-balance/{id}/{type}', GetUserBalanceController::class)
    ->name('user.balance');

Route::get('/env/group', GroupController::class)
    ->middleware('auth:sanctum')
    ->name('env.group');

require __DIR__.'/cockfight/member.php';
require __DIR__.'/cockfight/trader.php';

if (app()->environment('local') || app()->runningUnitTests()) {
    Route::post('test/withdraw', [RaceConditionController::class, 'withdraw'])->name('test.withdraw');
    Route::post('test/deposit', [RaceConditionController::class, 'deposit'])->name('test.deposit');
    Route::post('test/withdraw-lock-timeout-exception', [RaceConditionController::class, 'withdrawLockTimeoutException'])->name('test.withdraw-lock-timeout-exception');
}
