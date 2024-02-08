<?php

namespace KravanhEco\Report\Modules\DragonTiger\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use KravanhEco\Report\Modules\DragonTiger\Support\TransformAmount;

class WinLoseItemResource extends JsonResource
{
    public function toArray($request)
    {
        $transformAmount = TransformAmount::make($this->currency);

        return [
            'accountId' => $this->accountId,
            'account' => $this->account,
            'currency' => $this->currency,
            'site' => 'F88',
            'gameType' => 'D&T',
            'betAmount' => $transformAmount($this->betAmount),
            'validAmount' => $transformAmount($this->validAmount),
            'memberProfit' => [
                'winLose' => $transformAmount($this->memberWinLose),
                'com' => $transformAmount($this->memberCommission),
                'winLoseCom' => $transformAmount($this->memberWinLosePlusCommission) 
            ],
            'currentProfit' => [
                'winLose' => $transformAmount($this->currentWinLose),
                'com' => $transformAmount($this->currentCommission),
                'winLoseCom' => $transformAmount($this->currentWinLosePlusCommission)
            ],
            'upLineProfit' => [
                'winLose' => $transformAmount($this->upLineWinLose),
                'com' => $transformAmount($this->upLineCommission),
                'winLoseCom' => $transformAmount($this->upLineWinLosePlusCommission)
            ]
        ];
    }
}