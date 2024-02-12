<?php

namespace App\Kravanh\Domain\Baccarat\Support\Middleware;

use App\Kravanh\Domain\Game\Actions\GameBaccaratGetAction;
use Closure;
use Illuminate\Http\Request;

final class EnsureBaccaratGameTableIdOnMember
{
    public function handle(Request $request, Closure $next)
    {

        $user = $request->user();

        if ($user->isBaccaratGameTable() && $user->isBaccaratTraderGameTableAvailable()) {

            return $next($request);
        }

        $this->setUpUserForGame($user);

        return $next($request);
    }

    protected function setUpUserForGame(mixed $user): void
    {
        $game = app(GameBaccaratGetAction::class)();
        $user->group_id = $game->firstTableId();
        $user->two_factor_secret = 'baccarat';
        $user->saveQuietly();
    }
}
