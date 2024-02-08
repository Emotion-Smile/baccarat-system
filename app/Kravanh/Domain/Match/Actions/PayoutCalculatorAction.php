<?php

namespace App\Kravanh\Domain\Match\Actions;

use App\Kravanh\Domain\Match\Jobs\BetPayoutJob;
use App\Kravanh\Domain\Match\Models\Matches;
use App\Kravanh\Domain\Match\Supports\Enums\BetStatus;
use App\Kravanh\Domain\Match\Supports\Enums\MatchResult;

class PayoutCalculatorAction
{
    /**
     * @throws \Exception
     */
    public function __invoke(int $matchId)
    {
        $match = $this->getMatch($matchId);

        if (in_array($match->result->value, [MatchResult::DRAW, MatchResult::CANCEL])) {
            $records = $this->preparePayoutRecords(match: $match);
            $this->dispatchDeposit($records, $match);
        }

        if (in_array($match->result->value, [MatchResult::WALA, MatchResult::MERON])) {
            $records = $this->preparePayoutRecords(match: $match, isWin: true);
            $this->dispatchDeposit($records, $match, true);
        }

    }

    /**
     * @throws \Exception
     */
    public function preparePayoutRecords(Matches $match, $isWin = false)
    {
        return $this->getPayoutRecords($match, $isWin)->chunk(5)->values();
    }

    public function dispatchDeposit($record, $match, $isWin = false)
    {
        $record->each(function ($record) use ($match, $isWin) {
            BetPayoutJob::dispatch(
                $record->values()->toArray(),
                $this->matchPayload($match),
                $isWin
            );
        });
    }

    protected function getPayoutRecords($match, $isWin)
    {
        return $match
            ->betRecords()
            ->selectRaw('
            GROUP_CONCAT(id) as ids,
            user_id as memberId,
            SUM(amount) as amount,
            SUM(payout) as payout
            ')
            ->when($isWin, fn($query) => $query->where('bet_on', $match->result->value))
            ->where('status', BetStatus::ACCEPTED)
            ->groupBy('user_id')
            ->get();
    }

    protected function getMatch($matchId)
    {
        return Matches::query()
            ->select(['id', 'environment_id', 'group_id', 'result', 'fight_number'])
            ->find($matchId);
    }

    protected function matchPayload($match)
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
