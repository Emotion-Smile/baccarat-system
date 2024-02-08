<?php

namespace KravanhEco\Report\Modules\DragonTiger\Actions;

use App\Kravanh\Domain\User\Models\Member;
use App\Kravanh\Support\Enums\Period;
use App\Models\User;
use Bavix\Wallet\Models\Transaction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Date;

class GetBalanceStatementAction
{
    public function __invoke(User $user, string $date): LengthAwarePaginator
    {
        return Transaction::query()
            ->with('payable')
            ->where('payable_type', Member::class)
            ->where('payable_id', $user->id)
            ->when($date, function (Builder $query, string $date) {
                $column = 'created_at';
               
                return match ($date) {
                    Period::TODAY => $query->whereDate($column, Date::today()->format('Y-m-d')),

                    Period::YESTERDAY => $query->whereDate($column, Date::today()->subDay()->format('Y-m-d')),
                    
                    Period::THIS_WEEK => $query->whereDate($column, '>=', Date::now()->startOfWeek()->format('Y-m-d'))
                        ->whereDate($column, '<=', Date::now()->endOfWeek()->format('Y-m-d')),

                    Period::LAST_WEEK => $query->whereDate($column, '>=', Date::now()->subWeek()->startOfWeek()->format('Y-m-d'))
                        ->whereDate($column, '<=', Date::now()->subWeek()->endOfWeek()->format('Y-m-d')),

                    Period::CURRENT_MONTH => $query->whereMonth($column, date('m'))->whereYear($column, date('Y')), 

                    Period::LAST_MONTH => $query->whereDate($column, '>=', Date::now()->subMonthNoOverflow()->startOfMonth()->format('Y-m-d'))
                        ->whereDate($column, '<=', Date::now()->subMonthNoOverflow()->endOfMonth()->format('Y-m-d')) ,
                        
                    default => $query->whereDate($column, '>=', explode(',', $date)[0])
                        ->whereDate($column, '<=', explode(',', $date)[1])
                };
            })
            ->orderByDesc('id')
            ->paginate();
    }
}