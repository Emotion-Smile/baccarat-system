<?php

namespace App\Kravanh\Domain\DragonTiger\Actions;

use App\Kravanh\Domain\DragonTiger\Models\DragonTigerGame;
use App\Kravanh\Domain\DragonTiger\Support\Balance;
use App\Kravanh\Domain\Match\Events\AllPayoutDeposited;
use Illuminate\Support\Collection;

final class DragonTigerPayoutProcessingManagerAction
{
    public function __invoke(int $dragonTigerGameId): void
    {

        $game = DragonTigerGame::find($dragonTigerGameId);

        if ($game->isLive()) {
            return;
        }

        $this->processGamePayouts($game);

    }

    private function processGamePayouts(DragonTigerGame $game): void
    {

        if ($game->isCancel()) {

            $transactions = $this->handleCancelPayout(game: $game);
            $this->dispatchPayoutEvent($game, $transactions);

            return;
        }

        $transactions = $this->handleRegularPayout(game: $game);
        $this->dispatchPayoutEvent($game, $transactions);

        if ($game->isTie()) {
            $transactions = $this->handleTiePayout(game: $game);
            $this->dispatchPayoutEvent($game, $transactions);
        }

    }

    private function dispatchPayoutEvent(DragonTigerGame $game, Collection $transactions): void
    {
        AllPayoutDeposited::dispatch(
            1,
            $game->game_table_id,
            $this->makeMemberBalance($transactions)
        );
    }

    private function makeMemberBalance(Collection $transactions): array
    {
        $memberBalance = [];

        foreach ($transactions as $transaction) {
            $memberBalance[$transaction->payable_id] = Balance::format(
                amount: $transaction->meta['current_balance'],
                currency: $transaction->meta['currency']
            );
        }

        return $memberBalance;

    }

    private function handleRegularPayout(DragonTigerGame $game): Collection
    {
        $tickets = app(DragonTigerGameGetWinningTicketsAction::class)(game: $game);

        return $this->processPayout(game: $game, tickets: $tickets);
    }

    private function handleTiePayout(DragonTigerGame $game): Collection
    {
        $tickets = app(DragonTigerGameGetTicketsBetOnDragonAndTigerForTieResultAction::class)(game: $game);

        return $this->processPayout(game: $game, tickets: $tickets);
    }

    private function handleCancelPayout(DragonTigerGame $game): Collection
    {
        $tickets = app(DragonTigerGameGetTicketsForCancelResultAction::class)($game->id);

        return $this->processPayout(game: $game, tickets: $tickets);
    }

    private function processPayout(DragonTigerGame $game, Collection $tickets): Collection
    {
        $transactions = app(DragonTigerGamePayoutAction::class)(
            game: $game,
            tickets: $tickets
        );

        app(DragonTigerBulkRecordPayoutDepositedAction::class)(transactions: $transactions);

        return $transactions;
    }
}
