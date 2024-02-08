<?php

namespace App\Kravanh\Domain\BetCondition\Models;

use Illuminate\Database\Eloquent\Model;

class BetCondition extends Model
{

    protected $table = 'bet_conditions';
    protected $guarded = [];
    public $timestamps = false;

    protected $casts = [
        'condition' => 'object'
    ];

    /**
     * @param int $groupId
     * @param int $userId
     * @return object{minBetPerTicket: int,maxBetPerTicket: int,matchLimit: int, winLimitPerDay: int, force: bool}|null
     */
    public static function getCondition(int $groupId, int $userId): object|null
    {
        return static::where('group_id', $groupId)
            ->where('user_id', $userId)
            ->value('condition');
    }

}
