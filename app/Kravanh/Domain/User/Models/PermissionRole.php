<?php

namespace App\Kravanh\Domain\User\Models;

use App\Kravanh\Domain\User\Observers\PermissionRoleObserver;
use Illuminate\Database\Eloquent\Relations\Pivot;

class PermissionRole extends Pivot
{
    protected static function booted()
    {
        static::observe(PermissionRoleObserver::class);
    }
}
