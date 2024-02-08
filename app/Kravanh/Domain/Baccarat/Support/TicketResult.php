<?php

namespace App\Kravanh\Domain\Baccarat\Support;

enum TicketResult
{
    const Pending = 'pending';
    const Win = 'win';
    const Lose = 'lose';
    const LoseHalf = 'lose(Half)';

    public static function color(string $result): string
    {
        return match ($result) {
            self::Pending => '#3c4b5f',
            self::Win => '#4099de',
            self::Lose, self::LoseHalf => '#e74444',
            default => 'gray'
        };
    }
}
