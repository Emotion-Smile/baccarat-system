<?php

namespace KravanhEco\Report\Modules\Mixed\Http\Resources;

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
            'contact' => $this->contact,
            'currency' => $this->currency,
            'subTotal' => $transformAmount($this->subTotal),
            'games' => $this->games->map(
                fn($game) => WinLoseItemGameResource::make($game, $this->currency)
            ) 
        ];
    }
}