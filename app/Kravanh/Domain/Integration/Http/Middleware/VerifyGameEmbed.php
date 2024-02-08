<?php

namespace App\Kravanh\Domain\Integration\Http\Middleware;

use App\Kravanh\Domain\Integration\Supports\Enums\Game;
use Closure;
use Exception;
use Illuminate\Http\Request;

class VerifyGameEmbed
{
    public function handle(Request $request, Closure $next, ...$guards)
    {
        try {
            $user = $request->user(); 
            $game = Game::fromValue($request->game);

            if($game->is(Game::YUKI) && $user->hasT88GameCondition('LOTTO-12') && $user->hasAllowT88Game()) {
                return $next($request);
            }

            if($game->is(Game::SPORT) && $user->hasAF88GameCondition() && $user->hasAllowAF88Game()) {
                return $next($request);
            }

            throw new Exception('This game not allowed.');
        } catch (Exception $e) {
            abort(404);
        }
    }
}