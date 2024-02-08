<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{

    protected $rootView = 'app';

    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    public function share(Request $request): array
    {
        if (auth()->guest()) {
            return array_merge(parent::share($request), [
                'themeColor' => getThemeColor($request)
            ]);
        }

        $message = $request->session()->get('message');

        return array_merge(parent::share($request), [
            'csrf_token' => csrf_token(),
            'currentDateTime' => now(),
            'locale' => App::getLocale(),
            'themeColor' => getThemeColor($request),
            'hasGame' => [
                'af88' => $request->user()->hasAF88GameCondition() && $request->user()->hasAllowAF88Game(),
                't88' => [
                    'yuki' => $request->user()->hasT88GameCondition('LOTTO-12') && $request->user()->hasAllowT88Game()
                ]
            ],
            'flash' => function () use ($request, $message) {
                return [
                    'title' => $request->session()->get('title', 'Notification'),
                    'message' => $message,
                    'type' => $request->session()->get('type', 'success'),
                    'toast' => $request->session()->get('toast', !is_null($message))
                ];
            },
        ]);

    }
}
