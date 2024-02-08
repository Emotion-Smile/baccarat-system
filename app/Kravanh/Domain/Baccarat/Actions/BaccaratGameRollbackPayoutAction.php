<?php

namespace App\Kravanh\Domain\Baccarat\Actions;

use App\Kravanh\Domain\Baccarat\Models\BaccaratPayoutDeposited;
use App\Kravanh\Domain\Game\Supports\GameName;
use App\Kravanh\Domain\User\Models\Member;
use App\Kravanh\Support\LockHelper;
use Exception;
use Flare;
use Illuminate\Support\Collection;

/**
 * @TODO refactor later
 */
final class BaccaratGameRollbackPayoutAction
{
    public function __invoke(
        Collection $payouts,
        string $note = 'rollback payout'
    ): void {

        foreach ($payouts as $payout) {

            /**
             * @var Member $member
             */
            $member = $payout->member;
            $locker = LockHelper::lockWallet($member->id);

            try {
                $locker->block(config('balance.waiting_time_in_sec'));

                $transaction = $member->forceWithdraw(
                    amount: $payout->amount,
                    meta: $this->makeMeta($member, $payout, $note)
                );

                $this->markPayoutAsRollback(
                    payoutId: $payout->id,
                    transactionId: $transaction->id
                );

            } catch (Exception $exception) {
                Flare::report(throwable: $exception);
            } finally {
                $locker->release();
            }
        }
    }

    private function markPayoutAsRollback(
        int $payoutId,
        int $transactionId
    ): void {

        BaccaratPayoutDeposited::query()
            ->where('id', $payoutId)
            ->update(['rollback_transaction_id' => $transactionId]);

    }

    private function makeMeta(Member $member, $payout, string $note): array
    {
        $currentBalance = $member->balanceInt;

        return [
            'game' => GameName::DragonTiger,
            'match_id' => $payout->dragon_tiger_game_id,
            'type' => 'withdraw',
            'mode' => 'company',
            'action' => 'modify_match',
            'note' => $note,
            'before_balance' => $currentBalance,
            'current_balance' => $currentBalance - $payout->amount,
            'currency' => $member->currency,
        ];
    }
}
