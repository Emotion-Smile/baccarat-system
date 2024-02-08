<?php

namespace App\Kravanh\Domain\User\Actions;

use App\Kravanh\Domain\Match\Exceptions\TransactionNotAllowed;
use App\Kravanh\Domain\User\Events\RefreshBalance;
use App\Kravanh\Domain\User\Exceptions\BalanceIsBlocked;
use App\Kravanh\Support\LockHelper;
use App\Kravanh\Support\TransactionSetting;
use App\Models\User;
use Bavix\Wallet\Models\Transfer;

class BalanceTransferAction
{

    /**
     * @param User $sender
     * @param User $receiver
     * @param $remark
     * @param $amount
     * @param $mode
     * @return void
     * @throws BalanceIsBlocked
     * @throws TransactionNotAllowed|\Throwable
     */
    public function __invoke($sender, $receiver, $remark, $amount, $mode): void
    {
        //@TODO block all transaction
        if (TransactionSetting::isDisableWithdrawDeposit()) {
            throw TransactionNotAllowed::disableWithdrawDeposit();
        }

        if ($receiver->balanceIsBlocked() || $sender->balanceIsBlocked()) {
            throw new BalanceIsBlocked();
        }

        $blockReceiver = LockHelper::lockWallet($receiver->id);
        $blockSender = LockHelper::lockWallet($sender->id);

        try {

            $receiver->refresh();
            $sender->refresh();

            $blockSender->block(config('balance.waiting_time_in_sec'));
            $blockReceiver->block(config('balance.waiting_time_in_sec'));
            
            $amount = $receiver->toKHR($amount);
            $transfer = $sender->transfer($receiver, $amount);

            $this->recordTransactionDepositMeta(
                $transfer,
                $sender,
                $receiver,
                $remark,
                $amount,
                $mode
            );

            $this->recordTransactionWithdrawMeta(
                $transfer,
                $sender,
                $receiver,
                $remark,
                $amount,
                $mode
            );

        } finally {

            $blockReceiver->release();
            $blockSender->release();

            $balanceRefresher = $sender;

            if ($mode === 'from_downline') {
                $balanceRefresher = $receiver;
            }

            RefreshBalance::dispatch(
                $balanceRefresher->environment_id,
                $balanceRefresher->id,
                priceFormat($balanceRefresher->getCurrentBalance() ?? 0, $balanceRefresher->currency)
            );
        }
    }

    protected function recordTransactionDepositMeta(
        Transfer $transaction,
                 $sender,
                 $receiver,
                 $remark,
                 $amount,
                 $mode
    ): void
    {
        $receiverCurrentBalance = $receiver->balanceInt;

        $metaReceiver = [
            'type' => 'deposit',
            'mode' => $mode,
            'receiver' => $receiver->name,
            'receiver_id' => $receiver->id,
            'sender' => $sender->name,
            'sender_id' => $sender->id,
            'before_balance' => $receiverCurrentBalance - $amount,
            'current_balance' => $receiverCurrentBalance,
            'wallet_balance' => $receiverCurrentBalance,
            'currency' => $receiver->currency,
            'remark' => $remark,
            'ip' => request()->header('x-vapor-source-ip') ?? request()->ip()
        ];

        $transaction->deposit->meta = $metaReceiver;
        $transaction->deposit->save();

    }

    protected function recordTransactionWithdrawMeta(
        Transfer $transaction,
                 $sender,
                 $receiver,
                 $remark,
                 $amount,
                 $mode
    ): void
    {
        $senderCurrentBalance = $sender->balanceInt;

        $metaWithdraw = [
            'type' => 'withdraw',
            'mode' => $mode,
            'receiver' => $receiver->name,
            'receiver_id' => $receiver->id,
            'sender' => $sender->name,
            'sender_id' => $sender->id,
            'before_balance' => $senderCurrentBalance + $amount,
            'current_balance' => $senderCurrentBalance,
            'wallet_balance' => $senderCurrentBalance,
            'currency' => $sender->currency,
            'remark' => $remark,
            'ip' => request()->header('x-vapor-source-ip') ?? request()->ip()
        ];

        $transaction->withdraw->meta = $metaWithdraw;
        $transaction->withdraw->save();
    }
}
