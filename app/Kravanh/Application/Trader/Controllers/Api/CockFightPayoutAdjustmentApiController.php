<?php

namespace App\Kravanh\Application\Trader\Controllers\Api;

use App\Kravanh\Application\Trader\Requests\MatchRequest;
use App\Kravanh\Domain\Match\Events\MatchPayoutUpdated;
use App\Kravanh\Domain\Match\Models\Matches;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class CockFightPayoutAdjustmentApiController
{
    /**
     * @throws \Throwable
     */
    public function __invoke(
        MatchRequest $request,
    ): JsonResponse
    {
        /**
         * @var Matches $match
         */

        $match = Matches::live($request->user());

        if (!$match) {
            return response()
                ->json([
                    'message' => 'Oops, you cannot adjust the payout because the match does not exist.'
                ], Response::HTTP_BAD_REQUEST);
        }

        $payoutInfo = $this->payoutAdjustment($request, $match);
        $totalBet = $this->getTotalBet($match);

        return response()->json([
            'meronTotalBetAmount' => $totalBet['meronTotalBet'],
            'walaTotalBetAmount' => $totalBet['walaTotalBet'],
            'totalTicket' => $totalBet['totalTicket'],
            'meronPayoutRate' => $payoutInfo['meron_payout'],
            'walaPayoutRate' => $payoutInfo['wala_payout'],
            'fightNumber' => $match->fight_number
        ]);

    }

    //@TODO array return keys

    /**
     * @param $match
     * @return array{meronTotalBet: int,walaTotalBet: int,totalTicket: int}
     */
    private function getTotalBet($match): array
    {
        /**
         * @var Matches $match
         */
        $matchBetInfoKey = $match->getCacheKey(Matches::MATCH_BET_INFO);

        $payload = [
            'meronTotalBet' => 0,
            'walaTotalBet' => 0,
            'totalTicket' => 0
        ];

        if (Cache::has($matchBetInfoKey)) {
            $payload = Cache::get($matchBetInfoKey);
        }

        return $payload;
    }

    /**
     * @param MatchRequest $request
     * @param Matches $match
     * @return array{id: int, environment_id: int, group_id: int, meron_payout: string, wala_payout: string}
     * @throws \Throwable
     */
    protected function payoutAdjustment(
        MatchRequest $request,
        Matches      $match
    ): array
    {

        DB::transaction(function () use ($match, $request) {
            $match->payout_total = $request->totalPayout;
            $match->payout_meron = $request->meronPayout;
            $match->saveQuietly();
        }, 5);

        $payload = $match->broadCastPayout();
        MatchPayoutUpdated::dispatch($payload);

        Cache::put(
            Matches::ADJUST . $match->id, true,
            now()->addHour()
        );

        return $payload;
    }
}
