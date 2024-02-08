<?php

namespace App\Kravanh\Domain\Integration\Supports\Traits\T88;

use App\Kravanh\Domain\Integration\Models\T88GameCondition;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasGameCondition
{
    public function isAllowedT88Game(): bool
    {
        return $this->allow_t88_game;
    }

    public function t88GameConditions(): HasMany
    {
        return $this->hasMany(T88GameCondition::class, 'user_id'); 
    }

    public function hasT88GameCondition(string $gameType): bool
    {
        return $this->t88GameConditions()
            ->whereGameType($gameType)
            ->count();
    }

    public function notHasT88GameCondition(string $gameType): bool
    {
        return ! $this->hasT88GameCondition($gameType);
    }

    public function t88GameCondition(string $gameType): ?T88GameCondition
    {
        return $this->t88GameConditions()
            ->whereGameType($gameType)
            ->first();
    }

    public function hasUpLineAllowedT88Game(string $gameType): bool
    {
        $superSenior = $this->underSuperSenior;

        return $superSenior->isAllowedT88Game() && $superSenior->hasT88GameCondition($gameType);
    }
} 