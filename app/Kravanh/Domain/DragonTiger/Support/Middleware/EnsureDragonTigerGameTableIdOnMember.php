<?php

namespace App\Kravanh\Domain\DragonTiger\Support\Middleware;

use App\Kravanh\Domain\Game\Actions\GameDragonTigerGetAction;
use Closure;
use Illuminate\Http\Request;

final class EnsureDragonTigerGameTableIdOnMember
{
    public function handle(Request $request, Closure $next)
    {

        $user = $request->user();

        if ($user->isDragonTigerGameTable() && $user->isDragonTraderGameTableAvailable()) {

            return $next($request);
        }

        $this->setUpUserForGame($user);

        return $next($request);
    }

    protected function setUpUserForGame(mixed $user): void
    {
        $game = app(GameDragonTigerGetAction::class)();
        $user->group_id = $game->firstTableId();
        $user->two_factor_secret = 'dragon_tiger';
        $user->saveQuietly();
    }
}
