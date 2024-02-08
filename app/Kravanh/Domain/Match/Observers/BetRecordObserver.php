<?php

namespace App\Kravanh\Domain\Match\Observers;

use App\Kravanh\Domain\Match\Events\TraderMatchSummary;
use App\Kravanh\Domain\Match\Events\TraderNewBetCreated;
use App\Kravanh\Domain\Match\Models\BetRecord;
use App\Kravanh\Domain\Match\Models\Matches;
use App\Kravanh\Domain\Match\Supports\Enums\BetOn;
use App\Kravanh\Domain\Match\Supports\Enums\BetStatus;
use App\Kravanh\Support\External\Telegram\Telegram;
use App\Kravanh\Support\LockHelper;
use Bavix\Wallet\Exceptions\AmountInvalid;
use Bavix\Wallet\Exceptions\BalanceIsEmpty;
use Bavix\Wallet\Exceptions\InsufficientFunds;
use Exception;
use Illuminate\Support\Facades\Cache;
use Throwable;

class BetRecordObserver
{
    public bool $afterCommit = true;


    /**
     * @throws Throwable
     */
    public function created(BetRecord $betRecord): void
    {

        if (!$betRecord->status->is(BetStatus::ACCEPTED)) {
            return;
        }

        $balanceDisplay = $this->withdrawMemberBalance($betRecord);

        TraderNewBetCreated::dispatch(
            $betRecord->broadcastToTicketMonitor()
        );

        $betRecord->user->notifyRefreshBalance($balanceDisplay);
        $betRecord->user->notifyRefreshTotalBet($betRecord->match);

        $this->updateMatch($betRecord);
    }

    /**
     * @throws Throwable
     */
    protected function updateMatch(BetRecord $betRecord): void
    {
        //@todo should inform trader only
        $match = $betRecord->match;

        $matchBetInfoKey = $match->getCacheKey(Matches::MATCH_BET_INFO);

        $payload = [
            'totalTicket' => 0,
            'meronTotalBet' => 0,
            'meronTotalPayout' => 0,
            'walaTotalBet' => 0,
            'walaTotalPayout' => 0,
            'totalBetByMemberType' => [],
        ];

        if (Cache::has($matchBetInfoKey)) {
            $payload = Cache::get($matchBetInfoKey);
        }

        $payload['totalTicket'] += 1;

        $totalBetByMemberType = $payload['totalBetByMemberType'];
        $type = $betRecord->member_type_id ?? 'general';

        $memberTypePayload = $totalBetByMemberType[$type] ?? [];

        $total = $totalBetByMemberType[$type]['total'] ?? 0;
        $total += $betRecord->amount;
        $memberTypePayload['total'] = $total;

        if ($betRecord->bet_on->is(BetOn::MERON)) {
            $payload['meronTotalBet'] += $betRecord->amount;
            $payload['meronTotalPayout'] += $betRecord->payout;
            $prevTotalBet = $totalBetByMemberType[$type]["meron"] ?? 0;
            $memberTypePayload['meron'] = $prevTotalBet + $betRecord->amount;
        }

        if ($betRecord->bet_on->is(BetOn::WALA)) {
            $payload['walaTotalBet'] += $betRecord->amount;
            $payload['walaTotalPayout'] += $betRecord->payout;
            $prevTotalBet = $totalBetByMemberType[$type]['wala'] ?? 0;
            $memberTypePayload['wala'] = $prevTotalBet + $betRecord->amount;
        }

        $totalBetByMemberType[$type] = $memberTypePayload;
        $payload['totalBetByMemberType'] = $totalBetByMemberType;

        Cache::put($matchBetInfoKey, $payload, now()->addMinutes(20));

        TraderMatchSummary::dispatch($match->broadcastMatchSummary($type));

        $match->liveRefreshCache();
    }


    /**
     * @throws Exception
     * @throws Throwable
     */
    protected function withdrawMemberBalance(BetRecord $betRecord): string
    {
        $member = $betRecord->user;

        $lock = LockHelper::lockWallet($member->id);

        try {

            // if the time executes take longer than the waiting time it will throw the exception LockTimeoutException.
            $lock->block(config('balance.waiting_time_in_sec'));

            $transaction = $member->withdraw($betRecord->amount);
            $currentBalance = $member->balanceInt;

            $meta = [
                'bet_id' => $betRecord->id,
                'match_id' => $betRecord->match_id,
                'type' => 'bet',
                'before_balance' => ($currentBalance + $betRecord->amount),
                'current_balance' => $currentBalance,
                'fight_number' => $betRecord->fight_number,
                'amount' => $betRecord->amount,
                'bet_on' => $betRecord->bet_on,
                'payout_rate' => $betRecord->payout_rate,
                'payout' => $betRecord->payout,
                'currency' => $betRecord->currency
            ];

            $transaction->meta = $meta;
            $transaction->saveQuietly();

            BetRecord::query()
                ->where('id', $betRecord->id)
                ->update(['transaction_id' => $transaction->id]);

            return priceFormat(
                fromKHRtoCurrency($currentBalance, $member->currency),
                $member->currency
            );

        } catch (
        AmountInvalid|
        BalanceIsEmpty|
        InsufficientFunds|
        Throwable|
        Exception $exception) {

            $message = "Member withdraw failed: $member->name,amount: $betRecord->amount, " . $exception->getMessage();

            $betRecord->delete();

            Telegram::send()
                ->to("-792979466")
                ->text($message);

            throw $exception;
        } finally {
            $lock->release();
        }
    }
}
