<?php

namespace App\Kravanh\Domain\User\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MemberType extends Model
{
    protected $guarded = [];

    protected $casts = [
        'active' => 'boolean'
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'current_team_id', 'id');
    }

    public function members(): HasMany
    {
        return $this->hasMany(User::class, 'current_team_id', 'id');
    }

    public function agents(): HasMany
    {
        return $this->hasMany(User::class, 'current_team_id', 'id');
    }
}
