<?php

namespace App\Kravanh\Application\Admin\User\Supports\Middleware;

use App\Kravanh\Domain\User\Models\Member;
use Closure;
use Illuminate\Http\Request;

class AllowUserType
{
    //@TODO fix later
    public function handle(Request $request, Closure $next, ...$type)
    {
        $user = $request->user();

        if ($this->isAllow($this->makeUserType($user), $type)) {
            return $next($request);
        }

        if ($user->isMember()) {
            return redirect()->route('member');
        }

        if ($user->isTraderDragonTiger()) {
            return redirect()->route('dragon-tiger.trader');
        }

        if ($user->isTraderCockfight()) {
            return redirect()->route('open-bet');
        }

        return redirect()->route('nova.login');

    }

    private function makeUserType(mixed $user): array
    {
        /**
         * @var Member $user
         */
        if ($user->isMember()) {
            return [$user->type->value];
        }

        return [
            $user->type->value,
            $user->isTraderDragonTiger() ? 'dragonTiger' : 'cockfight',
        ];
    }

    private function isAllow(array $userType, array $allowType): bool
    {
        return empty(array_diff($userType, $allowType));
    }
}
