<?php

namespace App\Kravanh\Application;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;

class SetLocaleController
{
    public function __invoke(Request $request): JsonResponse|RedirectResponse
    {
        Cache::put('lang:' . $request->user()->id, $request->lang);

        App::setLocale($request->lang);

        return redirectSucceed('Language changed');
    }

}
