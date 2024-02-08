<?php

namespace App\Kravanh\Domain\Integration\Http\Controllers\Member;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;

class GatewayController
{
    public function __invoke(
        Request $request,
        string $site
    ): RedirectResponse
    {
        $user = $request->user();
        
        $user->tokens()->delete();

        $token = $user->createToken($user->name)->plainTextToken;

        $to = match ($site) {
            't88' => $this->redirectT88Url($token),
            'af88' => $this->redirectAF88Url($token) 
        };

        return Redirect::away($to);
    }

    protected function redirectT88Url(string $token): string
    {
        $baseUrl = Str::of(config('t88.base_url'))->trim('/');

        return "{$baseUrl}/magic-login/{$token}";
    }

    protected function redirectAF88Url(string $token): string
    {
        $baseUrl = Str::of(config('af88.base_url'))->trim('/');

        return "{$baseUrl}/integration/login/{$token}";
    }
} 