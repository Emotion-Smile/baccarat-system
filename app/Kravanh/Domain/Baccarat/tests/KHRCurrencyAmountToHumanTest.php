<?php

use App\Kravanh\Domain\DragonTiger\Support\KHRCurrencyAmountToHuman;

test('it can build human amount correctly', function () {

    expect(KHRCurrencyAmountToHuman::fromAmount(1000))->toBe('1 ពាន់')
        ->and(KHRCurrencyAmountToHuman::fromAmount(10000))->toBe('1 ម៉ឺន')
        ->and(KHRCurrencyAmountToHuman::fromAmount(100000))->toBe('10 ម៉ឺន')
        ->and(KHRCurrencyAmountToHuman::fromAmount(1000000))->toBe('1 លាន')
        ->and(KHRCurrencyAmountToHuman::fromAmount(10000000))->toBe('10 លាន');

});
