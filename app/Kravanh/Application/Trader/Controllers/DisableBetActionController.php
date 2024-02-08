<?php

namespace App\Kravanh\Application\Trader\Controllers;

use App\Kravanh\Domain\Match\Models\Matches;
use App\Kravanh\Domain\Match\Supports\Enums\BetOn;
use BenSampo\Enum\Rules\EnumValue;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class DisableBetActionController
{

    public function __invoke(Request $request): JsonResponse|RedirectResponse
    {
        $request->validate([
            'type' => ['required', new EnumValue(BetOn::class)],
            'value' => ['required', 'boolean']
        ]);

        $match = Matches::live($request->user());

        Cache::put("match:{$match->id}:disable:{$request->type}", $request->value, now()->addMinutes(10));

        $type = strtolower(BetOn::fromValue($request->type)->description);

        $text = $request->value ? 'enabled' : 'disabled';
        return redirectSucceed("$type is $text");

    }

}
