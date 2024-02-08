<?php

use App\Kravanh\Domain\DragonTiger\Actions\DragonTigerBulkRecordPayoutDepositedAction;
use App\Kravanh\Domain\DragonTiger\Actions\DragonTigerGameGetWinningTicketsAction;
use App\Kravanh\Domain\DragonTiger\Actions\DragonTigerGamePayoutAction;
use App\Kravanh\Domain\DragonTiger\Dto\DragonTigerGameTransactionTicketPayoutMetaData;
use App\Kravanh\Domain\DragonTiger\Models\DragonTigerPayoutDeposited;
use App\Kravanh\Domain\DragonTiger\tests\DragonTigerTestHelper;
use Bavix\Wallet\Models\Transaction;

test('validates bulk payout recording in Dragon Tiger game', function () {

    $game = DragonTigerTestHelper::createGameIncludeWinAndLoseTickets();

    $tickets = (new DragonTigerGameGetWinningTicketsAction())($game);

    $transactions = (new DragonTigerGamePayoutAction())(game: $game, tickets: $tickets);

    $isOk = (new DragonTigerBulkRecordPayoutDepositedAction())(transactions: $transactions);

    /**
     * @var Transaction $lastTx ;
     */
    $lastTx = $transactions->last();

    /**
     * @var DragonTigerPayoutDeposited $lastRecord ;
     */
    $lastRecord = DragonTigerPayoutDeposited::orderByDesc('id')->limit(1)->first();
    $meta = DragonTigerGameTransactionTicketPayoutMetaData::fromMeta($lastTx->meta);

    expect($isOk)->toBeTrue()
        ->and($transactions->count())->toBe(DragonTigerPayoutDeposited::count())
        ->and($lastRecord->member_id)->toBe($lastTx->payable_id)
        ->and($lastRecord->transaction_id)->toBe($lastTx->id)
        ->and($lastRecord->dragon_tiger_game_id)->toBe($meta->gameId)
        ->and($lastRecord->ticket_ids)->toBe($meta->ticketIds)
        ->and((int) $lastRecord->amount)->toBe($meta->amount)
        ->and($lastRecord->rollback_transaction_id)->toBe(0);

});
