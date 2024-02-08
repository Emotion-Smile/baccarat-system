<?php

namespace App\Kravanh\Domain\DragonTiger\App\Member\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class DragonTigerGameMemberSwitchTableActionController
{
    public function __invoke(Request $request): JsonResponse|RedirectResponse
    {

        $request->validate([
            'tableId' => 'required|numeric',
        ]);

        $request->user()->fill([
            'group_id' => $request->tableId,
            'two_factor_secret' => 'dragon_tiger',
        ])->saveQuietly();

        return redirect()->back();

    }
}
