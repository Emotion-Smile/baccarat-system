<?php

namespace App\Kravanh\Application\Member\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SwitchGroupController
{
    public function __invoke(Request $request): JsonResponse|RedirectResponse
    {
        $request->validate([
            'groupId' => 'required|numeric',
        ]);

        $request->user()->fill([
            'group_id' => $request->groupId,
            'two_factor_secret' => null,
        ])->saveQuietly();

        return redirectSucceed('Your switch to table #'.$request->groupId);
    }
}
