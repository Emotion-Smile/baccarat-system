<?php

namespace App\Kravanh\Domain\Game\Supports;

enum GameType: string
{
    case Cockfight = 'cockfight';
    case Casino = 'casino';
    case Slot = 'slot';

    public function label(): string
    {
        return GameType::getLabel($this);
    }

    public static function getLabel(self $value): string
    {
        return match ($value) {
            GameType::Casino => 'Casino',
            GameType::Cockfight => 'Cockfight',
            GameType::Slot => 'Slot Game'
        };
    }
}
