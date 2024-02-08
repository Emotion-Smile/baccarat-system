<?php

namespace App\Kravanh\Domain\User\Observers;

use Bavix\Wallet\Models\Wallet;

class WalletsObserver
{
    public function saving(Wallet $wallet)
    {
        $wallet->holder_type = 'App\Models\User';
    }
}
