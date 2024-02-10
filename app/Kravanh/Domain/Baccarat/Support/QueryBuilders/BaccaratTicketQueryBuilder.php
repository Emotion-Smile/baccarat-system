<?php

namespace App\Kravanh\Domain\Baccarat\Support\QueryBuilders;

use App\Kravanh\Domain\Baccarat\Actions\BaccaratGameGetLiveIdsAction;
use App\Kravanh\Domain\Baccarat\Models\BaccaratGame;
use App\Kravanh\Domain\Baccarat\Support\DateFilter;
use App\Kravanh\Domain\Baccarat\Support\BaccaratGameWinner;
use App\Kravanh\Domain\Baccarat\Support\TicketStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Date;

final class BaccaratTicketQueryBuilder extends Builder
{

    public function excludeOutstandingTickets(): BaccaratTicketQueryBuilder
    {
        $baccaratGameIds = app(BaccaratGameGetLiveIdsAction::class)();

        if (empty($baccaratGameIds)) {
            return $this;
        }

        $this->query->whereNotIn('baccarat_game_id', $baccaratGameIds);

        return $this;
    }

    public function whereGameTable(int|null $gameTableId): BaccaratTicketQueryBuilder
    {
        if (!$gameTableId) {
            return $this;
        }

        $this->query->where('game_table_id', $gameTableId);

        return $this;
    }

    public function filterBy(DateFilter $filter): BaccaratTicketQueryBuilder
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

    public function accepted(): BaccaratTicketQueryBuilder
    {
        $this->where('status', TicketStatus::Accepted);
        return $this;
    }

    public function onlyWinningTickets(BaccaratGame $game): BaccaratTicketQueryBuilder
    {
        $this
            ->where('baccarat_game_id', $game->id)
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

    public function onlyBetOnPlayerAndBankerTickets(BaccaratGame $game): BaccaratTicketQueryBuilder
    {
        $this
            ->where('baccarat_game_id', $game->id)
            ->accepted()
            ->where(function (Builder $query) {
                $query->where('bet_on', BaccaratGameWinner::Player)
                    ->where('bet_type', BaccaratGameWinner::Player)
                    ->orWhere(function (Builder $query) {
                        $query->where('bet_on', BaccaratGameWinner::Banker)
                            ->where('bet_type', BaccaratGameWinner::Banker);
                    });
            });

        return $this;
    }
}
