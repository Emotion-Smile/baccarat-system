<?php

namespace KravanhEco\Report\Modules\DragonTiger\Support\Helpers;

use App\Kravanh\Support\Enums\Currency;
use BenSampo\Enum\Exceptions\InvalidEnumKeyException;
use InvalidArgumentException;

/**
 * Prefix function
 * from:    take the value into class
 * to:      is mean exchange.
 *          Ex MoneyHelper::fromKHR($amount)->to($currency)
 *          it's mean exchange KHR amount to another currency
 * format: is just format the number
 */
class MoneyHelper
{
    public function __construct(
        private string|int|float $amount,
        private ?Currency $currency = null
    ) {
    }

    public static function fromKHR(string|int|float|null $amount): static
    {
        return new static($amount ?? 0, Currency::fromKey('KHR'));
    }

    public static function fromAmount(string|int|float $amount, Currency|string $currency = null): static
    {
        if (is_string($currency)) {
            $currency = Currency::fromKey($currency);
        }

        return new static($amount, $currency);
    }

    public function toUSD(): float|int|string
    {
        return $this->to(Currency::USD, false, false);
    }

    public function to(
        string|Currency $currency,
        bool $format = true,
        bool $showPrefix = true
    ): float|int|string {
        if ($this->currency->isNot(Currency::KHR)) {
            throw new InvalidArgumentException('Please provide KHR currency');
        }

        $exchangeAmount = $this->fromKHRAmountToAnotherCurrency($this->amount, $currency);

        if (! $format) {
            return $exchangeAmount;
        }

        return $this->format($exchangeAmount, $currency, $showPrefix);
    }

    /**
     * @throws InvalidEnumKeyException
     */
    public function toKHR(
        bool $format = false,
        bool $showPrefix = true): float|int|string
    {
        $currency = $this->currency ?? Currency::fromKey('KHR');

        $exchangeAmount = $this->toKHRAmount($this->amount, $currency);

        if (! $format) {
            return $exchangeAmount;
        }

        return $this->format($exchangeAmount, $currency, $showPrefix);

    }

    public function formatAsCurrency(
        string|Currency $currency,
        bool $showPrefix = true
    ): string {
        return $this->format($this->amount, $currency, $showPrefix);
    }

    protected function toKHRAmount($amount, Currency $fromCurrency): float|int
    {
        return $amount * Currency::getDescription($fromCurrency->key);
    }

    protected function fromKHRAmountToAnotherCurrency($amount, string|Currency $toCurrency): float|int
    {
        $currency = $toCurrency;

        if ($toCurrency instanceof Currency) {
            $currency = $toCurrency->key;
        }

        return $amount / Currency::getDescription($currency);
    }

    /**
     * @throws InvalidEnumKeyException
     */
    public function formatAsKHR(): string
    {
        return $this->format($this->amount, Currency::fromKey('KHR'));
    }

    public function format($amount, string|Currency $currency, $showPrefix = true): string
    {

        if (is_string($currency)) {
            $currency = Currency::fromKey($currency);
        }

        $prefix = $currency->getSymbol();
        $decimal = $currency->getDecimal();

        if (! $showPrefix) {
            $prefix = '';
        }

        return $prefix.' '.number_format($amount, $decimal);
    }

    protected function decimal(Currency $currency): int
    {
        return match ($currency->value) {
            Currency::USD => 2,
            default => 0
        };
    }
}
