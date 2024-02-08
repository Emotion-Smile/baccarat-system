<?php

namespace App\Kravanh\Domain\Baccarat\Models;

use App\Kravanh\Domain\Game\Models\GameTable;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin BaccaratGame
 */
trait BaccaratGameRelationship
{
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function submittedResult(): BelongsTo
    {
        return $this->belongsTo(User::class, 'result_submitted_user_id', 'id');
    }

    public function gameTable(): BelongsTo
    {
        return $this->belongsTo(GameTable::class);
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(BaccaratTicket::class);
    }
}
