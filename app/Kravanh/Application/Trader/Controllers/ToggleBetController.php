<?php

namespace App\Kravanh\Application\Trader\Controllers;

use App\Kravanh\Domain\Match\Events\MatchBetClosed;
use App\Kravanh\Domain\Match\Events\MatchBetOpened;
use App\Kravanh\Domain\Match\Jobs\CloseBetJob;
use App\Kravanh\Domain\Match\Jobs\OpenBetJob;
use App\Kravanh\Domain\Match\Models\Matches;
use Illuminate\Http\Request;

class ToggleBetController
{

    public function __invoke(Request $request)
    {
        $request->validate([
            'betStatus' => 'required|boolean'
        ]);

        $match = Matches::live($request->user());

        if (!$match) {
            return redirectWith([
                'type' => 'error',
                'message' => 'Oop, you cannot start bet without match'
            ]);

        }

        $betOpen = $request->betStatus;
        $match->refresh();

        if ($match->isBettingOpened() && $match->isBettingClosed()) {
            return redirectError('Sorry, we not allow re-open bet for the same match.');
        }

        if (!$match->isBettingOpened() && !$match->isBettingClosed()) {
            $match->bet_started_at = now();
            $match->saveQuietly();
            $match->liveRefreshCache();

            MatchBetOpened::dispatch($match->broadCastToggleBet());
            $status = $match->broadCastToggleBet()['bet_status'];

            if ($status === 'open') {

                OpenBetJob::dispatchIf($match->group->auto_trader, [
                    'groupId' => $match->group_id
                ]);

                return redirectSucceed("Fight number: {$match->fight_number} Betting {$status}");
            }
        }

        if (!$match->isBettingOpened() && $betOpen) {

            $match->bet_started_at = now();
            $match->saveQuietly();
            $match->liveRefreshCache();

            MatchBetOpened::dispatch($match->broadCastToggleBet());
            $status = $match->broadCastToggleBet()['bet_status'];

            if ($status === 'open') {
                return redirectSucceed("Fight number: {$match->fight_number} Betting {$status}");
            }
        }


        if ($match->isBettingOpened() && (!$match->isBettingClosed() && !$betOpen)) {

            $match->bet_stopped_at = now();
            $match->saveQuietly();
            $match->liveRefreshCache();

            MatchBetClosed::dispatch($match->broadCastToggleBet());

            $status = $match->broadCastToggleBet()['bet_status'];
            if ($status === 'close') {

                CloseBetJob::dispatchIf($match->group->auto_trader, [
                    'groupId' => $match->group_id
                ]);

                return redirectSucceed("Fight number: {$match->fight_number} Betting {$status}");
            }
        }

        return redirectError("Oop, something when wrong cannot toggle bet status");
    }
}
