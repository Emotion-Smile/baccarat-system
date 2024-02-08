<?php

namespace App\Kravanh\Domain\User\Observers;

use Bavix\Wallet\Models\Transfer;

class TransferObserver
{
    public function saving(Transfer $transfer)
    {
        $transfer->to_type = 'App\Models\User';
    }
}
