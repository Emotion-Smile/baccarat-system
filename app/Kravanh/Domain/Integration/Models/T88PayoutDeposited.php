<?php

namespace App\Kravanh\Domain\Integration\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class T88PayoutDeposited extends Model
{
    use HasFactory;

    protected $guarded = [];

    public static function isPaid(int $memberId, int $gameId): bool
    {
        return self::whereMemberId($memberId)
            ->whereGameId($gameId)
            ->exists();
    }
}
