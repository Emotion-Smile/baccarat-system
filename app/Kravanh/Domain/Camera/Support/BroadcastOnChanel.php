<?php

namespace App\Kravanh\Domain\Camera\Support;

final class BroadcastOnChanel
{
    public static function table(string $gameName, int $tableId): string
    {
        return "camera.{$gameName}.table.{$tableId}";
    }
}
