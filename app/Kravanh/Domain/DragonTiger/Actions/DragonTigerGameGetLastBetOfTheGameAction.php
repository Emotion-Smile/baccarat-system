<?php

namespace App\Kravanh\Domain\DragonTiger\Actions;

use App\Kravanh\Domain\DragonTiger\Models\DragonTigerTicket;
use KravanhEco\Report\Modules\DragonTiger\Support\Helpers\MoneyHelper;

final class DragonTigerGameGetLastBetOfTheGameAction
{
    /**
     * @return array<int,array{amount: int, betOn: string, betTye: string}>
     */
    public function __invoke(int $userId, string $currency): array
    {

        $dragonTigerGameId = $this->getGameIdOfLastBet($userId);

        if (! $dragonTigerGameId) {
            return [];
        }

        return $this->getBets($userId, $dragonTigerGameId, $currency);

    }

    private function getGameIdOfLastBet(int $userId)
    {
        return DragonTigerTicket::query()
            ->where('user_id', $userId)
            ->orderByDesc('id')
            ->value('dragon_tiger_game_id');
    }

    /**
     * @return array<int,array{amount: int, betOn: string, betTye: string}>
     */
    private function getBets(
        int $userId,
        int $dragonTigerGameId,
        string $currency
    ): array {

        return DragonTigerTicket::query()
            ->where('user_id', $userId)
            ->where('dragon_tiger_game_id', $dragonTigerGameId)
            ->get(['amount', 'bet_on', 'bet_type'])
            ->map(fn (DragonTigerTicket $ticket) => [
                'amount' => MoneyHelper::fromKHR($ticket->amount)->to(currency: $currency, format: false),
                'betOn' => $ticket->bet_on,
                'betType' => $ticket->bet_type,
            ])
            ->all();
    }
}
