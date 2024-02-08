<?php

namespace App\Kravanh\Domain\Match\Actions;

use App\Kravanh\Domain\Match\Exceptions\MatchResultInvalid;
use App\Kravanh\Domain\Match\Models\Matches;
use App\Kravanh\Domain\Match\Models\PayoutDeposit;
use App\Kravanh\Domain\Match\Supports\Enums\BetStatus;
use Illuminate\Database\Eloquent\Collection;

class DepositMissingPayoutAction
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
        $missingPayoutTickets = $tickets->filter(fn($ticket) => !$this->isMemberTicketHasPayout($ticket->memberId, $matchId));

        if ($missingPayoutTickets->count() === 0) {
            return 0;
        }

        (new PayoutDepositorAction())(
            $missingPayoutTickets->toArray(),
            $this->matchPayload($match),
            $match->isWinResult()
        );

        return $missingPayoutTickets->count();
    }

    protected function isMemberTicketHasPayout($memberId, $matchId): bool
    {

        return PayoutDeposit::query()
            ->where('match_id', $matchId)
            ->where('member_id', $memberId)
            ->count();
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

    protected function matchPayload($match): array
    {
        return [
            'environment_id' => $match->environment_id,
            'group_id' => $match->group_id,
            'match_id' => $match->id,
            'fight_number' => $match->fight_number,
            'result' => $match->result->description
        ];
    }
}
