<?php

namespace App\Kravanh\Domain\User\Supports\Traits;

use App\Kravanh\Domain\User\Supports\Enums\UserType;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Date;

/**
 * @mixin User
 */
trait HasLocalQueryScope
{
    public function scopeTodayBetReport(Builder $query, $matchId = 0): HasMany
    {
        return $this->bets()
            ->with('match', 'group')
            ->when($matchId > 0, fn($query) => $query->where('match_id', '!=', $matchId))
            ->where('bet_date', Date::today()->format('Y-m-d'))
            ->orderByDesc('id');
    }

    public function scopeCurrentBetReport(Builder $query, $matchId): HasMany
    {
        return $this->bets()
            ->where('match_id', $matchId)
            ->orderByDesc('id');
    }

    public function scopeTrader(Builder $query): Builder
    {
        return $query->where('type', UserType::TRADER);
    }

    public function scopeMember(Builder $query): Builder
    {
        return $query->where('type', UserType::MEMBER);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('suspend', 0);
    }

}
