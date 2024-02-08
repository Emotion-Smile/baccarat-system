<?php

namespace App\Kravanh\Application\Trader\Controllers;

use App\Kravanh\Domain\Match\Events\MatchBetClosed;
use App\Kravanh\Domain\Match\Events\MatchBetOpened;
use App\Kravanh\Domain\Match\Supports\MemberTypeHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ToggleBetButtonController
{
    public function __invoke(Request $request): JsonResponse|RedirectResponse
    {
        $request->validate([
            'status' => 'required|boolean',
            'matchId' => 'required|numeric',
            'memberTypeId' => 'required|numeric'
        ]);

        $user = $request->user();

        if ($request->status) {

            MatchBetOpened::dispatch([
                'environment_id' => $user->environment_id,
                'group_id' => $user->group_id,
                'memberType' => $request->memberTypeId,
                'status' => 'open',
                'disable_bet_button' => false,
                'bet_status' => 'open'
            ]);

            MemberTypeHelper::fromRequest($request)
                ->setBetStatus([
                    'status' => 'open',
                    'disable_bet_button' => false,
                    'bet_status' => 'open'
                ]);

        } else {
            MatchBetClosed::dispatch([
                'environment_id' => $user->environment_id,
                'group_id' => $user->group_id,
                'memberType' => $request->memberTypeId,
                'status' => 'close',
                'disable_bet_button' => true,
                'bet_status' => 'close'
            ]);

            MemberTypeHelper::fromRequest($request)
                ->setBetStatus([
                    'status' => 'close',
                    'disable_bet_button' => true,
                    'bet_status' => 'close'
                ]);

        }

        return redirect()
            ->back()
            ->with([
                'message' => '',
                'type' => 'success',
                'toast' => false
            ]);
    }
}
