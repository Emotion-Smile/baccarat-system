<?php

namespace KravanhEco\Report\Modules\Cockfight\DataTransferObjects;

use App\Kravanh\Domain\User\Supports\Enums\UserType;

class WinLoseItemData
{
    public readonly int $accountId;
    public readonly string $account;
    public readonly string $contact;
    public readonly string $currency;
    public readonly string $userType;

    public readonly float $betAmount;
    public readonly float $validAmount;

    public readonly float $memberWinLose;
    public readonly float $memberCommission;
    public readonly float $memberWinLosePlusCommission;

    public readonly float $currentWinLose;
    public readonly float $currentCommission;
    public readonly float $currentWinLosePlusCommission;

    public readonly float $upLineWinLose;
    public readonly float $upLineCommission;
    public readonly float $upLineWinLosePlusCommission;

    public readonly string $site;
    public readonly string $gameType;
    public readonly string $routeName;

    public function __construct($item)
    {
        $this->accountId = $item->id;
        $this->account = $item->name ?? '';
        $this->contact = $item->phone ?? '';
        $this->currency = $item->currency ?? '';
        $this->userType = $item->userType ?? '';

        $this->betAmount = $item->bet_amount;
        $this->validAmount = $item->bet_amount;
        
        $this->memberWinLose = $item->win_lose;
        $this->memberCommission = 0;
        $this->memberWinLosePlusCommission = $this->memberWinLose + $this->memberCommission;

        $this->currentWinLose = $item->userType === UserType::SUPER_SENIOR ? $item->win_lose * -1 : 0;
        $this->currentCommission = 0;
        $this->currentWinLosePlusCommission = $this->currentWinLose + $this->currentCommission;

        $this->upLineWinLose = $item->win_lose * -1;
        $this->upLineCommission = 0;
        $this->upLineWinLosePlusCommission = $this->upLineWinLose + $this->upLineCommission;

        $this->site = 'F88';
        $this->gameType = 'Cockfight';
        $this->routeName = 'report-win-lose-detail';
    }

    public static function from($item): WinLoseItemData
    {
        return new static($item);
    }
}