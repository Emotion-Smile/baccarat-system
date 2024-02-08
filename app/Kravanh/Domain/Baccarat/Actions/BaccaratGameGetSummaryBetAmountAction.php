<?php

namespace App\Kravanh\Domain\Baccarat\Actions;

use App\Kravanh\Domain\Baccarat\Models\BaccaratTicket;
use KravanhEco\Report\Modules\Baccarat\Support\Helpers\MoneyHelper;

final class BaccaratGameGetSummaryBetAmountAction
{
    public function __invoke(int $dragonTigerGameId): array
    {
        return BaccaratTicket::query()
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
