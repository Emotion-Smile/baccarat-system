<?php

namespace KravanhEco\Report\Modules\Mixed\DataTransferObjects;

use Illuminate\Support\Collection;

class WinLoseItemGameData
{
    public readonly string $site;
    public readonly string $gameType;
    public readonly string $routeName;

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
    

    public function __construct($item)
    {
        $this->site = $item->site;
        $this->gameType = $item->gameType;
        $this->routeName = $item->routeName;

        $this->betAmount = $item->betAmount;
        $this->validAmount = $item->validAmount;

        $this->memberWinLose = $item->memberWinLose;
        $this->memberCommission = $item->memberCommission;
        $this->memberWinLosePlusCommission = $item->memberWinLosePlusCommission;

        $this->currentWinLose = $item->currentWinLose;
        $this->currentCommission = $item->currentCommission;
        $this->currentWinLosePlusCommission = $item->currentWinLosePlusCommission;

        $this->upLineWinLose = $item->upLineWinLose;
        $this->upLineCommission = $item->upLineCommission;
        $this->upLineWinLosePlusCommission = $item->upLineWinLosePlusCommission;
    }

    public static function from($item): WinLoseItemGameData
    {
        return new static($item);
    }
}