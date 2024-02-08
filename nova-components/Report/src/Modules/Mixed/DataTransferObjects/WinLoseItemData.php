<?php

namespace KravanhEco\Report\Modules\Mixed\DataTransferObjects;

use Illuminate\Support\Collection;

class WinLoseItemData
{
    public readonly int $accountId;
    public readonly string $account;
    public readonly string $contact;
    public readonly string $currency;
    public readonly string $userType;
    public readonly Collection $games;
    public readonly int|float $subTotal;

    public function __construct(Collection $items)
    {
        $item = $items->first();

        $this->accountId = $item->accountId;
        $this->account = $item->account;
        $this->contact = $item->contact;
        $this->currency = $item->currency;
        $this->userType = $item->userType;
        $this->games = $items->mapInto(WinLoseItemGameData::class);
        $this->subTotal = $this->games->sum(fn($game) => $game->memberWinLosePlusCommission);
    }

    public static function from(Collection $items): WinLoseItemData
    {
        return new static($items);
    }
}