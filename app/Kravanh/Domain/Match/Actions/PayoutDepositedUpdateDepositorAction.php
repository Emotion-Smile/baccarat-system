<?php

namespace App\Kravanh\Domain\Match\Actions;


use App\Kravanh\Domain\Match\Models\PayoutDeposit;

class PayoutDepositedUpdateDepositorAction
{
    public function __invoke(
        int    $transactionId,
        string $depositor
    ): void
    {
        PayoutDeposit::where('transaction_id', $transactionId)
            ->update([
                'depositor' => $depositor
            ]);
    }
}
