<?php

namespace App\Kravanh\Domain\DragonTiger\Support\Middleware;

use Closure;
use Illuminate\Http\Request;

final class EnsureUserCanPlayDragonTigerGame
{
    public function handle(Request $request, Closure $next)
    {
        if (! $request->user()->canPlayDragonTiger()) {
            return redirect()->route('member');
        }

        return $next($request);
    }
}
