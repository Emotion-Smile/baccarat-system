<?php

namespace App\Kravanh\Domain\Baccarat\Models;

use App\Kravanh\Domain\User\Models\Member;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BaccaratPayoutDeposited extends Model
{
    protected $table = 'baccarat_payout_deposited';
    protected $guarded = [];

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'member_id', 'id');
    }

}
