<?php

namespace App\Kravanh\Application\Trader\Controllers\Api;

use App\Kravanh\Domain\Match\Events\MatchBetClosed;
use App\Kravanh\Domain\Match\Events\MatchBetOpened;
use App\Kravanh\Domain\Match\Models\Matches;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CockFightToggleBetApiController
{

    public function __invoke(Request $request): JsonResponse
    {
        $request->validate([
            'betStatus' => 'required|boolean'
        ]);

        $match = Matches::live($request->user());

        if (!$match) {
            return $this->responseAsBadRequest('Oops, you cannot open or close betting because the match does not exist.');
        }

        //request open bet
        if ($request->betStatus) {

            if ($match->isBettingOpened() || $match->isBettingClosed()) {
                return $this->responseAsBadRequest('Oops, we did not allow to re-open betting for the single match.');
            }

            return $this->toggleBetHandler(match: $match, isOpen: true);
        }

        //request close bet
        return $this->toggleBetHandler(match: $match, isOpen: false);
    }


    private function toggleBetHandler($match, $isOpen): JsonResponse
    {

        $payload = $this->matchToggleBet($match, $isOpen);
        $this->broadcastEvent($payload);

        return response()
            ->json([
                'fightNumber' => $match->fight_number,
                'betStatus' => $payload['bet_status'],
            ]);
    }

    private function broadcastEvent(array $payload): void
    {
        $status = $payload['bet_status'];

        if ($status === 'open') {
            MatchBetOpened::dispatch($payload);
        }

        if ($status === 'close') {
            MatchBetClosed::dispatch($payload);
        }

    }

    private function matchToggleBet($match, $isOpen): array
    {
        /**
         * @var Matches $match
         */
        if ($isOpen) {
            $match->bet_started_at = now();
        } else {
            $match->bet_started_at ??= now();
            $match->bet_stopped_at = now();
        }

        $match->saveQuietly();
        $match->refresh();
        $match->liveRefreshCache();

        return $match->broadCastToggleBet();
    }

    private function responseAsBadRequest(string $message): JsonResponse
    {
        return response()->json([
            'message' => $message
        ], Response::HTTP_BAD_REQUEST);
    }
}
