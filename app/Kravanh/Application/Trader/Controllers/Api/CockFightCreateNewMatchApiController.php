<?php

namespace App\Kravanh\Application\Trader\Controllers\Api;

use App\Kravanh\Application\Trader\Requests\MatchRequest;
use App\Kravanh\Domain\Match\Actions\CreateNewMatchAction;
use App\Kravanh\Domain\Match\DataTransferObject\NewMatchData;
use App\Kravanh\Domain\Match\Events\MatchBenefited;
use App\Kravanh\Domain\Match\Events\MatchCreated;
use App\Kravanh\Domain\Match\Models\Matches;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class CockFightCreateNewMatchApiController
{
    public function __invoke(
        MatchRequest $request
    ): JsonResponse
    {

        $match = Matches::live($request->user());

        if ($match) {
            return response()->json([
                'message' => 'Please end the current match before creating a new one.'
            ], Response::HTTP_BAD_REQUEST);
        }

        $match = (new CreateNewMatchAction)(NewMatchData::fromRequest($request));

        $this->cache($match);
        $this->broadcastEvent($match);

        return response()->json([
            'fightNumber' => $match->fight_number
        ]);
    }


    private function broadcastEvent(Matches $match): void
    {
        MatchCreated::dispatch($match->broadCastDataToMember());

        MatchBenefited::dispatch(Matches::estimateBenefit(
            envId: $match->environment_id,
            groupId: $match->group_id,
            initialize: true
        ));
    }

    protected function cache(Matches $match): void
    {
        $match->clearCacheMatchNotLive();
        $match->liveRefreshCache();
        $match->cacheLastFightNumber();
    }
}
