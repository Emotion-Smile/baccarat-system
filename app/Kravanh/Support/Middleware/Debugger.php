<?php

namespace App\Kravanh\Support\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class Debugger
{
    public function handle(Request $request, Closure $next)
    {
//        if (App::isLocal()) {
//            return $next($request);
//        }

        info("host: {$request->getHost()}, ip: {$request->ip()}, {$request->header('x-vapor-source-ip')}");
//        info('sessionID: ' . $request->session()->getId());
        return $next($request);
    }
}
