<?php

namespace App\Kravanh\Support;

use App\Kravanh\Support\Enums\Currency;

class BetHelper
{
    public static function minimum(string $currency): int
    {
        return match ($currency) {
            Currency::KHR => 5000,
            Currency::USD => 1,
            Currency::VND => 22000,
            Currency::THB => 30,
        };
    }

    public static function betValue(string $currency, int $minimum = 0): array
    {

        $data = [];

        if ($minimum === 0) {
            $minimum = self::minimum($currency);
        }

        $data[Currency::USD] = [
            [
                'key' => 1,
                'value' => '$ 1',
                'coin' => 'chip-1',
            ],
            [
                'key' => 5,
                'value' => '$ 5',
                'coin' => 'chip-2',
            ],
            [
                'key' => 10,
                'value' => '$ 10',
                'coin' => 'chip-3',
            ],
            [
                'key' => 20,
                'value' => '$ 20',
                'coin' => 'chip-4',
            ],
            [
                'key' => 50,
                'value' => '$ 50',
                'coin' => 'chip-5',
            ],
            [
                'key' => 100,
                'value' => '$ 100',
                'coin' => 'chip-6',
            ],
            [
                'key' => 200,
                'value' => '$ 200',
                'coin' => 'chip-7',
            ],
            [
                'key' => 500,
                'value' => '$ 500',
                'coin' => 'chip-8',
            ],
        ];

        $data[Currency::KHR] = [
            [
                'key' => 1000,
                'value' => '១ពាន់',
                'coin' => 'chip-1',
            ],
            [
                'key' => 2000,
                'value' => '២ពាន់',
                'coin' => 'chip-1',
            ],
            [
                'key' => 5000,
                'value' => '៥ពាន់',
                'coin' => 'chip-1',
            ],
            [
                'key' => 10000,
                'value' => '១ម៉ឺន',
                'coin' => 'chip-2',
            ],
            [
                'key' => 20000,
                'value' => '២ម៉ឺន',
                'coin' => 'chip-2',
            ],
            [
                'key' => 40000,
                'value' => '៤ម៉ឺន',
                'coin' => 'chip-3',
            ],
            [
                'key' => 50000,
                'value' => '៥ម៉ឺន',
                'coin' => 'chip-3',
            ],
            [
                'key' => 100000,
                'value' => '១០ម៉ឺន',
                'coin' => 'chip-4',
            ],
            [
                'key' => 200000,
                'value' => '២០ម៉ឺន',
                'coin' => 'chip-5',
            ],
            [
                'key' => 500000,
                'value' => '៥០ម៉ឺន',
                'coin' => 'chip-6',
            ],
            [
                'key' => 1000000,
                'value' => '១លាន',
                'coin' => 'chip-7',
            ],
            [
                'key' => 2000000,
                'value' => '២លាន',
                'coin' => 'chip-8',
            ],
        ];

        $data[Currency::VND] = [
            [
                'key' => 22000,
                'value' => '₫ 22,000',
                'coin' => 'chip-1',
            ],
            [
                'key' => 110000,
                'value' => '₫ 110,000',
                'coin' => 'chip-2',
            ],
            [
                'key' => 220000,
                'value' => '₫ 220,000',
                'coin' => 'chip-3',
            ],
            [
                'key' => 440000,
                'value' => '₫ 440,000',
                'coin' => 'chip-4',
            ],
            [
                'key' => 1100000,
                'value' => '₫ 1.1M',
                'coin' => 'chip-5',
            ],
            [
                'key' => 2200000,
                'value' => '₫ 2.2M',
                'coin' => 'chip-6',
            ],
            [
                'key' => 4400000,
                'value' => '₫ 4.4M',
                'coin' => 'chip-7',
            ],
            [
                'key' => 11000000,
                'value' => '₫ 11M',
                'coin' => 'chip-8',
            ],
        ];

        $data[Currency::THB] = [
            [
                'key' => 30,
                'value' => '฿ 30',
                'coin' => 'chip-1',
            ],
            [
                'key' => 150,
                'value' => '฿ 150',
                'coin' => 'chip-2',
            ],
            [
                'key' => 300,
                'value' => '฿ 300',
                'coin' => 'chip-3',
            ],
            [
                'key' => 600,
                'value' => '฿ 600',
                'coin' => 'chip-4',
            ],
            [
                'key' => 1500,
                'value' => '฿ 1500',
                'coin' => 'chip-5',
            ],
            [
                'key' => 3000,
                'value' => '฿ 3000',
                'coin' => 'chip-6',
            ],
            [
                'key' => 6000,
                'value' => '฿ 6000',
                'coin' => 'chip-7',
            ],
            [
                'key' => 15000,
                'value' => '฿ 15000',
                'coin' => 'chip-8',
            ],
        ];

        if ($minimum === 0) {
            return $data[$currency];
        }

        return collect($data[$currency])
            ->filter(fn ($value) => $value['key'] >= $minimum)
            ->all();

        //$data[$currency];

    }
}
