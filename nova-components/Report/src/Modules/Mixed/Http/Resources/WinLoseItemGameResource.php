<?php

namespace KravanhEco\Report\Modules\Mixed\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use KravanhEco\Report\Modules\DragonTiger\Support\TransformAmount;

class WinLoseItemGameResource extends JsonResource
{
    protected string $currency;

    public function __construct(mixed $resource, string $currency)
    {
        parent::__construct($resource);

        $this->currency = $currency;
    }
    public function toArray($request)
    {
        $transformAmount = TransformAmount::make($this->currency);
    
        return [
            'site' => $this->site,
            'type' => $this->gameType,
            'routeName' => $this->routeName,
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