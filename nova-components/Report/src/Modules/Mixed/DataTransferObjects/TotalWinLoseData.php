<?php

namespace KravanhEco\Report\Modules\Mixed\DataTransferObjects;

use Closure;
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
        $this->betAmount = $items->sum($this->sum('betAmount'));
        $this->validAmount = $items->sum($this->sum('validAmount'));
        
        $this->memberWinLose = $items->sum($this->sum('memberWinLose'));
        $this->memberCommission = $items->sum($this->sum('memberCommission'));
        $this->memberWinLosePlusCommission = $this->memberWinLose + $this->memberCommission; 

        $this->currentWinLose = $items->sum($this->sum('currentWinLose'));
        $this->currentCommission = $items->sum($this->sum('currentCommission'));
        $this->currentWinLosePlusCommission = $this->currentWinLose + $this->currentCommission; 

        $this->upLineWinLose = $items->sum($this->sum('upLineWinLose'));
        $this->upLineCommission = $items->sum($this->sum('upLineCommission'));
        $this->upLineWinLosePlusCommission = $this->upLineWinLose + $this->upLineCommission;
    }

    public static function from(Collection $items): TotalWinLoseData
    {
        return new static($items);
    }

    protected function sum($key): Closure
    {
        return fn($item) => $item->games->sum($key);
    }
}