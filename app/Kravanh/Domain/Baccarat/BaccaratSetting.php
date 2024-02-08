<?php

namespace App\Kravanh\Domain\Baccarat;

use Closure;

final class BaccaratSetting
{
    public static function allow(Closure|bool $allow = true): bool
    {
        if (request()->user()->canPlayBaccarat()) {
            return true;
        }

        if ($allow instanceof Closure) {
            $allow = $allow();
        }

        return $allow;
    }
}
