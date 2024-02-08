<?php

namespace App\Kravanh\Domain\Baccarat\Actions;

use App\Kravanh\Domain\Baccarat\Dto\BaccaratGameTransactionTicketPayoutMetaData;
use App\Kravanh\Domain\Baccarat\Models\BaccaratPayoutDeposited;
use Bavix\Wallet\Models\Transaction;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Date;

final class BaccaratBulkRecordPayoutDepositedAction
{
    public function __invoke(Collection $transactions): bool
    {
        return BaccaratPayoutDeposited::insert(
            values: $this->fromTransactions($transactions)
        );

    }

    private function fromTransactions(Collection $transactions): array
    {

        return $transactions->map(function ($transaction) {
            /**
             * @var Transaction $transaction
             */
            $meta = BaccaratGameTransactionTicketPayoutMetaData::fromMeta($transaction->meta);

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
