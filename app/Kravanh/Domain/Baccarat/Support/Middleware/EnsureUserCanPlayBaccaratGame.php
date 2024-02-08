<?php

namespace App\Kravanh\Domain\Baccarat\Support\Middleware;

use Closure;
use Illuminate\Http\Request;

final class EnsureUserCanPlayBaccaratGame
{
    public function handle(Request $request, Closure $next)
    {
        if (! $request->user()->canPlayBaccarat()) {
            return redirect()->route('member');
        }

        return $next($request);
    }
}
