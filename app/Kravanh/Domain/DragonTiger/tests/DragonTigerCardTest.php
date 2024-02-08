<?php

use App\Kravanh\Domain\DragonTiger\Exceptions\DragonTigerCardException;
use App\Kravanh\Domain\DragonTiger\Support\DragonTigerCard;

test('it throw exception if card range invalid', function (int $rangeNumber) {
    DragonTigerCard::make(DragonTigerCard::Heart, $rangeNumber);
})
    ->with([0, 14])
    ->expectExceptionMessage(DragonTigerCardException::invalidRange()->getMessage());

test('it throw exception if card type invalid', function (string $type) {
    DragonTigerCard::make($type, 1);
})
    ->with(['unknown', ''])
    ->expectExceptionMessage(DragonTigerCardException::invalidType()->getMessage());

test('ensure card type are valid', function (string $type) {

    $card = DragonTigerCard::make($type, 1);

    $color = match ($type) {
        'heart', 'diamond' => 'red',
        'spade', 'club' => 'black'
    };

    expect($card->type)->toBe($type)
        ->and($card->color())->toBe($color);

})->with(['heart', 'diamond', 'spade', 'club']);

test('ensure care range are valid', function (int $number) {

    $card = DragonTigerCard::make(DragonTigerCard::Heart, $number);

    $range = 'small';

    if ($number === 7) {
        $range = 'middle';
    }

    if ($number > 7) {
        $range = 'big';
    }

    expect($card->number)->toBe($number)
        ->and($card->range())->toBe($range);

})->with([1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13]);


