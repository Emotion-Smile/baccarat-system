<?php

use App\Kravanh\Domain\Baccarat\App\Member\Controllers\BaccaratGameMemberController;
use Illuminate\Support\Facades\Route;

// Member
Route::middleware([
    'AllowUserType:member',
])
    ->group(function () {
        Route::get('/', BaccaratGameMemberController::class)->name('baccarat');
    });
