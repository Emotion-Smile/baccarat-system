<?php

namespace App\Kravanh\Domain\Match\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Spread extends Model
{
    protected $guarded = [];

    protected $casts = [
        'payout_deduction' => 'integer',
        'active' => 'boolean'
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function members(): HasMany
    {
        return $this->hasMany(User::class, 'spread_id', 'id');
    }

    public function agents(): HasMany
    {
        return $this->hasMany(User::class, 'spread_id', 'id');
    }
}
