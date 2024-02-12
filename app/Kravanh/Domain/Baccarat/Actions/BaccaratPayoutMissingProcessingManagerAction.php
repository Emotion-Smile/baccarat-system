<?php

namespace App\Kravanh\Domain\Baccarat\Actions;

use App\Kravanh\Domain\Baccarat\Models\BaccaratGame;
use App\Kravanh\Domain\Baccarat\Support\Balance;
use App\Kravanh\Domain\Match\Events\AllPayoutDeposited;
use Illuminate\Support\Collection;

final class BaccaratPayoutMissingProcessingManagerAction
{

    public function __invoke(int $baccaratGameId): void
    {

        $game = BaccaratGame::find($baccaratGameId);

        if ($game->isLive() || $game->isCancel()) {
            return;
        }

        $ticketIdsAlreadyPayout = app(BaccaratGameGetDepositedPayoutTicketIdsAction::class)(
            dragonTigerGameId: $baccaratGameId
        );

        $this->processGamePayouts(
            game: $game,
            ticketIdsAlreadyPayout: $ticketIdsAlreadyPayout
        );

    }

    private function processGamePayouts(BaccaratGame $game, array $ticketIdsAlreadyPayout): void
    {
        $transactions = $this->handleRegularPayout(game: $game, ticketIdsAlreadyPayout: $ticketIdsAlreadyPayout);
        $this->dispatchPayoutEvent($game, $transactions);

        if ($game->isTie()) {
            $transactions = $this->handleTiePayout(game: $game, ticketIdsAlreadyPayout: $ticketIdsAlreadyPayout);
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

    private function handleRegularPayout(BaccaratGame $game, array $ticketIdsAlreadyPayout): Collection
    {
        $tickets = app(BaccaratGameGetWinningTicketsAction::class)(game: $game);

        return $this->processPayout(
            game: $game,
            tickets: $this->findMissingTickets($tickets, $ticketIdsAlreadyPayout)
        );

    }

    private function handleTiePayout(BaccaratGame $game, array $ticketIdsAlreadyPayout): Collection
    {
        $tickets = app(BaccaratGameGetTicketsBetOnPlayerAndBankerForTieResultAction::class)(game: $game);

        return $this->processPayout(
            game: $game,
            tickets: $this->findMissingTickets($tickets, $ticketIdsAlreadyPayout)
        );

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

    private function findMissingTickets(Collection $tickets, array $ticketIdsAlreadyPayout): Collection
    {
        return $tickets->filter(fn($ticket) => !in_array($ticket->ids, $ticketIdsAlreadyPayout))->values();
    }

}
