<?php

namespace KravanhEco\Report\Modules\DragonTiger\Actions;

use App\Kravanh\Domain\DragonTiger\Actions\DragonTigerGameGetLiveIdsAction;
use App\Kravanh\Domain\DragonTiger\Models\DragonTigerTicket;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Date;
use KravanhEco\Report\Modules\DragonTiger\Support\Helpers\DateFilterHelper;

class GetBetDetailAction
{
    public function __invoke(User $user, string $date): LengthAwarePaginator
    {
        return DragonTigerTicket::query()
            ->with([
                'user:id,name,currency',
                'gameTable:id,label',
                'game'
            ])
            ->whereNotIn(
                'dragon_tiger_game_id',
                (new DragonTigerGameGetLiveIdsAction())()
            )
            ->where(function ($query) use ($date) {
                $filter = DateFilterHelper::from($date);
                
                return $query->when(
                    $filter->isDay(),
                    fn($query) => $query->where('in_day', $filter->date())
                )
                    ->when(
                        $filter->isMonth(),
                        fn($query) => $query
                            ->where('in_year', Date::now()->year)
                            ->where('in_month', $filter->date())
                    )
                    ->when(
                        $filter->isWeek() || $filter->isDateRange(),
                        fn($query) => $query->whereBetween('in_day', $filter->date())
                    );
            })
            ->whereUserId($user->id)
            ->orderByDesc('id')
            ->paginate();
    }
}
