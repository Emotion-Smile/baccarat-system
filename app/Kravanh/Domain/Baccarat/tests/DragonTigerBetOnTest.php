<?php

use App\Kravanh\Domain\DragonTiger\Support\DragonTigerBetOn;

test('ensure all bet option are valid', function ($betOn, $betType) {
    expect(DragonTigerBetOn::make($betOn, $betType)->isValid())->toBeTrue();
})->with([
    ['tiger', 'tiger'],
    ['tiger', 'red'],
    ['tiger', 'black'],
    ['tiger', 'small'],
    ['tiger', 'big'],
    ['dragon', 'dragon'],
    ['dragon', 'red'],
    ['dragon', 'black'],
    ['dragon', 'small'],
    ['dragon', 'big'],
    ['tie', 'tie'],
]);

test('ensure all bet option are invalid', function ($betOn, $betType) {
    expect(DragonTigerBetOn::make($betOn, $betType)->isValid())->toBeFalse();
})->with([
    ['tiger', 'tiger1'],
    ['tiger', 'dragon'],
    ['tie', 'tiger'],
]);
