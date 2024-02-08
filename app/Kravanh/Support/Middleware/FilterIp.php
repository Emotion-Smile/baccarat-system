<?php

namespace App\Kravanh\Support\Middleware;

use Closure;
use Illuminate\Http\Request;

class FilterIp
{
    public function handle(Request $request, Closure $next)
    {

//        $position = Location::get($request->header('x-vapor-source-ip') ?? $request->ip());
        return $next($request);
    }
}
