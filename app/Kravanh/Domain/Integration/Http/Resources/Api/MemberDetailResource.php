<?php

namespace App\Kravanh\Domain\Integration\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;

class MemberDetailResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'name' => $this->name,
            'balance' => $this->balanceInt,
            'currency' => $this->currency
        ];
    }
} 