<?php

use App\Kravanh\Application\Admin\Match\Controllers\ExcelController;
use App\Kravanh\Application\Trader\Controllers\Api\CockFightCreateNewMatchApiController;
use App\Kravanh\Application\Trader\Controllers\Api\CockFightEndMatchApiController;
use App\Kravanh\Application\Trader\Controllers\Api\CockFightPayoutAdjustmentApiController;
use App\Kravanh\Application\Trader\Controllers\Api\CockFightToggleBetApiController;
use App\Kravanh\Domain\Environment\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Routing\Middleware\ValidateSignature;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('env/group', function () {
    return Group::query()
        ->select(['iframe_allow', 'direct_link_allow', 'streaming_server_ip'])
        ->where('environment_id', 1)
        ->first();
});

Route::get('download', ExcelController::class)
    ->name('laravel-nova-excel.download')
    ->middleware(ValidateSignature::class);

Route::middleware(['auth:sanctum'])
    ->prefix('match')
    ->group(function () {
        Route::post('/new', CockFightCreateNewMatchApiController::class)->name('api.match.new');
        Route::post('/toggle-bet', CockFightToggleBetApiController::class)->name('api.match.toggle-bet');
        Route::post('/adjust', CockFightPayoutAdjustmentApiController::class)->name('api.match.adjust');
        Route::post('/submit-result', CockFightEndMatchApiController::class)->name('api.match.submit-result');
    });
