<?php

namespace App\Kravanh\Application\Admin\User\Filters;

use App\Kravanh\Support\Enums\Period;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Laravel\Nova\Filters\Filter;

class PeriodFilter extends Filter
{
    public $component = 'select-filter';

    protected $column;
    protected $default;

    public function __construct(string $column, string $defaultValue = '')
    {
        $this->column = $column; 
        $this->default = $defaultValue;
    }

    public function apply(Request $request, $query, $value)
    {
        if ($value === Period::TODAY) {
            $query->whereDate($this->column, Date::today()->format('Y-m-d'));
        }

        if ($value === Period::YESTERDAY) {
            $query->whereDate($this->column, Date::today()->subDay()->format('Y-m-d'));
        }

        if ($value === Period::THIS_WEEK) {
            $query->whereDate($this->column, '>=', now()->startOfWeek()->format('Y-m-d'))
                ->whereDate($this->column, '<=', now()->startOfWeek()->format('Y-m-d'));
        }

        if ($value === Period::LAST_WEEK) {
            $query->whereDate($this->column, '>=', now()->subWeek()->startOfWeek()->format('Y-m-d'))
                ->whereDate($this->column, '<=', now()->subWeek()->endOfWeek()->format('Y-m-d'));
        }

        if ($value === Period::CURRENT_MONTH) {
            $query->whereMonth($this->column, date('m'))
                ->whereYear($this->column, date('Y'));
        }

        if ($value === Period::LAST_MONTH) {
            $query->whereDate($this->column, '>=',  now()->subMonth()->startOfMonth()->format('Y-m-d'))
                ->whereDate($this->column, '<=', now()->subMonth()->endOfMonth()->format('Y-m-d'));
        }
    }

    public function options(Request $request)
    {
        return Period::options();
    }

    public function default()
    {
        return $this->default;
    }
}
