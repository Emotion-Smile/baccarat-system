<?php

namespace App\Kravanh\Domain\User\Actions;

use App\Kravanh\Domain\Environment\Models\Domain;
use Closure;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use OptimistDigital\NovaSettings\Models\Settings;

final class LoginScreenRenderAction
{
    public static function handle(): Closure
    {
        return (new LoginScreenRenderAction())();
    }

    public function __invoke(): Closure
    {
        return $this->loginViewRenderer();
    }

    private function defaultLoginUI(): Response
    {
        $viewName = config('domain.default_login_view');

        return Inertia::render("Auth/Logins/{$viewName}", [
            'logo' => Settings::getValueForKey('login_logo'), 
            'backgroundImages' => [
                'i6' => Settings::getValueForKey('login_bg_i6'),
                'i6p' => Settings::getValueForKey('login_bg_i6p'),
                'mobile' => Settings::getValueForKey('login_bg_mobile'),
                'desktop' => Settings::getValueForKey('login_bg_desktop')
            ]
        ]);
    }

    private function loginUIByDomain($domain): Response
    {
        $viewName = takeValueIfKeyExist($domain->meta, 'login_view') ?? config('domain.default_login_view');
        
        return Inertia::render("Auth/Logins/$viewName", [
            'logo' => [
                'url' => takeValueIfKeyExist($domain->meta, 'logo_url'),
                'width' => takeValueIfKeyExist($domain->meta, 'logo_width'),
            ],
            'promotionImages' => takeValueIfKeyExist($domain->meta, 'promotion_images') ?? [],
            'phoneNumbers' => takeValueIfKeyExist($domain->meta, 'phone_numbers') ?? [],
            'socials' => [
                'telegram' => takeValueIfKeyExist($domain->meta, 'telegram_link'),
                'facebook' => takeValueIfKeyExist($domain->meta, 'facebook_link')
            ],
            'copyrightText' => takeValueIfKeyExist($domain->meta, 'copyright_text') 
        ]);

    }

    private function loginViewRenderer(): Closure
    {
        return function (Request $request) {

            $domain = Domain::findByDomain($request->server('HTTP_HOST'));
            
            return $domain->count()
                ? $this->loginUIByDomain($domain)
                : $this->defaultLoginUI();

        };
    }

}
