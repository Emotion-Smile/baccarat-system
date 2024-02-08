<?php

namespace App\Kravanh\Domain\Baccarat\Actions;

use App\Kravanh\Domain\Baccarat\Models\BaccaratGame;
use App\Kravanh\Domain\Baccarat\Support\Balance;
use App\Kravanh\Domain\Match\Events\AllPayoutDeposited;
use Illuminate\Support\Collection;

final class BaccaratPayoutProcessingManagerAction
{
    public function __invoke(int $dragonTigerGameId): void
    {

        $game = BaccaratGame::find($dragonTigerGameId);

        if ($game->isLive()) {
            return;
        }

        $this->processGamePayouts($game);

    }

    private function processGamePayouts(BaccaratGame $game): void
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

    private function dispatchPayoutEvent(BaccaratGame $game, Collection $transactions): void
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

    private function handleRegularPayout(BaccaratGame $game): Collection
    {
        $tickets = app(BaccaratGameGetWinningTicketsAction::class)(game: $game);

        return $this->processPayout(game: $game, tickets: $tickets);
    }

    private function handleTiePayout(BaccaratGame $game): Collection
    {
        $tickets = app(BaccaratGameGetTicketsBetOnDragonAndTigerForTieResultAction::class)(game: $game);

        return $this->processPayout(game: $game, tickets: $tickets);
    }

    private function handleCancelPayout(BaccaratGame $game): Collection
    {
        $tickets = app(BaccaratGameGetTicketsForCancelResultAction::class)($game->id);

        return $this->processPayout(game: $game, tickets: $tickets);
    }

    private function processPayout(BaccaratGame $game, Collection $tickets): Collection
    {
        $transactions = app(BaccaratGamePayoutAction::class)(
            game: $game,
            tickets: $tickets
        );

        app(BaccaratBulkRecordPayoutDepositedAction::class)(transactions: $transactions);

        return $transactions;
    }
}
