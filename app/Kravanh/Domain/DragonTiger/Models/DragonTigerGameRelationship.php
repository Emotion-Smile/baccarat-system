<?php

namespace App\Kravanh\Domain\DragonTiger\Models;

use App\Kravanh\Domain\Game\Models\GameTable;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin DragonTigerGame
 */
trait DragonTigerGameRelationship
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
        return $this->hasMany(DragonTigerTicket::class);
    }
}
