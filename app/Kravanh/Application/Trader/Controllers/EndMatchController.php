<?php

namespace App\Kravanh\Application\Trader\Controllers;

use App\Kravanh\Domain\Match\Events\MatchEnded;
use App\Kravanh\Domain\Match\Events\MatchEndedResultSummary;
use App\Kravanh\Domain\Match\Jobs\EndMatchJob;
use App\Kravanh\Domain\Match\Models\Matches;
use App\Kravanh\Domain\Match\Supports\Enums\MatchResult;
use BenSampo\Enum\Rules\EnumValue;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class EndMatchController
{
    /**
     * @throws \Throwable
     */
    public function __invoke(Request $request): RedirectResponse|JsonResponse
    {
        $request->validate([
            'result' => ['required', new EnumValue(MatchResult::class)]
        ]);

        $match = Matches::live($request->user());

        if (!$match) {
            return redirectWith([
                'type' => 'error',
                'message' => 'Oop, we don\'t have live match now.',
            ]);
        }

        if ($match->isBettingOpened() && !$match->isBettingClosed()) {
            return redirectWith([
                'type' => 'error',
                'message' => 'Please close bet before submit the match result',
            ]);
        }


        $match->endMatch($request->result);

        EndMatchJob::dispatchIf(
            $match->group->auto_trader,
            [
                'groupId' => $match->group_id,
                'result' => $request->result
            ]
        );

        $this->persistTotalBet($match);

        Cache::forget($match->getCacheKey(Matches::MATCH_RESULT_TODAY));
        Cache::forget($match->getCacheKey(Matches::MATCH_BET_INFO));

        $resultSummary = Matches::todayResultSummary($match->group_id);

        $resultSummary['group_id'] = $match->group_id;
        $resultSummary['environment_id'] = $match->environment_id;

        MatchEndedResultSummary::dispatch($resultSummary);

        // match end
        MatchEnded::dispatch($match->broadCastDataToMember());


        return redirectWith([
            'message' => "Fight# $match->fight_number was ended.",
            'toast' => false
        ]);
    }

    /**
     * @throws \Throwable
     */
    public function persistTotalBet($match)
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
