<?php

namespace App\Kravanh\Support\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;

class EnsureLocale
{
    public function handle(Request $request, \Closure $next)
    {
        $locale = Cache::get('lang:' . $request->user()?->id, 'km');

        App::setLocale($locale);
        Carbon::setLocale($locale);

        return $next($request);
    }
}
