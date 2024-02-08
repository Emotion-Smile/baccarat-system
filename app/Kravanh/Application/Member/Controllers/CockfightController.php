<?php

namespace App\Kravanh\Application\Member\Controllers;

use App\Kravanh\Domain\Environment\Actions\GroupGetAction;
use App\Kravanh\Domain\Environment\Actions\GroupGetGroupIdsAvailableAction;
use App\Kravanh\Domain\GroupUser\Actions\GroupGetIdsBelongToUser;
use App\Kravanh\Domain\Match\Models\Matches;
use App\Kravanh\Domain\Match\Supports\Enums\MatchResult;
use App\Kravanh\Support\BetHelper;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;
use OptimistDigital\NovaSettings\Models\Settings;

class CockfightController
{
    public function __invoke(Request $request): Response
    {
        $user = $request->user();
        $disableGroupId = $this->getGroupIdDisabled($user);
        $groupId = $this->getAllowGroupId($user, $disableGroupId);

        $data = [
            'betRecords' => fn () => $this->getBetRecords($request->todayReport ?? false),
            'betValueRange' => fn () => BetHelper::betValue($user->currency, $user->condition['minimum_bet_per_ticket'] ?? 0),
            'matchInfo' => fn () => Matches::liveWithDefault($user),
            'resultCount' => fn () => Matches::todayResultCount($groupId),
            'resultSymbols' => fn () => Matches::todayResultAsSymbol($groupId),
            'betConfiguration' => [
                'show_only_own_bet' => config('option.betting.show_only_own_bet', false),
                'show_your_bet' => config('option.betting.show_your_bet', false),
            ],
            'groups' => fn () => $this->getGroups($disableGroupId, $user),
            'marqueeSetting' => $this->getMarqueeSetting($groupId),
        ];

        return Inertia::render('Member/Cockfight', $data);
    }

    private function getAllowGroupId($user, array $disableGroupId)
    {
        /**
         * @var User $user ;
         */
        $userCurrentGroupId = $user->group_id;
        $allowGroupIds = app(GroupGetGroupIdsAvailableAction::class)($disableGroupId, $user);

        if (
            ! in_array($userCurrentGroupId, $disableGroupId) &&
            in_array($userCurrentGroupId, $allowGroupIds)) {
            return $userCurrentGroupId;
        }

        abort_if(empty($allowGroupIds), 401);

        // reset user group ID
        $user->group_id = $allowGroupIds[0];
        $user->two_factor_secret = null;
        $user->saveQuietly();

        return $allowGroupIds[0];
    }

    private function getGroupIdDisabled($user): array
    {
        return app(GroupGetIdsBelongToUser::class)($user);
    }

    private function getGroups(array $disableGroupId, $user)
    {
        return app(GroupGetAction::class)($disableGroupId, $user);
    }

    protected function getBetRecords($todayReport = true): LengthAwarePaginator|array
    {
        $matchLive = Matches::live(user());

        if ($todayReport) {
            return $this->getBetToday($matchLive?->id ?? 0, user()->group_id);
        }

        if (! $matchLive) {
            return [];
        }

        return $this->getCurrentBet($matchLive);
    }

    protected function getCurrentBet(Matches $match): LengthAwarePaginator
    {
        return request()
            ->user()
            ->currentBetReport($match->id)
            ->paginate(perPage: 100)
            ->through($this->toCurrentBetReport());
    }

    protected function getBetToday(int $matchLiveId, int $groupId): LengthAwarePaginator
    {
        return request()
            ->user()
            ->todayBetReport($matchLiveId)
            ->where('group_id', $groupId)
            ->paginate(perPage: 50)
            ->through($this->toTodayReport());
    }

    protected function toCurrentBetReport(): \Closure
    {
        return function ($bet) {

            $betAmount = priceFormat(fromKHRtoCurrency($bet->amount, user()->currency), user()->currency, false);

            $winAndLoss = $betAmount." * {$bet->payout_rate}: ????";
            $userResult = 'None';

            return [
                'id' => $bet->id,
                'table' => $bet->group->name,
                'fight_number' => $bet->fight_number,
                'time' => $bet->bet_time->format(config('kravanh.time_format')),
                'bet_on' => Str::lower($bet->bet_on->description),
                'amount' => $bet->amount,
                'win_and_loss' => $winAndLoss,
                'user_result' => $userResult,
                'type' => $bet->type->description,
                'status' => $bet->status->description,
                'result' => [
                    'value' => 'None',
                    'key' => 0,
                ],
            ];
        };
    }

    protected function toTodayReport(): \Closure
    {
        return function ($bet) {

            $match = $bet->match;
            $currency = user()->currency;

            $betAmount = priceFormat(fromKHRtoCurrency($bet->amount, $currency), $currency, false);
            $payout = priceFormat(fromKHRtoCurrency($bet->payout, $currency), $currency, false);

            $userResult = 'loss';

            if ($match->result->is(MatchResult::CANCEL)) {
                $winAndLoss = 'cancel';
                $userResult = 'cancel';
            } elseif ($match->result->is(MatchResult::DRAW)) {
                $winAndLoss = 'draw';
                $userResult = 'draw';
            } elseif ($match->result->is(MatchResult::PENDING)) {
                $winAndLoss = 'pending';
                $userResult = 'pending';
            } else {
                if ($bet->bet_on->value === $match->result->value) {
                    $winAndLoss = $betAmount." * {$bet->payout_rate}: {$payout}";
                    $userResult = 'win';
                } else {
                    $winAndLoss = 'loss(-'.$betAmount.')';
                }
            }

            return [
                'id' => $bet->id,
                'table' => $bet->group->name,
                'fight_number' => $match->fight_number,
                'time' => $bet->bet_time->format(config('kravanh.time_format')),
                'bet_on' => Str::lower($bet->bet_on->description),
                'amount' => $betAmount,
                'win_and_loss' => $winAndLoss,
                'user_result' => $userResult,
                'type' => $bet->type->description,
                'status' => $bet->status->description,
                'result' => [
                    'value' => Str::lower(MatchResult::fromValue($match->result)->description),
                    'key' => $match->result,
                ],
            ];
        };
    }

    protected function getMarqueeSetting(int $groupId): array
    {
        $marqueeStatus = Settings::getValueForKey('marquee_status_'.$groupId);
        $marqueeTexts = json_decode(Settings::getValueForKey('marquee_text_'.$groupId), true);
        $marqueeSpeed = Settings::getValueForKey('marquee_speed_'.$groupId) ?? 50;

        return [
            'status' => (bool) $marqueeStatus,
            'text' => $marqueeTexts ? $marqueeTexts[App::getLocale()] : null,
            'speed' => (int) $marqueeSpeed,
        ];
    }
}
