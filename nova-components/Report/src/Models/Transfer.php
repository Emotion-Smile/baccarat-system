<?php

namespace KravanhEco\Report\Models;

use App\Kravanh\Support\Enums\Period;
use Bavix\Wallet\Models\Transfer as BaseTransfer;
use Illuminate\Database\Eloquent\Builder;

class Transfer extends BaseTransfer
{
    public function scopeFilterByPeriod($query, string $column)
    {
        $date = request('date') ?? null;

        return $query->when($date, function (Builder $query, string $date) use ($column) {

            if ($date === Period::TODAY) {
                $query->whereDate($column, now()->format('Y-m-d'));
            }

            if ($date === Period::YESTERDAY) {
                $query->whereDate($column, now()->subDay()->format('Y-m-d'));
            }

            if ($date === Period::THIS_WEEK) {
                $query->whereDate($column, '>=', now()->startOfWeek()->format('Y-m-d'))
                    ->whereDate($column, '<=', now()->endOfWeek()->format('Y-m-d'));
            }

            if ($date === Period::LAST_WEEK) {
                $query->whereDate($column, '>=', now()->subWeek()->startOfWeek()->format('Y-m-d'))
                    ->whereDate($column, '<=', now()->subWeek()->endOfWeek()->format('Y-m-d'));
            }

            if ($date === Period::CURRENT_MONTH) {
                $query->whereMonth($column, date('m'))
                    ->whereYear($column, date('Y'));
            }

            if ($date === Period::LAST_MONTH) {
                $query->whereDate($column, '>=', now()->subMonthNoOverflow()->startOfMonth()->format('Y-m-d'))
                    ->whereDate($column, '<=', now()->subMonthNoOverflow()->endOfMonth()->format('Y-m-d'));
            }
        });
    }

    public function scopeFilterByDateRage($query, string $column)
    {
        return $query->when(request('from') && request('to'), function (Builder $query) use ($column) {
            $query->whereDate($column, '>=', request('from'))
                ->whereDate($column, '<=', request('to'));
        });
    }
}
