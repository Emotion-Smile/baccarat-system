<?php

namespace App\Kravanh\Support\Middleware;

use Illuminate\Http\Request;

class UpdateUserLastActivity
{
    public function handle(Request $request, \Closure $next)
    {

        if (auth()->guest()) {
            return $next($request);
        }

        if ($request->user()->suspend || !$request->user()->online) {
            $request->session()->flush();
        }

        $request->user()->updateLastActivity();

        return $next($request);
    }
}
