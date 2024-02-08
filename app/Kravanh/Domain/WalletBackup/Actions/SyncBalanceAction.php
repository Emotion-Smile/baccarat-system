<?php

namespace App\Kravanh\Domain\WalletBackup\Actions;

use Bavix\Wallet\Internal\BookkeeperInterface;
use Bavix\Wallet\Models\Wallet;

final class SyncBalanceAction
{
    public function __invoke(int $walletId, int|float|string $amount): void
    {
        $wallet = Wallet::find($walletId);
        app(BookkeeperInterface::class)->sync($wallet, $amount);
    }
}
