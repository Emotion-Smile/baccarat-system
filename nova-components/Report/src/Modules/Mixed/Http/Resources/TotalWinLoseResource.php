<?php

namespace KravanhEco\Report\Modules\Mixed\Http\Resources;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;
use KravanhEco\Report\Modules\DragonTiger\Support\TransformAmount;

class TotalWinLoseResource extends JsonResource
{
    protected User $user;

    public function __construct(mixed $resource, User $user)
    {
        parent::__construct($resource);

        $this->user = $user;
    }

    public function toArray($request)
    {
        $transformAmount = TransformAmount::make($this->user->currency);

        return [
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