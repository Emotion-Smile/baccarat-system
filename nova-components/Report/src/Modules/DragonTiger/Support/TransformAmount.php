<?php

namespace KravanhEco\Report\Modules\DragonTiger\Support;

use App\Kravanh\Support\Enums\Currency;
use KravanhEco\Report\Modules\DragonTiger\Support\Helpers\MoneyHelper;

class TransformAmount
{
    private function __construct(
        protected readonly string|Currency $currency
    ) {}

    public static function make(
        string|Currency $currency
    ) 
    {
        if(is_string($currency)) {
            $currency = Currency::fromKey($currency);
        }

        return new static($currency);
    }

    public function __invoke(int|float $amount): array
    {
        return [
            'value' => $amount,
            'text' => MoneyHelper::fromKHR($amount)->to($this->currency)
        ];
    }
}