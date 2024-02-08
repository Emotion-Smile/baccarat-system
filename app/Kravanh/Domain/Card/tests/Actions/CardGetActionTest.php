<?php

use App\Kravanh\Domain\Card\Actions\CardGetAction;
use Illuminate\Support\ItemNotFoundException;

test('it will throw ItemNotFoundException if card not found', function () {
    (new CardGetAction())(11);
})->expectException(ItemNotFoundException::class);

test('it can get card', function () {
    $card = (new CardGetAction())(1010);
    expect($card)->toHaveKeys(['code', 'name', 'value', 'type']);
});
