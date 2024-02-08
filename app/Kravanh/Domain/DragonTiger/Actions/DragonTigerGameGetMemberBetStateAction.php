<?php

namespace App\Kravanh\Domain\DragonTiger\Actions;

use App\Kravanh\Domain\DragonTiger\Models\DragonTigerTicket;
use App\Kravanh\Domain\DragonTiger\Support\Balance;
use App\Kravanh\Domain\DragonTiger\Support\DragonTigerGameWinner;
use App\Kravanh\Domain\DragonTiger\Support\KHRCurrencyAmountToHuman;
use App\Kravanh\Support\Enums\Currency;
use Illuminate\Support\Collection;

final class DragonTigerGameGetMemberBetStateAction
{
    public function __invoke(int $userId, string|Currency $currency, int $dragonTigerGameId): array
    {
        return $this->format(
            betState: $this->getBetState(
                userId: $userId,
                dragonTigerGameId: $dragonTigerGameId
            ),
            currency: Balance::ensureCurrency($currency)
        );

    }

    private function format(Collection $betState, Currency $currency): array
    {
        $finalBetState = [];
        foreach ($betState as $betOn => $amount) {
            $balance = Balance::toCurrency($amount, $currency);

            $finalBetState[$this->makeBetOnKey($betOn)] = [
                'label' => $this->formatBalance(
                    amount: $balance,
                    currency: $currency
                ),
                'amount' => $balance,
                'currency' => $currency->key,
            ];

        }

        return $finalBetState;
    }

    private function formatBalance(mixed $amount, Currency $currency): string
    {
        if ($currency->is(Currency::KHR)) {
            return KHRCurrencyAmountToHuman::fromAmount($amount);
        }

        return currencySymbol(Balance::ensureCurrency($currency)).number_format($amount, is_float($amount) ? 2 : 0);

    }

    private function getBetState(int $userId, int $dragonTigerGameId): Collection
    {
        return DragonTigerTicket::query()
            ->select('amount')
            ->selectRaw("CONCAT(bet_on,'_',bet_type) as betOn")
            ->where('dragon_tiger_game_id', $dragonTigerGameId)
            ->where('user_id', $userId)
            ->get()
            ->groupBy('betOn')
            ->map(fn (Collection $tickets) => $tickets->sum('amount'));
    }

    private function makeBetOnKey(string $betOn): string
    {
        return match ($betOn) {
            'tie_tie' => DragonTigerGameWinner::Tie,
            'dragon_dragon' => DragonTigerGameWinner::Dragon,
            'tiger_tiger' => DragonTigerGameWinner::Tiger,
            default => $betOn
        };
    }
}
