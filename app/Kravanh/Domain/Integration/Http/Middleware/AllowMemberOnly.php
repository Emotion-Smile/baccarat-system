<?php

namespace App\Kravanh\Domain\Integration\Http\Middleware;

use App\Kravanh\Domain\User\Supports\Enums\UserType;
use Closure;
use Illuminate\Http\Request;

class AllowMemberOnly
{
    public function handle(Request $request, Closure $next, ...$guards)
    {
        return $request->user()->type->isNot(UserType::MEMBER) 
            ? abort(403)
            : $next($request);
    }
}