<?php

namespace App\Kravanh\Domain\Match\Actions;

use App\Kravanh\Domain\Match\Events\AllPayoutDeposited;
use App\Kravanh\Domain\Match\Models\Matches;
use App\Kravanh\Domain\Match\Supports\Enums\BetStatus;
use App\Kravanh\Domain\Match\Supports\Enums\MatchResult;
use App\Kravanh\Domain\User\Models\Member;
use App\Kravanh\Support\Enums\Currency;
use Illuminate\Support\Facades\Log;

class PayoutActionV1
{
    public function __invoke(int $matchId)
    {
        $match = Matches::query()
            ->select(['id', 'environment_id', 'group_id', 'result'])
            ->find($matchId);

        if (in_array($match->result->value, [MatchResult::DRAW, MatchResult::CANCEL])) {
            $this->deposit(match: $match);
        }

        if (in_array($match->result->value, [MatchResult::WALA, MatchResult::MERON])) {
            $this->deposit(match: $match, isWin: true);
        }
    }


    /**
     * @throws \Exception
     */
    protected function deposit(Matches $match, $isWin = false)
    {
        $fields = ['id', 'user_id', 'payout', 'amount', 'fight_number', 'currency'];

        $bets = $match
            ->betRecords()
            ->select($fields)
            ->when($isWin, fn($query) => $query->where('bet_on', $match->result->value))
            ->where('status', BetStatus::ACCEPTED)
            ->get();


        $totalTicket = $bets->count();

        $betByUser = $bets->groupBy('user_id');

        $depositMessage = [];
        $allMemberBalances = [];

        foreach ($betByUser as $userId => $bets) {

            $member = Member::select(['id', 'name'])->find($userId);

            try {

                $member->blockBalance();

                $totalBetAmount = $bets->sum('amount');
                $betId = $bets->pluck('id')->implode(',');

                $depositAmount = $totalBetAmount;

                if ($isWin) {
                    $depositAmount += $bets->sum('payout');
                }

                $firstBet = $bets->first();

                $deposit = $member->deposit($depositAmount);


                $currentBalance = $member->balanceInt;
                $beforeBalance = $currentBalance - $depositAmount;

                $depositMeta = [
                    'type' => 'payout',
                    'bet_id' => $betId,
                    'match_id' => $match->id,
                    'before_balance' => $beforeBalance,
                    'current_balance' => $currentBalance,
                    'wallet_balance' => $currentBalance,
                    'match_status' => $match->result->description,
                    'amount' => $depositAmount,
                    'fight_number' => $firstBet?->fight_number ?? '',
                    'currency' => $firstBet?->currency ?? Currency::KHR
                ];

                $deposit->meta = $depositMeta;
                $deposit->save();

                $currency = Currency::fromKey($firstBet?->currency ?? Currency::KHR);
                $balance = priceFormat(fromKHRtoCurrency($currentBalance, $currency), $currency);

                $allMemberBalances[$userId] = $balance;

                //PayoutDeposited::dispatch($match->environment_id, $userId, $balance);
                $member->todayBetPayoutAmountIncrement($depositAmount);

                $depositMessage[] = "{$member->name}: bet: {$betId} amount: {$depositAmount}";

            } finally {
                $member->releaseBalance();
            }
        }

        AllPayoutDeposited::dispatch($match->environment_id, $match->group_id, $allMemberBalances);

        Log::info(
            'Payout Summary'
            . "\nTotal ticket: " . $totalTicket
            . "\nTotal user: " . $betByUser->count()
            . "\nWin/Loss: " . $isWin, $depositMessage);
    }
}
