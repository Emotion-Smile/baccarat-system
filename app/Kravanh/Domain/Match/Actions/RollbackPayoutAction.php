<?php

namespace App\Kravanh\Domain\Match\Actions;

use App\Kravanh\Domain\Match\Models\PayoutDeposit;
use App\Kravanh\Domain\User\Models\Member;
use App\Kravanh\Support\LockHelper;
use Bavix\Wallet\Models\Transaction;
use Illuminate\Database\Eloquent\Collection;

final class RollbackPayoutAction
{

    public function __invoke(
        int    $matchId,
        string $note = 'rollback transaction'
    ): int {

        $payoutTransactions = $this->getPayoutTransactionForRollback($matchId);
        $payoutTransactions->each(fn ($payoutTransaction) => $this->rollback($matchId, $payoutTransaction, $note));
        return $payoutTransactions->count();
    }

    private function rollback(
        int    $matchId,
        $payoutTransaction,
        string $note = 'rollback transaction'
    ): void {
        /**
         * @var object{transaction_id: int, member_id: int} $payoutTransaction
         */

        $amount = $this->getAmount($payoutTransaction->transaction_id);
        $member = $this->getWalletOwner($payoutTransaction->member_id);

        $lock = LockHelper::lockWallet($member->id);
        try {

            $lock->block(config('balance.waiting_time_in_sec'));
            $transaction = $member->forceWithdraw($amount);
            $this->recordTransactionMeta(
                $transaction,
                $member,
                $amount,
                $matchId,
                $note
            );


            (new PayoutDepositedUpdateDepositorAction())(
                transactionId: $payoutTransaction->transaction_id,
                depositor: 'rollback'
            );
        } finally {
            $lock->release();
        }
    }

    private function getPayoutTransactionForRollback(int $matchId): Collection|array
    {
        return PayoutDeposit::query()
            ->where('match_id', $matchId)
            ->where('depositor', '!=', 'rollback')
            ->get(['transaction_id', 'member_id']);
    }

    private function getAmount(int $transactionId)
    {
        return Transaction::query()
            ->where('id', $transactionId)
            ->value('amount');
    }

    private function getWalletOwner(int $memberId)
    {
        return Member::select(['id', 'name'])->find($memberId);
    }


    private function recordTransactionMeta(
        Transaction $transaction,
        Member      $member,
        int         $amount,
        int         $matchId,
        string      $note
    ): void {
        $currentBalance = $member->balanceInt;

        $meta = [
            'match_id' => $matchId,
            'type' => 'withdraw',
            'mode' => 'company',
            'action' => 'modify_match',
            'note' => $note,
            'before_balance' => $currentBalance + $amount,
            'current_balance' => $currentBalance,
            'currency' => $member->currency
        ];

        $transaction->meta = $meta;
        $transaction->save();
    }
}
