<?php

namespace App\Kravanh\Domain\DragonTiger\Models;

use App\Kravanh\Domain\User\Models\Member;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DragonTigerPayoutDeposited extends Model
{
    protected $table = 'dragon_tiger_payout_deposited';
    protected $guarded = [];

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'member_id', 'id');
    }

}
