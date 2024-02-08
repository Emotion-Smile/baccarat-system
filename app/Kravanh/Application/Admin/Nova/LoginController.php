<?php

namespace App\Kravanh\Application\Admin\Nova;

use App\Kravanh\Domain\User\Supports\Enums\UserType;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Laravel\Nova\Http\Controllers\LoginController as NovaLoginController;

class LoginController extends NovaLoginController
{
    /**
     * @param  User  $user
     * @return RedirectResponse|void
     */
    protected function authenticated(Request $request, $user)
    {
        if (in_array($user->type->value, [UserType::MEMBER, UserType::TRADER])) {
            return $this->backToLogin('member');
        }

        if ($user->suspend) {
            return $this->backToLogin();
        }

        if (! App::isLocal() && ! $user->type->is(UserType::DEVELOPER)) {

            $domains = explode(',', $user->environment?->domain ?? '');
            $domainAllowed = in_array($request->getHost(), $domains);

            if (! $domainAllowed) {
                return $this->backToLogin();
            }
        }

        if (! in_array($user->type->value, exceptUserType()) && userDomainInvalidWithCurrentDomain($request, $user)) {
            return $this->backToLogin();
        }
    }

    public function backToLogin($guard = 'web'): RedirectResponse
    {
        auth($guard)->logout();
        request()->session()->invalidate();

        return redirect()
            ->route('nova.login')
            ->withErrors(['name' => 'These credentials do not match our records']);
    }

    public function username(): string
    {
        return 'name';
    }
}
