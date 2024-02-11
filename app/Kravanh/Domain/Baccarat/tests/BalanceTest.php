<?php

use App\Kravanh\Domain\Baccarat\Support\Balance;

test('it can format balance', function () {

    $usdBalance = Balance::toCurrency(6000, 'USD');
    expect($usdBalance)->toBe(1.50)
        ->and($usdBalance)->toBeFloat()
        ->and(number_format(1000))->toBe('1,000')
        ->and(number_format(1000.50, 2))->toBe('1,000.50');
});
