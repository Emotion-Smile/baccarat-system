<?php

namespace App\Kravanh\Domain\User\Actions;

use App\Kravanh\Domain\User\Exceptions\BalanceIsBlocked;
use App\Kravanh\Domain\User\Models\SuperSenior;
use App\Models\User;
use Bavix\Wallet\Models\Transaction;

class CompanyWithdrawFromSuperSeniorAction
{

    /**
     * @throws \Throwable
     * @throws BalanceIsBlocked
     */
    public function __invoke(User $company, SuperSenior $superSenior, string $remark, $amount)
    {

        if ($superSenior->balanceIsBlocked()) {
            throw new BalanceIsBlocked();
        }

        $superSenior->blockBalance();

        try {

            $superSenior->refresh();

            $amount = $superSenior->toKHR($amount);
            $transaction = $superSenior->withdraw($amount);

            $this->recordTransactionMeta(
                $transaction,
                $company,
                $superSenior,
                $remark,
                $amount
            );

        } finally {
            $superSenior->releaseBalance();
        }
    }

    protected function recordTransactionMeta(
        Transaction $transaction,
        User        $company,
        SuperSenior $superSenior,
        string      $remark,
                    $amount
    ): void
    {
        $currentBalance = $superSenior->balanceInt;

        $meta = [
            'type' => 'withdraw',
            'mode' => 'company',
            'withdraw_by' => $company->id,
            'withdrawer' => $company->name,
            'withdraw_from' => $superSenior->name,
            'withdraw_from_id' => $superSenior->id,
            'before_balance' => $currentBalance + $amount,
            'current_balance' => $currentBalance,
            'wallet_balance' => $currentBalance,
            'currency' => $superSenior->currency,
            'remark' => $remark,
            'ip' => request()->header('x-vapor-source-ip') ?? request()->ip()
        ];

        $transaction->meta = $meta;
        $transaction->save();
    }
}
