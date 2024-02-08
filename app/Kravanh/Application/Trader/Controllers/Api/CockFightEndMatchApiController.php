<?php

namespace App\Kravanh\Application\Trader\Controllers\Api;

use App\Kravanh\Domain\Match\Events\MatchEnded;
use App\Kravanh\Domain\Match\Events\MatchEndedResultSummary;
use App\Kravanh\Domain\Match\Models\Matches;
use App\Kravanh\Domain\Match\Supports\Enums\MatchResult;
use BenSampo\Enum\Rules\EnumValue;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class CockFightEndMatchApiController
{
    /**
     * @throws \Throwable
     */
    public function __invoke(Request $request): JsonResponse
    {
        $request->validate([
            'result' => ['required', new EnumValue(MatchResult::class)]
        ]);

        $match = Matches::live($request->user());

        if (!$match) {
            return response()->json([
                'message' => 'Oops, we do not have a live match for submitting the result.',
            ], Response::HTTP_BAD_REQUEST);
        }

        $match->endMatch($request->result);
        $this->persistTotalBet($match);

        $this->forgetCache($match);
        $this->broadcastEvent($match);

        return response()->json([
            'fightNumber' => $match->fight_number,
        ]);
    }

    private function forgetCache(Matches $match): void
    {
        Cache::forget($match->getCacheKey(Matches::MATCH_RESULT_TODAY));
        Cache::forget($match->getCacheKey(Matches::MATCH_BET_INFO));
    }

    private function broadcastEvent(Matches $match): void
    {
        $resultSummary = Matches::todayResultSummary($match->group_id);
        $resultSummary['group_id'] = $match->group_id;
        $resultSummary['environment_id'] = $match->environment_id;

        MatchEndedResultSummary::dispatch($resultSummary);

        // match end
        MatchEnded::dispatch($match->broadCastDataToMember());
    }

    /**
     * @throws \Throwable
     */
    public function persistTotalBet($match): void
    {

        DB::transaction(function () use ($match) {

            $matchBetInfoFromCache = Cache::get($match->getCacheKey(Matches::MATCH_BET_INFO));
            $match->meron_total_bet = $matchBetInfoFromCache['meronTotalBet'] ?? 0;
            $match->wala_total_bet = $matchBetInfoFromCache['walaTotalBet'] ?? 0;
            $match->total_ticket = $matchBetInfoFromCache['totalTicket'] ?? 0;
            $match->wala_total_payout = $matchBetInfoFromCache['walaTotalPayout'] ?? 0;
            $match->meron_total_payout = $matchBetInfoFromCache['meronTotalPayout'] ?? 0;

            $match->saveQuietly();
        }, 3);
    }
}
