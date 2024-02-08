<?php

namespace App\Kravanh\Domain\Integration\Supports\Traits\AF88;

use App\Kravanh\Domain\Integration\Models\Af88GameCondition;
use Illuminate\Database\Eloquent\Relations\HasOne;

trait HasGameCondition
{
    public function af88GameCondition(): HasOne
    {
        return $this->hasOne(Af88GameCondition::class, 'user_id'); 
    }

    public function hasAF88GameCondition(): bool
    {
        return $this->af88GameCondition()->exists();
    }
} 