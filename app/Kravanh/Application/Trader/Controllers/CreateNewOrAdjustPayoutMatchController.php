<?php

namespace App\Kravanh\Application\Trader\Controllers;

use App\Kravanh\Application\Trader\Requests\MatchRequest;
use App\Kravanh\Domain\Match\Actions\CreateNewMatchAction;
use App\Kravanh\Domain\Match\DataTransferObject\NewMatchData;
use App\Kravanh\Domain\Match\Events\MatchBenefited;
use App\Kravanh\Domain\Match\Events\MatchCreated;
use App\Kravanh\Domain\Match\Events\MatchPayoutUpdated;
use App\Kravanh\Domain\Match\Jobs\StartNewMatchJob;
use App\Kravanh\Domain\Match\Models\Matches;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class CreateNewOrAdjustPayoutMatchController
{
    /**
     * @throws \Throwable
     */
    public function __invoke(MatchRequest $request): RedirectResponse|JsonResponse
    {

        $match = Matches::live($request->user());

        if (!$match) {

            $newMatch = (new CreateNewMatchAction)(NewMatchData::fromRequest($request));

            $this->cacheAndNotifyClient($newMatch);

            StartNewMatchJob::dispatchIf($newMatch->group->auto_trader,
                [
                    'groupId' => $newMatch->group_id,
                    'totalPayout' => $request->totalPayout,
                    'meronPayout' => $request->meronPayout,
                    'fightNumber' => $newMatch->fight_number,
                ]
            );

            return redirectWith([
                'message' => "New Fight# {$newMatch->fight_number} created"
            ]);
        }

        $this->payoutAdjustment($request, $match);

        return redirectWith([
            'message' => "Fight# {$match->fight_number} payout adjusted"
        ]);

    }

    protected function cacheAndNotifyClient(Matches $match): void
    {

        $match->clearCacheMatchNotLive();
        $match->liveRefreshCache();
        $match->cacheLastFightNumber();

        MatchCreated::dispatch($match->broadCastDataToMember());

        MatchBenefited::dispatch(Matches::estimateBenefit(
            envId: $match->environment_id,
            groupId: $match->group_id,
            initialize: true
        ));

    }

    /**
     * @throws \Throwable
     */
    protected function payoutAdjustment(MatchRequest $request, Matches $match): void
    {
        DB::transaction(function () use ($match, $request) {
            $match->payout_total = $request->totalPayout;
            $match->payout_meron = $request->meronPayout;
            $match->saveQuietly();
        }, 5);

        MatchPayoutUpdated::dispatch($match->broadCastPayout());

        Cache::put(
            Matches::ADJUST . $match->id, true,
            now()->addHour()
        );
    }
}
