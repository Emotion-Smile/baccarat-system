<?php

use App\Kravanh\Domain\Baccarat\Exceptions\BaccaratCardException;
use App\Kravanh\Domain\Baccarat\Support\BaccaratCard;

test('it throw exception if card range invalid', function (int $rangeNumber) {
    BaccaratCard::make(BaccaratCard::Heart, $rangeNumber);
})
    ->with([0, 14])
    ->expectExceptionMessage(BaccaratCardException::invalidRange()->getMessage());

test('it throw exception if card type invalid', function (string $type) {
    BaccaratCard::make($type, 1);
})
    ->with(['unknown', ''])
    ->expectExceptionMessage(BaccaratCardException::invalidType()->getMessage());

test('ensure card type are valid', function (string $type) {

    $card = BaccaratCard::make($type, 1);

    $color = match ($type) {
        'heart', 'diamond' => 'red',
        'spade', 'club' => 'black'
    };

    expect($card->type)->toBe($type)
        ->and($card->color())->toBe($color);

})->with(['heart', 'diamond', 'spade', 'club']);

//test('ensure care range are valid', function (int $number) {
//
//    $card = BaccaratCard::make(BaccaratCard::Heart, $number);
//
//    $range = 'small';
//
//    if ($number === 7) {
//        $range = 'middle';
//    }
//
//    if ($number > 7) {
//        $range = 'big';
//    }
//
//    expect($card->number)->toBe($number)
//        ->and($card->range())->toBe($range);
//
//})->with([1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13]);


