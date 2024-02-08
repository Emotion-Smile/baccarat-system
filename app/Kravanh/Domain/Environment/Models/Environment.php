<?php

namespace App\Kravanh\Domain\Environment\Models;

use App\Models\User;
use Database\Factories\EnvironmentFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Nova\Actions\Actionable;

class Environment extends Model
{
    use Actionable, HasFactory;


    protected static function newFactory(): EnvironmentFactory
    {
        return EnvironmentFactory::new();
    }

    public function user(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function hasUser(): bool
    {
        return (bool)$this->user()->count();
    }


}
