<?php

namespace App\Kravanh\Support\Enums;

use BenSampo\Enum\Enum;

class Currency extends Enum
{
    const KHR = 'KHR';
    const USD = 'USD';
    const THB = 'THB';
    const VND = 'VND';

    public static function getDescription($value): string
    {
        return match ($value) {
            self::KHR => 1,
            self::USD => 4000,
            self::THB => 134,
            self::VND => 0.18
        };
    }

    public function symbol(): string
    {
        return match ($this->value) {
            Currency::USD => '$',
            Currency::THB => '฿',
            Currency::VND => '₫',
            default => '៛'
        };
    }

    public function getSymbol(): string
    {
        return match ($this->key) {
            self::KHR => '៛',
            self::USD => '$',
            self::THB => '฿',
            self::VND => '₫'
        };
    }

    public function getSymbolAsWord(): string
    {
        return $this->key;
    }

    public function getDecimal(): int
    {
        return match ($this->key) {
            Currency::USD => 2,
            default => 0
        };
    }
}
