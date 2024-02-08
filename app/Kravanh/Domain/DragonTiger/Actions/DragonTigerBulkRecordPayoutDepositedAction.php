<?php

namespace App\Kravanh\Domain\DragonTiger\Actions;

use App\Kravanh\Domain\DragonTiger\Dto\DragonTigerGameTransactionTicketPayoutMetaData;
use App\Kravanh\Domain\DragonTiger\Models\DragonTigerPayoutDeposited;
use Bavix\Wallet\Models\Transaction;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Date;

final class DragonTigerBulkRecordPayoutDepositedAction
{
    public function __invoke(Collection $transactions): bool
    {
        return DragonTigerPayoutDeposited::insert(
            values: $this->fromTransactions($transactions)
        );

    }

    private function fromTransactions(Collection $transactions): array
    {

        return $transactions->map(function ($transaction) {
            /**
             * @var Transaction $transaction
             */
            $meta = DragonTigerGameTransactionTicketPayoutMetaData::fromMeta($transaction->meta);

            return [
                'dragon_tiger_game_id' => $meta->gameId,
                'member_id' => $transaction->payable_id,
                'transaction_id' => $transaction->id,
                'amount' => $transaction->amount,
                'ticket_ids' => $meta->ticketIds,
                'created_at' => Date::now(),
                'updated_at' => Date::now()
            ];

        })->all();
    }


}
