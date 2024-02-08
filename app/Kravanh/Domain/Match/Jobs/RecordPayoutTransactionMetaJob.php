<?php

namespace App\Kravanh\Domain\Match\Jobs;

use App\Kravanh\Domain\Match\Actions\PayoutDepositedCreateAction;
use Bavix\Wallet\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;

class RecordPayoutTransactionMetaJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    public function __construct(
        public int   $depositId,
        public array $meta,
        public int   $memberId
    )
    {
    }

    /**
     * @throws \Exception
     */
    public function handle(): void
    {
        Transaction::query()
            ->where('id', $this->depositId)
            ->update([
                'meta' => $this->meta
            ]);

        (new PayoutDepositedCreateAction())(
            matchId: $this->meta['match_id'],
            memberId: $this->memberId,
            transactionId: $this->depositId,
            depositor: 'deposit',
        );

    }
}
