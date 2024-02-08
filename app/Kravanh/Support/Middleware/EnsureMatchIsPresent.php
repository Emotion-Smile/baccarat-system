<?php

namespace App\Kravanh\Support\Middleware;

use App\Kravanh\Domain\Match\Models\Matches;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureMatchIsPresent
{
    public function handle(Request $request, \Closure $next)
    {
        $match = Matches::live($request->user());

        if (!$match) {
            return response()
                ->json([
                    'message' => 'Match not exist'
                ], Response::HTTP_BAD_REQUEST);
        }


        return $next($request);
    }
}
