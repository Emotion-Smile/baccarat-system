<?php

use App\Kravanh\Domain\Baccarat\Support\BaccaratBetOn;

test('ensure all bet option are valid', function ($betOn, $betType) {
    expect(BaccaratBetOn::make($betOn, $betType)->isValid())->toBeTrue();
})->with([
    ['banker', 'banker'],
    ['banker_pair', 'banker_pair'],
    ['small', 'small'],
    ['big', 'big'],
    ['player', 'player'],
    ['player_pair', 'player_pair'],
    ['tie', 'tie'],
]);

test('ensure all bet option are invalid', function ($betOn, $betType) {
    expect(BaccaratBetOn::make($betOn, $betType)->isValid())->toBeFalse();
})->with([
    ['player', 'player1'],
    ['banker', 'player'],
    ['tie', 'banker'],
]);
