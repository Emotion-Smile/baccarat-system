<?php

namespace App\Kravanh\Domain\DragonTiger\Actions;

use App\Kravanh\Domain\DragonTiger\Models\DragonTigerTicket;
use KravanhEco\Report\Modules\DragonTiger\Support\Helpers\MoneyHelper;

final class DragonTigerGameGetSummaryBetAmountAction
{
    public function __invoke(int $dragonTigerGameId): array
    {
        return DragonTigerTicket::query()
            ->select(['amount'])
            ->selectRaw("CONCAT(`bet_on`,'_',`bet_type`) as bet")
            ->where('dragon_tiger_game_id', $dragonTigerGameId)
            ->get()
            ->groupBy('bet')
            ->map(fn ($ticket) => [
                'value' => MoneyHelper::fromKHR($ticket->sum('amount'))->toUSD(),
                'label' => MoneyHelper::fromKHR($ticket->sum('amount'))->to('USD'),
            ])
            ->toArray();

    }
}
