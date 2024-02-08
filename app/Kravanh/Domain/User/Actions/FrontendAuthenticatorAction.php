<?php

namespace App\Kravanh\Domain\User\Actions;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Hash;

final class FrontendAuthenticatorAction
{
    public static function handle(): Closure
    {
        return (new FrontendAuthenticatorAction())();
    }

    public function __invoke(): Closure
    {
        return function (Request $request) {
            $this->blocker($request);

            $user = $this->getUser($request->get('name'));

            if (! $user) {
                return null;
            }

            if (! $this->isMemberBelongToDomain($request, $user)) {
                return null;
            }

            return $this->userPasswordVerification($request->get('password'), $user);
        };
    }

    private function userPasswordVerification(string $password, User $user): ?User
    {
        return Hash::check($password, $user->password) ? $user : null;
    }

    private function getUser(string $name): ?User
    {
        return User::frontendLogin($name);
    }

    private function getIpFromRequest(Request $request): array|string|null
    {
        return $request->header('x-vapor-source-ip') ?? $request->ip();
    }

    private function isMemberBelongToDomain(Request $request, User $user): bool
    {
        if (! $user->isMember()) {
            return true;
        }

        return ! userDomainInvalidWithCurrentDomain($request, $user);
    }

    private function blocker(Request $request): void
    {
        if (! App::isLocal()) {
            //AuthenticationManager::check($this->getIpFromRequest($request))->blockAll();
        }
    }
}
