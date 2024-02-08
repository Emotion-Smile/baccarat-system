<?php

namespace App\Kravanh\Domain\Baccarat\Support;

use Illuminate\Support\Facades\Date;

enum DateFilter
{
    case Today;
    case Yesterday;
    case ThisWeek;
    case LastWeek;
    case ThisMonth;
    case LastMonth;

    public static function fromStr(string $date): DateFilter
    {
        return match ($date) {
            'yesterday' => self::Yesterday,
            'thisWeek' => self::ThisWeek,
            'lastWeek' => self::LastWeek,
            'thisMonth' => self::ThisMonth,
            'lastMonth' => self::LastMonth,
            default => self::Today
        };
    }

    public function isDay(): bool
    {
        return in_array($this, [self::Today, self::Yesterday]);
    }

    public function isMonth(): bool
    {
        return in_array($this, [self::ThisMonth, self::LastMonth]);
    }

    public function isWeek(): bool
    {
        return in_array($this, [self::ThisWeek, self::LastWeek]);
    }

    public function date(): int|array
    {
        return match ($this) {
            self::Today => (int)Date::today()->format('Ymd'),
            self::Yesterday => (int)Date::yesterday()->format('Ymd'),
            self::ThisWeek => [
                (int)Date::now()->startOfWeek()->format('Ymd'),
                (int)Date::now()->endOfWeek()->format('Ymd'),
            ],
            self::LastWeek => [
                (int)Date::now()->subWeek()->startOfWeek()->format('Ymd'),
                (int)Date::now()->subWeek()->endOfWeek()->format('Ymd'),
            ],
            self::ThisMonth => Date::now()->month,
            self::LastMonth => Date::now()->subMonthNoOverflow()->month
        };
    }
}
