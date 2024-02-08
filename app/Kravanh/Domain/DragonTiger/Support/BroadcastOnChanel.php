<?php

namespace App\Kravanh\Domain\DragonTiger\Support;

final class BroadcastOnChanel
{

    const Table = 'dragon_tiger.table.';

    public static function table(int $gameTableId): string
    {
        return self::Table . $gameTableId;
    }

}
