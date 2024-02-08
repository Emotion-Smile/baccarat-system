<?php

namespace App\Kravanh\Application\Admin\User\Filters;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Laravel\Nova\Filters\DateFilter;

class DateTimeFilter extends DateFilter
{
    protected $title;
    protected $column;
    protected $symbol;

    public function __construct($title, $column, $symbol)
    {
        $this->title = $title;
        $this->column = $column;
        $this->symbol = $symbol;
    }

    public function name()
    {
        return $this->title;
    }

    public function key()
    {
        return 'datetime_' . $this->column . '_' . Str::slug($this->title, '_');
    }

    public function apply(Request $request, $query, $value)
    {
        return $query->where($this->column, $this->symbol, Carbon::parse($value));
    }
}
