<?php

use App\Kravanh\Support\Enums\Currency;

test('from KHR to other currency', function ($amount, $exchangeRate, $currency) {
    $usd = fromKHRtoCurrency($amount, Currency::fromKey($currency));
    expect($usd)->toBe($amount / $exchangeRate);
})->with([
    [25000, 4000, Currency::USD],
    [5000, 134, Currency::THB],
    [5000, 0.18, Currency::VND],
    [5000, 1, Currency::KHR]
]);

test('from other currency to KHR', function ($amount, $exchangeRate, $currency) {
    $khr = toKHR($amount, Currency::fromKey($currency));
    expect($khr)->toBe($amount * $exchangeRate);
})->with([
    [5, 4000, Currency::USD],
    [50, 134, Currency::THB],
    [20000, 0.18, Currency::VND],
    [5000, 1, Currency::KHR]
]);
