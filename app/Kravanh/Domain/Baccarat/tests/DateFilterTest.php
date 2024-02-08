<?php

use App\Kravanh\Domain\DragonTiger\Support\DateFilter;
use Illuminate\Support\Facades\Date;

test('ensure behaviours date filter', function () {

    Date::setTestNow(Date::createFromDate(year: 2023, month: 10, day: 22));

    $today = DateFilter::Today;
    $yesterday = DateFilter::Yesterday;
    $thisMonth = DateFilter::ThisMonth;
    $lastMonth = DateFilter::LastMonth;
    $thisWeek = DateFilter::ThisWeek;
    $lastWeek = DateFilter::LastWeek;

    expect($today->isDay())->toBeTrue()
        ->and($yesterday->isDay())->toBeTrue()
        ->and($thisMonth->isMonth())->toBeTrue()
        ->and($lastMonth->isMonth())->toBeTrue()
        ->and($thisWeek->isWeek())->toBeTrue()
        ->and($lastWeek->isWeek())->toBeTrue()
        ->and($today->date())->toBe(20231022)
        ->and($yesterday->date())->toBe(20231021)
        ->and($thisMonth->date())->toBe(10)
        ->and($lastMonth->date())->toBe(9)
        ->and($thisWeek->date())->toMatchArray([20231016, 20231022])
        ->and($lastWeek->date())->toMatchArray([20231009, 20231015]);
});

test('ensure date filter convert from string')
    ->expect(DateFilter::fromStr('today'))->toBe(DateFilter::Today)
    ->and(DateFilter::fromStr('yesterday'))->toBe(DateFilter::Yesterday)
    ->and(DateFilter::fromStr('thisWeek'))->toBe(DateFilter::ThisWeek)
    ->and(DateFilter::fromStr('lastWeek'))->toBe(DateFilter::LastWeek)
    ->and(DateFilter::fromStr('thisMonth'))->toBe(DateFilter::ThisMonth)
    ->and(DateFilter::fromStr('lastMonth'))->toBe(DateFilter::LastMonth);

