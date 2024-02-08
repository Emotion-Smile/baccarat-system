<?php

namespace KravanhEco\Report\Modules\Cockfight\Actions;

use App\Kravanh\Domain\Match\Models\BetRecord;
use App\Kravanh\Domain\Match\Supports\Enums\BetStatus;
use App\Kravanh\Domain\Match\Supports\Enums\MatchResult;
use App\Kravanh\Domain\User\Supports\Enums\UserType;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Log;

class GetWinLoseAction
{
    public function __invoke(Request $request)
    {
        return $this->reportManager($request); 
    }

    protected function getCurrentUser(User $user)
    {
        $currentUserLoginId = $user->id;
        $currentUserLoginType = $user->type;

        if ($user->type->is(UserType::SUB_ACCOUNT)) {
            $parent = $user->getParent();
            $currentUserLoginId = $parent['id'];
            $currentUserLoginType = $parent['type'];
        }

        return [$currentUserLoginId, $currentUserLoginType];
    }

    protected function reportManager(Request $request)
    {
        $requestDate = $request->get('date');

        list($currentUserLoginId, $currentUserLoginType) = $this->getCurrentUser($request->user());

        /**
         * Current  report for company only
         */
        $isWantToCurrentMonthCache = appGetSetting('cache_win_lose_current_month', false);
        if (
            $requestDate === 'current-month'
            && $currentUserLoginType == UserType::COMPANY
            && is_null(request('userId'))
            && $isWantToCurrentMonthCache
        ) {
            $month = today()->format('Ym');
            $key = "report:current:month:{$month}";
            return Cache::remember(
                $key,
                now()->addHour(),
                function () {
                    return $this->queryReport();
                });
        }


        $isLastMonthRequest = $requestDate === 'last-month';

        if (!$isLastMonthRequest) {
            return self::query(self::columns([
                'users.id',
                'users.name',
                'users.phone',
                'users.currency',
                'users.type as userType'
            ]), true)
                ->get();
        }

        /**
         * Last month report
         */

        $isWantToCurrentMonthCache = appGetSetting('cache_win_lose_last_month', false);

        if (!$isWantToCurrentMonthCache) {
            return $this->queryReport();
        }

        return Cache::remember(
            $this->makeCacheKey($request, $currentUserLoginType, $currentUserLoginId),
            now()->addMonths(2),
            function () {
                return $this->queryReport();
            });
    }

    protected function queryReport()
    {
        Log::info("query report from database");

        return self::query(self::columns([
            'users.id',
            'users.name',
            'users.phone',
            'users.currency',
            'users.type as userType'
        ]), true)
            ->get();
    }

    protected static function query(array $select, bool $groupBy = false)
    {
        $userId = request('userId') ?? null;
        // $startDate = request('startDate') ?? null;
        // $endDate = request('endDate') ?? null;

        $currentUser = request()->user();
        $currentUserId = $currentUser->id;
        $currentUserType = $currentUser->type;

        if ($currentUser->type->is(UserType::SUB_ACCOUNT)) {
            $parent = $currentUser->getParent();
            $currentUserId = $parent['id'];
            $currentUserType = $parent['type'];
        }

        $viewUser = null;

        if ($userId) {
            $viewUser = User::findOrFail($userId);

            if (!self::checkLevelCanAccess($currentUserType, $viewUser->type)) {
                abort(403);
            }
        }

        $underUserType = self::underUserType($viewUser ? $viewUser->type : $currentUserType);

        $hasGroup = true;

        if (request('group') === null || request('group') === '0') {
            $hasGroup = false;
        }

        $query = BetRecord::select($select)
            ->join('users', 'users.id', '=', "bet_records.{$underUserType}")
            ->join('matches', 'matches.id', '=', 'bet_records.match_id')
            ->where('bet_records.status', BetStatus::ACCEPTED)
            ->when($hasGroup, fn($query) => $query->where('bet_records.group_id', request('group')))
            ->when(request('date') === 'today', fn($query) => $query->withoutLiveMatch())
            ->when(!in_array($currentUserType, [UserType::COMPANY, UserType::DEVELOPER]), function ($query) use ($currentUserId, $currentUserType) {
                $query->where("bet_records.{$currentUserType}", $currentUserId);
            })
            ->when($viewUser, function ($query, $viewUser) {
                $query->where("bet_records.{$viewUser->type}", $viewUser->id);
            })
            ->when(request('from') && request('to'), function (Builder $query) {
                $query->whereBetween('bet_records.bet_date', [
                    request('from'),
                    request('to')
                ]);
            })
            ->when(request('date') ?? null, function (Builder $query, string $date) {
                $column = 'bet_records.bet_date';

                if ($date === 'today') {
                    $query->where($column, Date::today()->format('Y-m-d'));
                }

                if ($date === 'yesterday') {
                    $query->where($column, Date::today()->subDay()->format('Y-m-d'));
                }

                if ($date === 'this-week') {

                    $query->whereBetween($column, [
                        now()->startOfWeek()->format('Y-m-d'),
                        now()->endOfWeek()->format('Y-m-d')
                    ]);
                }

                if ($date === 'last-week') {
                    // 60158964 - 60158964
                    // bet_records.id
//                    $query->whereBetween($column, [
//                        now()->subWeek()->startOfWeek()->format('Y-m-d'),
//                        now()->subWeek()->endOfWeek()->format('Y-m-d')
//                    ]);

                    $query->whereBetween('bet_records.id', [
                        BetRecord::where('bet_date', now()->subWeek()->startOfWeek()->toDateString())->min('id'),
                        BetRecord::where('bet_date', now()->subWeek()->endOfWeek()->toDateString())->max('id')
                    ]);
                }

                if ($date === 'current-month') {

//                    if (!$viewUser && in_array($currentUserType, [UserType::COMPANY, UserType::DEVELOPER])) {
//                        $query->forceIndex('idx_bet_date');
//                    }


                    $startIdOfTheMonth = BetRecord::where('bet_date', today()->firstOfMonth()->format('Y-m-d'))->min('id');

                    $query->where('bet_records.id', '>=', $startIdOfTheMonth);
//                    $query->whereBetween($column, [
//                        today()->firstOfMonth()->format('Y-m-d'),
//                        today()->endOfMonth()->format('Y-m-d'),
//                    ]);

                }

                if ($date === 'last-month') {

//                    if (!$viewUser && in_array($currentUserType, [UserType::COMPANY, UserType::DEVELOPER])) {
//                        $query->forceIndex('idx_bet_date');
//                    }

                    $startIdOfTheMonth = BetRecord::where('bet_date', today()->subMonthNoOverflow()->firstOfMonth()->format('Y-m-d'))->min('id');
                    $endIdOfTheMonth = BetRecord::where('bet_date', today()->subMonthNoOverflow()->endOfMonth()->format('Y-m-d'))->max('id');

                    $query->whereBetween('bet_records.id', [$startIdOfTheMonth, $endIdOfTheMonth]);

//                    $query->whereBetween($column, [
//                        today()->subMonthNoOverflow()->firstOfMonth()->format('Y-m-d'),
//                        today()->subMonthNoOverflow()->endOfMonth()->format('Y-m-d')
//                    ]);
                }
            });

        if ($groupBy) {
            $query = $query->groupBy("bet_records.{$underUserType}");
        }


        return $query->orderBy('users.name'); 
    }

    protected static function columns(array $columns = [])
    {
        return [
            ...$columns,
            \DB::raw('SUM(bet_records.amount) AS bet_amount'),
            \DB::raw('SUM(
                CASE
                    WHEN matches.result = ' . MatchResult::CANCEL . ' THEN 0
                    WHEN matches.result = ' . MatchResult::DRAW . ' THEN 0
                    WHEN matches.result = ' . MatchResult::PENDING . ' THEN 0
                    WHEN matches.result = ' . MatchResult::NONE . ' THEN 0
                    WHEN bet_records.bet_on = matches.result THEN bet_records.payout
                    ELSE -(bet_records.amount)
                END
            ) AS win_lose'),
        ];
    }

    protected static function underUserType($type): string
    {
        if ($type == UserType::SUPER_SENIOR) {
            $type = UserType::SENIOR;
        } elseif ($type == UserType::SENIOR) {
            $type = UserType::MASTER_AGENT;
        } elseif ($type == UserType::MASTER_AGENT) {
            $type = UserType::AGENT;
        } elseif ($type == UserType::AGENT) {
            $type = 'user_id';
        } else {
            $type = UserType::SUPER_SENIOR;
        }

        return $type;
    }

    protected static function checkLevelCanAccess(string $currentType, string $accessToType)
    {
        if (in_array($currentType, [UserType::COMPANY, UserType::DEVELOPER])) return true;

        $userTypes = [
            UserType::MEMBER,
            UserType::AGENT,
            UserType::MASTER_AGENT,
            UserType::SENIOR,
            UserType::SUPER_SENIOR,
        ];

        $current = array_search($currentType, $userTypes);
        $accessTo = array_search($accessToType, $userTypes);

        return $current > $accessTo;
    }

    /**
     * @TODO cache key
     * @param Request $request
     * @return string
     * @throws \Exception
     */
    protected function makeCacheKey(Request $request, $currentUserLoginId, $currentUserLoginType): string
    {
        $reportBelongUserId = $request->get('userId');

//        $user = $request->user();
//        $currentUserLoginId = $user->id;
//        $currentUserLoginType = $user->type;
//
//        if ($user->type->is(UserType::SUB_ACCOUNT)) {
//            $parent = $user->getParent();
//            $currentUserLoginId = $parent['id'];
//            $currentUserLoginType = $parent['type'];
//        }

        $reportOwner = null;

        if ($reportBelongUserId) {
            $reportOwner = User::findOrFail($reportBelongUserId);
//            if (!self::checkLevelCanAccess($currentUserLoginType, $reportOwner->type)) {
//                abort(403);
//            }
        }

        $month = today()->subMonthNoOverflow()->format('Ym');
        $key = "report:last:month:{$month}:";

        if ($request->get('group') === null || $request->get('group') === '0') {
            $key .= 'group:all:';
        } else {
            $key .= "group:" . $request->get('group') . ":";
        }

        $groupByUserType = self::underUserType($reportOwner ? $reportOwner->type : $currentUserLoginType);

        if ($reportOwner) {
            $key .= "{$reportOwner->type}:$reportOwner->id";
        } else {
            if ($currentUserLoginType == 'company') {
                $key .= $groupByUserType;
            } else {
                $key .= "{$currentUserLoginType}:$currentUserLoginId";
            }
        }


        Log::info("last month report cache key: {$key}");

        $this->storeCacheKeys($key);

        return $key;
    }

    protected function storeCacheKeys($key)
    {
        $allKeys = [];
        $keys = Cache::get('last:month:report:cache:keys');

        if (is_null($keys)) {
            $allKeys[] = $key;
        } else {
            $allKeys = Arr::prepend($keys, $key);
        }

        Cache::put('last:month:report:cache:keys', array_unique($allKeys), now()->addMonths(3));
    }
}
