<?php

namespace KravanhEco\Report\Modules\DragonTiger\DataTransferObjects;

use Illuminate\Support\Collection;

class TotalWinLoseData
{
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

    public function __construct(Collection $items)
    {
        $this->betAmount = $items->sum('betAmount');
        $this->validAmount = $items->sum('validAmount');
        
        $this->memberWinLose = $items->sum('memberWinLose');
        $this->memberCommission = $items->sum('memberCommission');
        $this->memberWinLosePlusCommission = $this->memberWinLose + $this->memberCommission; 

        $this->currentWinLose = $items->sum('currentWinLose');
        $this->currentCommission = $items->sum('currentCommission');
        $this->currentWinLosePlusCommission = $this->currentWinLose + $this->currentCommission; 

        $this->upLineWinLose = $items->sum('upLineWinLose');
        $this->upLineCommission = $items->sum('upLineCommission');
        $this->upLineWinLosePlusCommission = $this->upLineWinLose + $this->upLineCommission;
    }

    public static function from(Collection $items): TotalWinLoseData
    {
        return new static($items);
    }
}