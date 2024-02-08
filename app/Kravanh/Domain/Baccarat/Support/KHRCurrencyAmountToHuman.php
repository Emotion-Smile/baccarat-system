<?php

namespace App\Kravanh\Domain\Baccarat\Support;

use Illuminate\Support\Str;

final class KHRCurrencyAmountToHuman
{
    public static function fromAmount(string $amount): string
    {
        return (new KHRCurrencyAmountToHuman())($amount);
    }

    public function __invoke(string $amount): string
    {
        return $this->getPostfixNumberFromAmount($amount).' '.$this->getLabelPrefixFromAmount($amount);
    }

    private function getLabelPrefixFromAmount(string $amount): string
    {

        if (strlen($amount) >= 7) {
            return 'លាន';
        }

        return match (strlen($amount)) {
            4 => 'ពាន់',
            5, 6 => 'ម៉ឺន',
            default => ''
        };
    }

    private function getPostfixNumberFromAmount(string $amount): string
    {
        if (strlen($amount) >= 7) {
            return Str::substr($amount, 0, strlen($amount) - 6);
        }

        return match (strlen($amount)) {
            4, 5 => Str::substr($amount, 0, 1),
            6 => Str::substr($amount, 0, 2),
            default => ''
        };
    }
}
