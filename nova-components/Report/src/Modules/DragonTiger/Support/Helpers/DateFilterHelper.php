<?php

namespace KravanhEco\Report\Modules\DragonTiger\Support\Helpers;

use App\Kravanh\Support\Enums\Period;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Str;

class DateFilterHelper
{ 
    public function __construct(
        protected string $date
    )
    {}

    public static function from(string $date): DateFilterHelper
    {
        return new static($date);
    }

    public function isDay(): bool
    {
        return in_array($this->date, [Period::TODAY, Period::YESTERDAY]);
    }

    public function isWeek(): bool
    {
        return in_array($this->date, [Period::THIS_WEEK, Period::LAST_WEEK]);
    }

    public function isMonth(): bool
    {
        return in_array($this->date, [Period::CURRENT_MONTH, Period::LAST_MONTH]);
    }

    public function isDateRange(): bool
    {
        return ! in_array($this->date, Period::getValues());
    }

    public function date(): int|array
    {
        return match ($this->date) {
            Period::TODAY => (int) Date::today()->format('Ymd'),
            Period::YESTERDAY => (int) Date::yesterday()->format('Ymd'),
            Period::THIS_WEEK => [
                (int) Date::now()->startOfWeek()->format('Ymd'),
                (int) Date::now()->endOfWeek()->format('Ymd'),
            ],
            Period::LAST_WEEK => [
                (int) Date::now()->subWeek()->startOfWeek()->format('Ymd'),
                (int) Date::now()->subWeek()->endOfWeek()->format('Ymd'),
            ],
            Period::CURRENT_MONTH => Date::now()->month,
            Period::LAST_MONTH => Date::now()->subMonthNoOverflow()->month,
            default => $this->dateRangeHandler()
        };
    }

    protected function dateRangeHandler(): array
    {
        if (! Str::of($this->date)->contains(',')) {
            throw new \InvalidArgumentException('Invalid date range');
        }

        $dateRange = Str::of($this->date)->explode(',');

        $start = Date::createFromFormat('Y-m-d', $dateRange->first());
        $end = Date::createFromFormat('Y-m-d', $dateRange->last());

        if ($start->gt($end)) {
            throw new \InvalidArgumentException('The start date must less than end date');
        }

        return [
            $start->format('Ymd'),
            $end->format('Ymd'),
        ];
    }
}
