<?php

namespace App\Kravanh\Domain\User\Models;

use App\Kravanh\Domain\User\Observers\RoleObserver;
use App\Models\User;
use Database\Factories\RoleFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Laravel\Nova\Actions\Actionable;

class Role extends Model
{

    use HasFactory, Actionable;

    protected static function booted()
    {
        static::observe(RoleObserver::class);
    }

    public function permissions(): BelongsToMany
    {
        return $this
            ->belongsToMany(Permission::class)
            ->using(PermissionRole::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    protected static function newFactory(): RoleFactory
    {
        return RoleFactory::new();
    }
}

