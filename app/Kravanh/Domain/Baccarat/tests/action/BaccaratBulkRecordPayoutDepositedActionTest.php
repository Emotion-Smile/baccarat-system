<?php

use App\Kravanh\Domain\Baccarat\Actions\BaccaratBulkRecordPayoutDepositedAction;
use App\Kravanh\Domain\Baccarat\Actions\BaccaratGameGetWinningTicketsAction;
use App\Kravanh\Domain\Baccarat\Actions\BaccaratGamePayoutAction;
use App\Kravanh\Domain\Baccarat\Dto\BaccaratGameTransactionTicketPayoutMetaData;
use App\Kravanh\Domain\Baccarat\Models\BaccaratPayoutDeposited;
use App\Kravanh\Domain\Baccarat\tests\BaccaratTestHelper;
use Bavix\Wallet\Models\Transaction;

test('validates bulk payout recording in Dragon Tiger game', function () {

    $game = BaccaratTestHelper::createGameIncludeWinAndLoseTickets();

    $tickets = (new BaccaratGameGetWinningTicketsAction())($game);

    $transactions = (new BaccaratGamePayoutAction())(game: $game, tickets: $tickets);

    $isOk = (new BaccaratBulkRecordPayoutDepositedAction())(transactions: $transactions);

    /**
     * @var Transaction $lastTx ;
     */
    $lastTx = $transactions->last();

    /**
     * @var BaccaratPayoutDeposited $lastRecord ;
     */
    $lastRecord = BaccaratPayoutDeposited::orderByDesc('id')->limit(1)->first();
    $meta = BaccaratGameTransactionTicketPayoutMetaData::fromMeta($lastTx->meta);

    expect($isOk)->toBeTrue()
        ->and($transactions->count())->toBe(BaccaratPayoutDeposited::count())
        ->and($lastRecord->member_id)->toBe($lastTx->payable_id)
        ->and($lastRecord->transaction_id)->toBe($lastTx->id)
        ->and($lastRecord->dragon_tiger_game_id)->toBe($meta->gameId)
        ->and($lastRecord->ticket_ids)->toBe($meta->ticketIds)
        ->and((int) $lastRecord->amount)->toBe($meta->amount)
        ->and($lastRecord->rollback_transaction_id)->toBe(0);

});
