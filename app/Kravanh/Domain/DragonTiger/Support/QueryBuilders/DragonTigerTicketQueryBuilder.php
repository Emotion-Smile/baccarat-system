<?php

namespace App\Kravanh\Domain\DragonTiger\Support\QueryBuilders;

use App\Kravanh\Domain\DragonTiger\Actions\DragonTigerGameGetLiveIdsAction;
use App\Kravanh\Domain\DragonTiger\Models\DragonTigerGame;
use App\Kravanh\Domain\DragonTiger\Support\DateFilter;
use App\Kravanh\Domain\DragonTiger\Support\DragonTigerGameWinner;
use App\Kravanh\Domain\DragonTiger\Support\TicketStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Date;

final class DragonTigerTicketQueryBuilder extends Builder
{

    public function excludeOutstandingTickets(): DragonTigerTicketQueryBuilder
    {
        $dragonTigerGameIds = app(DragonTigerGameGetLiveIdsAction::class)();

        if (empty($dragonTigerGameIds)) {
            return $this;
        }

        $this->query->whereNotIn('dragon_tiger_game_id', $dragonTigerGameIds);

        return $this;
    }

    public function whereGameTable(int|null $gameTableId): DragonTigerTicketQueryBuilder
    {
        if (!$gameTableId) {
            return $this;
        }

        $this->query->where('game_table_id', $gameTableId);

        return $this;
    }

    public function filterBy(DateFilter $filter): DragonTigerTicketQueryBuilder
    {
        $this
            ->when(
                $filter->isDay(),
                fn(Builder $query) => $query
                    ->where('in_day', $filter->date()))
            ->when(
                $filter->isMonth(),
                fn(Builder $query) => $query
                    ->where('in_year', Date::now()->year)
                    ->where('in_month', $filter->date()))
            ->when(
                $filter->isWeek(),
                fn(Builder $query) => $query
                    ->whereBetween('in_day', $filter->date())
            );

        return $this;
    }

    public function accepted(): DragonTigerTicketQueryBuilder
    {
        $this->where('status', TicketStatus::Accepted);
        return $this;
    }

    public function onlyWinningTickets(DragonTigerGame $game): DragonTigerTicketQueryBuilder
    {
        $this
            ->where('dragon_tiger_game_id', $game->id)
            ->accepted()
            ->where(function (Builder $query) use ($game) {
                $query->where('bet_on', $game->winner)
                    ->where('bet_type', $game->winner)
                    ->orWhere(function (Builder $query) use ($game) {
                        foreach ($game->makeSubResult() as $result) {
                            $where = explode('_', $result);
                            $query
                                ->orWhere('bet_on', $where[0])
                                ->where('bet_type', $where[1]);
                        }
                    });
            });

        return $this;
    }

    public function onlyBetOnDragonAndTigerTickets(DragonTigerGame $game): DragonTigerTicketQueryBuilder
    {
        $this
            ->where('dragon_tiger_game_id', $game->id)
            ->accepted()
            ->where(function (Builder $query) {
                $query->where('bet_on', DragonTigerGameWinner::Dragon)
                    ->where('bet_type', DragonTigerGameWinner::Dragon)
                    ->orWhere(function (Builder $query) {
                        $query->where('bet_on', DragonTigerGameWinner::Tiger)
                            ->where('bet_type', DragonTigerGameWinner::Tiger);
                    });
            });

        return $this;
    }
}
