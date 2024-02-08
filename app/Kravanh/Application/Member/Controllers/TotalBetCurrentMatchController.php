<?php

namespace App\Kravanh\Application\Member\Controllers;

use App\Kravanh\Domain\Match\Models\Matches;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TotalBetCurrentMatchController
{
    public function __invoke(Request $request): JsonResponse
    {
        $match = Matches::live($request->user());

        if (!$match) {
            return asJson([
                'meron' => 0,
                'wala' => 0
            ]);
        }

        return asJson($match->totalBetOfMember($request->user()->id));
    }
}
