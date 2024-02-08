<?php

namespace App\Kravanh\Domain\DragonTiger\Support;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Date;

final class DateHelper
{
    public static function toTime(Carbon $dateTime): string
    {
        return $dateTime->format(config('kravanh.time_format'));
    }

    public static function toDateTime(Carbon $dateTime): string
    {
        return $dateTime->format(config('kravanh.date_time_format'));
    }

    public static function toDate(Carbon $date): string
    {
        return $date->format('d-m-Y');
    }

    public static function today(): string
    {
        return Date::today()->toDateString();
    }

    public static function yesterday(): string
    {
        return Date::today()->subDay()->toDateString();
    }

    public static function yesterdayAt(string $time): string
    {
        return Date::today()->subDay()->toDateString() . ' ' . $time;
    }

    public static function thisWeek(): array
    {
        return [
            now()->startOfWeek()->toDateString(),
            now()->endOfWeek()->toDateString(),
        ];
    }

    public static function lastWeek(): array
    {
        return [
            now()->subWeek()->startOfWeek()->toDateString(),
            now()->subWeek()->endOfWeek()->toDateString(),
        ];
    }

    public static function thisMonth(): array
    {
        return [
            now()->firstOfMonth()->toDateString(),
            now()->endOfMonth()->toDateString(),
        ];
    }

    public static function lastMonth(): array
    {
        return [
            now()->subMonthNoOverflow()->startOfMonth()->toDateString(),
            now()->subMonthNoOverflow()->endOfMonth()->toDateString(),
        ];
    }


}
