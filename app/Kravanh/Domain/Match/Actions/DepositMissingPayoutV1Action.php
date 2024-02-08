<?php

namespace App\Kravanh\Domain\Match\Actions;

use App\Kravanh\Domain\Match\Exceptions\MatchResultInvalid;
use App\Kravanh\Domain\Match\Jobs\DepositMissingPayoutJop;
use App\Kravanh\Domain\Match\Models\Matches;
use App\Kravanh\Domain\Match\Supports\Enums\BetStatus;
use Illuminate\Database\Eloquent\Collection;

class DepositMissingPayoutV1Action
{
    /**
     * @throws \Exception
     * @throws \Throwable
     */
    public function __invoke(int $matchId): int
    {
        $match = Matches::query()
            ->select(['id', 'environment_id', 'group_id', 'result', 'fight_number'])
            ->find($matchId);

        throw_if($match->isInvalidResultForPayout(), MatchResultInvalid::class);

        $tickets = $this->getTickets($match);

        foreach ($tickets as $ticket) {
            DepositMissingPayoutJop::dispatch(
                $match->toArray(),
                $ticket->toArray(),
                $match->isWinResult()
            );
        }

        return 0;
    }


    protected function getTickets(Matches $match): Collection
    {
        return $match
            ->betRecords()
            ->selectRaw('
            GROUP_CONCAT(id) as ids,
            user_id as memberId,
            SUM(amount) as amount,
            SUM(payout) as payout
            ')
            ->when($match->isWinResult(), fn($query) => $query->where('bet_on', $match->result->value))
            ->where('status', BetStatus::ACCEPTED)
            ->groupBy('user_id')
            ->get();
    }


}
