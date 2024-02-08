<?php

namespace KravanhEco\Report\Modules\DragonTiger\DataTransferObjects;

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
        $this->accountId = $item->accountId;
        $this->account = $item->account ?? '';
        $this->contact = $item->phone ?? '';
        $this->currency = $item->currency ?? '';
        $this->userType = $item->userType ?? '';

        $this->betAmount = $item->betAmount;
        $this->validAmount = $item->validAmount;
        
        $this->memberWinLose = $item->memberWinLose;
        $this->memberCommission = $item->memberCommission;
        $this->memberWinLosePlusCommission = $item->memberWinLose + $item->memberCommission;

        $this->currentWinLose = $item->currentWinLose;
        $this->currentCommission = $item->currentCommission;
        $this->currentWinLosePlusCommission = $item->currentWinLose + $item->currentCommission;

        $this->upLineWinLose = $item->upLineWinLose;
        $this->upLineCommission = $item->upLineCommission;
        $this->upLineWinLosePlusCommission = $item->upLineWinLose + $item->upLineCommission;

        $this->site = 'F88';
        $this->gameType = 'Dragon Tiger';
        $this->routeName = 'report-dragon-tiger-bet-detail';
    }

    public static function from($item): WinLoseItemData
    {
        return new static($item);
    }
}