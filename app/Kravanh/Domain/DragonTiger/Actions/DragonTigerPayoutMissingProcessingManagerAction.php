<?php

namespace App\Kravanh\Domain\DragonTiger\Actions;

use App\Kravanh\Domain\DragonTiger\Models\DragonTigerGame;
use App\Kravanh\Domain\DragonTiger\Support\Balance;
use App\Kravanh\Domain\Match\Events\AllPayoutDeposited;
use Illuminate\Support\Collection;

final class DragonTigerPayoutMissingProcessingManagerAction
{

    public function __invoke(int $dragonTigerGameId): void
    {

        $game = DragonTigerGame::find($dragonTigerGameId);

        if ($game->isLive() || $game->isCancel()) {
            return;
        }

        $ticketIdsAlreadyPayout = app(DragonTigerGameGetDepositedPayoutTicketIdsAction::class)(
            dragonTigerGameId: $dragonTigerGameId
        );

        $this->processGamePayouts(
            game: $game,
            ticketIdsAlreadyPayout: $ticketIdsAlreadyPayout
        );

    }

    private function processGamePayouts(DragonTigerGame $game, array $ticketIdsAlreadyPayout): void
    {
        $transactions = $this->handleRegularPayout(game: $game, ticketIdsAlreadyPayout: $ticketIdsAlreadyPayout);
        $this->dispatchPayoutEvent($game, $transactions);

        if ($game->isTie()) {
            $transactions = $this->handleTiePayout(game: $game, ticketIdsAlreadyPayout: $ticketIdsAlreadyPayout);
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

    private function handleRegularPayout(DragonTigerGame $game, array $ticketIdsAlreadyPayout): Collection
    {
        $tickets = app(DragonTigerGameGetWinningTicketsAction::class)(game: $game);

        return $this->processPayout(
            game: $game,
            tickets: $this->findMissingTickets($tickets, $ticketIdsAlreadyPayout)
        );

    }

    private function handleTiePayout(DragonTigerGame $game, array $ticketIdsAlreadyPayout): Collection
    {
        $tickets = app(DragonTigerGameGetTicketsBetOnDragonAndTigerForTieResultAction::class)(game: $game);

        return $this->processPayout(
            game: $game,
            tickets: $this->findMissingTickets($tickets, $ticketIdsAlreadyPayout)
        );

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

    private function findMissingTickets(Collection $tickets, array $ticketIdsAlreadyPayout): Collection
    {
        return $tickets->filter(fn($ticket) => !in_array($ticket->ids, $ticketIdsAlreadyPayout))->values();
    }

}
