<?php

namespace App\Kravanh\Domain\User\Observers;

use Bavix\Wallet\Models\Transaction;

class TransactionObserver
{
    public function saving(Transaction $transaction)
    {
        $transaction->payable_type = 'App\Models\User';
    }
}
