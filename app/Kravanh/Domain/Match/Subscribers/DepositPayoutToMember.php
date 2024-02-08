<?php

namespace App\Kravanh\Domain\Match\Subscribers;

use App\Kravanh\Domain\Match\Events\AllPayoutDeposited;
use App\Kravanh\Domain\Match\Events\MatchEnded;
use App\Kravanh\Domain\Match\Events\MatchResultUpdated;
use App\Kravanh\Domain\Match\Jobs\MatchPayoutNotificationJob;
use App\Kravanh\Domain\Match\Jobs\RecordPayoutTransactionMetaJob;
use App\Kravanh\Domain\Match\Models\Matches;
use App\Kravanh\Domain\Match\Supports\Enums\BetStatus;
use App\Kravanh\Domain\Match\Supports\Enums\MatchResult;
use App\Kravanh\Domain\User\Models\Member;
use App\Kravanh\Support\LockHelper;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Log;

class DepositPayoutToMember
{
    /**
     * @throws \Exception
     */
    public function handle(MatchEnded|MatchResultUpdated $event): void
    {

        //(new PayoutCalculatorAction())($event->match['id']);
        //(new PayoutActionV1())(($event->match['id']));

        $startTime = Date::now();
        $match = $this->getMatch($event->match['id']);

        $depositPayload = [];

        if (in_array($match->result->value, [MatchResult::DRAW, MatchResult::CANCEL])) {
            $depositPayload = $this->deposit(match: $match);
        }

        if (in_array($match->result->value, [MatchResult::WALA, MatchResult::MERON])) {
            $depositPayload = $this->deposit(match: $match, isWin: true);
        }

        $depositPayload['duration'] = Date::now()->sub($startTime)->diffForHumans();
        $depositPayload['envId'] = $match->environment_id;

        MatchPayoutNotificationJob::dispatch($depositPayload);
    }

    protected function getMatch($matchId)
    {
        return Matches::query()
            ->select(['id', 'environment_id', 'group_id', 'result', 'fight_number'])
            ->find($matchId);
    }

    protected function deposit(Matches $match, $isWin = false)
    {
        $fields = ['id', 'user_id', 'payout', 'amount', 'currency'];

        $bets = $match
            ->betRecords()
            ->select($fields)
            ->when($isWin, fn ($query) => $query->where('bet_on', $match->result->value))
            ->where('status', BetStatus::ACCEPTED)
            ->get();

        $totalTicket = $bets->count();

        $betByUser = $bets->groupBy('user_id');

        $depositMessage = [];
        $allMemberBalances = [];

        foreach ($betByUser as $userId => $bets) {

            /** @var Member $member * */
            $member = Member::select(['id', 'name', 'currency'])->find($userId);

            $currency = $member->currency;

            $lock = LockHelper::lockWallet($member->id);
            try {

                $lock->block(config('balance.waiting_time_in_sec'));

                $totalBetAmount = $bets->sum('amount');
                $betId = $bets->pluck('id')->implode(',');

                $depositAmount = $totalBetAmount;
                $payoutAmount = 0;

                if ($isWin) {
                    $depositAmount += $bets->sum('payout');
                    $payoutAmount = $bets->sum('payout');
                }

                $deposit = $member->deposit($depositAmount);

                $currentBalance = $member->balanceInt;

                $beforeBalance = $currentBalance - $depositAmount;

                $depositMeta = [
                    'type' => 'payout',
                    'bet_id' => $betId,
                    'match_id' => $match->id,
                    'before_balance' => $beforeBalance,
                    'current_balance' => $currentBalance,
                    'match_status' => $match->result->description,
                    'amount' => $depositAmount,
                    'fight_number' => $match->fight_number,
                    'currency' => $currency->value,
                    'action' => 'DepositPayoutToMember',
                ];

                RecordPayoutTransactionMetaJob::dispatch(
                    $deposit->id,
                    $depositMeta,
                    $userId
                );

                $balance = priceFormat(fromKHRtoCurrency($currentBalance, $currency), $currency);

                $allMemberBalances[$userId] = $balance;

                $member->todayBetPayoutAmountIncrement($payoutAmount);

                $depositMessage[] = "$member->name: bet: $betId amount: $depositAmount";

            } finally {
                $lock->release();
            }
        }

        AllPayoutDeposited::dispatch(
            $match->environment_id,
            $match->group_id,
            $allMemberBalances
        );

        Log::info(
            'Payout Summary'
            ."\nMatchId: ".$match->id
            ."\nFight Number: ".$match->fight_number
            ."\nGroup: ".$match->group_id
            ."\nTotal ticket: ".$totalTicket
            ."\nTotal user: ".$betByUser->count()
            ."\nresult: ".$match->result->description,
            $depositMessage
        );

        return [
            'matchId' => $match->id,
            'groupId' => $match->group_id,
            'fightNumber' => $match->fight_number,
            'result' => $match->result->description,
            'totalUser' => $betByUser->count(),
            'totalPayoutTickets' => $totalTicket,
            'duration' => '',
        ];
    }
}
