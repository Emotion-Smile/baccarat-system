<?php

use Illuminate\Support\Facades\Route;
use KravanhEco\Report\Http\Controllers\AF88MemberOutstanding\AF88MemberOutstandingController;
use KravanhEco\Report\Http\Controllers\AF88MemberOutstanding\AF88MemberOutstandingDetailController;
use KravanhEco\Report\Http\Controllers\AF88WinLose\AF88MixParlayBetDetailController;
use KravanhEco\Report\Http\Controllers\AF88WinLose\AF88SingleBetDetailController;
use KravanhEco\Report\Http\Controllers\AF88WinLose\AF88WinLoseController;
use KravanhEco\Report\Http\Controllers\AF88WinLose\BalanceStatementController as AF88WinLoseBalanceStatementController;
use KravanhEco\Report\Http\Controllers\MissingPayout\MissingPayoutReportController;
use KravanhEco\Report\Http\Controllers\OutstandingTicket\OutstandingTicketReportController;
use KravanhEco\Report\Http\Controllers\Payment\PaymentReportController;
use KravanhEco\Report\Http\Controllers\T88OutstandingTicket\T88OutstandingTicketController;
use KravanhEco\Report\Http\Controllers\T88WinLose\BalanceStatementController;
use KravanhEco\Report\Http\Controllers\T88WinLose\T88BetDetailController;
use KravanhEco\Report\Http\Controllers\T88WinLose\T88WinLoseController;
use KravanhEco\Report\Http\Controllers\TopWinner\TopWinnerController;
use KravanhEco\Report\Http\Controllers\WinLoss\BetDetailController;
use KravanhEco\Report\Http\Controllers\WinLoss\BetSummaryController;
use KravanhEco\Report\Http\Controllers\WinLoss\BetSummaryV2Controller;
use KravanhEco\Report\Http\Controllers\WinLoss\StatementBalanceController;
use KravanhEco\Report\Http\Middleware\AllowedCompanyOnly;

/*
|--------------------------------------------------------------------------
| Tool API Routes
|--------------------------------------------------------------------------
|
| Here is where you may register API routes for your tool. These routes
| are loaded by the ServiceProvider of your tool. They are protected
| by your tool's "Authorize" middleware by default. Now, go build!
|
*/

Route::get('/bet-summaries', BetSummaryController::class);
Route::get('/v2/bet-summaries', BetSummaryV2Controller::class);
Route::get('/bet-detail/{member}', BetDetailController::class);
Route::get('/statement-balances/{member}', StatementBalanceController::class);
Route::get('/payment-reports/{subUser?}', PaymentReportController::class);
Route::get('/outstanding-ticket-reports', OutstandingTicketReportController::class);
Route::get('/missing-payouts', MissingPayoutReportController::class);
Route::get('/top-winners', TopWinnerController::class)
    ->middleware(AllowedCompanyOnly::class);


Route::prefix('/t88')
    ->group(function () {
        Route::prefix('/win-lose')
            ->group(function () {
                Route::get('/', T88WinLoseController::class);
                Route::get('/balance-statement/{name}', BalanceStatementController::class);
                Route::get('/bet-detail/{name}', T88BetDetailController::class);
            });

        Route::prefix('/outstanding/tickets')
            ->group(function () {
                Route::get('/', T88OutstandingTicketController::class);
            });
    });

Route::prefix('/af88')
    ->group(function () {
        Route::prefix('/win-lose')
            ->group(function () {
                Route::get('/', AF88WinLoseController::class);
                Route::get('/single-bet/detail/{name}', AF88SingleBetDetailController::class);
                Route::get('/mix-parlay-bet/detail/{name}', AF88MixParlayBetDetailController::class);
                Route::get('/balance-statement/{name}', AF88WinLoseBalanceStatementController::class);
            });

        Route::prefix('member/outstanding')
            ->group(function () {
                Route::get('/', AF88MemberOutstandingController::class);
                Route::get('/detail/{accountId}', AF88MemberOutstandingDetailController::class);
            });
    });

