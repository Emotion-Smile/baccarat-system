<?php

namespace App\Kravanh\Domain\DragonTiger;

use Closure;

final class DragonTigerSetting
{
    public static function allow(Closure|bool $allow = true): bool
    {
        if (request()->user()->canPlayDragonTiger()) {
            return true;
        }

        if ($allow instanceof Closure) {
            $allow = $allow();
        }

        return $allow;
    }
}
