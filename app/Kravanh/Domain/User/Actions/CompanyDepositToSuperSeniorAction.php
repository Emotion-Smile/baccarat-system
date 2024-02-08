<?php

namespace App\Kravanh\Domain\User\Actions;

use App\Kravanh\Domain\User\Exceptions\BalanceIsBlocked;
use App\Kravanh\Domain\User\Models\SuperSenior;
use App\Models\User;
use Bavix\Wallet\Models\Transaction;
use Exception;
use Illuminate\Support\Facades\Log;

class CompanyDepositToSuperSeniorAction
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

            $amount = $superSenior->toKHR($amount);
            $transaction = $superSenior->deposit($amount);

            $this->recordTransactionMeta(
                $transaction,
                $company,
                $superSenior,
                $remark,
                $amount
            );

        } catch (Exception $exception) {
            Log::error($exception);
            throw $exception;
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
            'type' => 'company_deposit',
            'deposit_by' => $company->id,
            'depositor' => $company->name,
            'receiver' => $superSenior->name,
            'receiver_id' => $superSenior->id,
            'before_balance' => $currentBalance - $amount,
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
