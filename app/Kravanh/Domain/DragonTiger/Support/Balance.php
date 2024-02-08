<?php

namespace App\Kravanh\Domain\DragonTiger\Support;

use App\Kravanh\Support\Enums\Currency;

final class Balance
{

    public static function format(mixed $amount, string|Currency $currency): string
    {
        $currency = self::ensureCurrency($currency);

        return priceFormat(
            fromKHRtoCurrency(
                amount: $amount,
                toCurrency: $currency
            ),
            prefix: $currency
        );
    }

    public static function toCurrency(mixed $amount, Currency|string $currency)
    {
        return fromKHRtoCurrency($amount, self::ensureCurrency($currency));
    }

    public static function ensureCurrency(Currency|string $currency): Currency
    {
        if (!$currency instanceof Currency) {
            return Currency::fromKey($currency);
        }

        return $currency;
    }
}
