<?php

namespace App\Kravanh\Application\Admin\Match\Filters;

use Ampeco\Filters\DateRangeFilter;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DateRange extends DateRangeFilter
{
    public $name = "Date";

    public function __construct(private string $column)
    {
    }

    public function apply(Request $request, $query, $value)
    {
        $from = Carbon::parse($value[0])->startOfDay();
        $to = Carbon::parse($value[1])->endOfDay();

        return $query->whereBetween($this->column, [$from, $to]);
    }


    public function options(Request $request): array
    {
        return [
            'mode' => 'range'
        ];
    }
}
