<?php

namespace App\Kravanh\Support\Middleware;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class EnsureGroupIdAlwaysPresent
{
    /**
     * @throws \Throwable
     */
    public function handle(Request $request, \Closure $next)
    {
        throw_if(
            !$request->header('groupId'),
            BadRequestException::class,
            'The Group Id not present'
        );

        $request->user()->group_id = $request->header('groupId');

        return $next($request);
    }
}
