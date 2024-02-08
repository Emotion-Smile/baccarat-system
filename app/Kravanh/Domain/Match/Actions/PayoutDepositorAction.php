<?php

namespace App\Kravanh\Domain\Match\Actions;

use App\Kravanh\Domain\Match\Events\AllPayoutDeposited;
use App\Kravanh\Domain\User\Models\Member;
use App\Kravanh\Support\Enums\Currency;
use App\Kravanh\Support\LockHelper;

class PayoutDepositorAction
{
    /**
     * @throws \Exception
     * @throws \Throwable
     */
    public function __invoke(
        array $payoutRecords,
        array $match,
        bool  $isWin = false): void
    {

        $this->processDeposit(
            $payoutRecords,
            $match,
            $isWin
        );
    }


    /**
     * @throws \Exception|\Throwable
     */
    protected function processDeposit(array $payoutRecords, array $match, bool $isWin = false): void
    {

        $allMemberBalances = [];

        foreach ($payoutRecords as $record) {

            $ticketOwner = $this->getTicketOwner($record['memberId']);

            $lock = LockHelper::lockWallet($record['memberId']);

            try {

                $lock->block(config('balance.waiting_time_in_sec'));

                $depositAmount = $record['amount'];

                if ($isWin) {
                    $depositAmount += $record['payout'];
                }

                $deposit = $ticketOwner->deposit($depositAmount);

                $currentBalance = $this->recordDepositMeta($ticketOwner, $depositAmount, $record, $match, $deposit);
                $allMemberBalances[$record['memberId']] = $currentBalance;

                $ticketOwner->todayBetPayoutAmountIncrement($depositAmount);

            } finally {
                $lock->release();
            }
        }

        AllPayoutDeposited::dispatch(
            $match['environment_id'],
            $match['group_id'],
            $allMemberBalances
        );
    }

    protected function getTicketOwner($memberId): Member
    {
        return Member::select(['id', 'name', 'currency'])->find($memberId);
    }

    /**
     * @throws \BenSampo\Enum\Exceptions\InvalidEnumKeyException
     */
    protected function recordDepositMeta(
        $member,
        $depositAmount,
        $record,
        $match,
        $deposit): string
    {
        $currentBalance = $member->balanceInt;
        $beforeBalance = $currentBalance - $depositAmount;

        $currency = Currency::fromKey($member->currency->value ?? Currency::KHR);

        $depositMeta = [
            'type' => 'payout',
            'bet_id' => $record['ids'],
            'match_id' => $match['match_id'],
            'before_balance' => $beforeBalance,
            'current_balance' => $currentBalance,
            'match_status' => $match['result'],
            'amount' => $depositAmount,
            'fight_number' => $match['fight_number'] ?? '',
            'currency' => $currency->value,
            'action' => 'PayoutDepositorAction'
        ];


        $deposit->meta = $depositMeta;
        $deposit->save();

        (new PayoutDepositedCreateAction())(
            matchId: $match['match_id'],
            memberId: $member->id,
            transactionId: $deposit->id,
            depositor: 'deposit_missing'
        );

        return priceFormat(fromKHRtoCurrency($currentBalance, $currency), $currency);
    }
}
