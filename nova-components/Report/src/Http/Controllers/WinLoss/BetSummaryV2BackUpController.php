<?php

namespace KravanhEco\Report\Http\Controllers\WinLoss;

use App\Kravanh\Domain\Environment\Models\Group;
use App\Kravanh\Domain\Match\Models\BetRecord;
use App\Kravanh\Domain\Match\Supports\Enums\BetStatus;
use App\Kravanh\Domain\Match\Supports\Enums\MatchResult;
use App\Kravanh\Domain\User\Supports\Enums\UserType;
use App\Kravanh\Support\Enums\Currency;
use App\Models\User;
use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Date;

class BetSummaryV2BackUpController
{
    public function __invoke(Request $request)
    {
        ray($request->all());

        $reports = self::query(self::columns([
            'users.id',
            'users.name',
            'users.currency',
            'users.type as userType',
            'users.condition'
        ]), true)
            ->paginate(25)
            ->through($this->mapReports());

        // info($reports->sum('uplineProfits.winLose.value'));
        

        $total = self::query(self::columns())
            ->get()
            ->map(function ($item) use ($request) {

                $currency = Currency::fromKey($request->user()->currency);

                if ($request->user()->type->is(UserType::COMPANY)) {
                    $currency = Currency::fromKey(Currency::KHR);
                }

                return [
                    'bet_amount' => [
                        'value' => fromKHRtoCurrency($item->bet_amount, $currency),
                        'label' => priceFormat(fromKHRtoCurrency($item->bet_amount, $currency), $currency),
                    ],
                    'win_lose' => [
                        'value' => fromKHRtoCurrency($item->win_lose, $currency),
                        'label' => priceFormat(fromKHRtoCurrency($item->win_lose, $currency), $currency)
                    ]
                ];
            })
            ->first();

        $preventUserId = null;

        if (request('userId')) {
            $user = User::findOrFail(request('userId'));
            $preventUserId = $this->preventUserId($user);
        }

        $envId = user()->environment_id;

        if (user()->isCompany() || user()->isRoot()) {
            $envId = null;
        }

        return response()
            ->json([
                'reports' => $reports,
                'total' => $total,
                'preventUserId' => $preventUserId,
                'groups' => Group::select(['id', 'name'])->when($envId, fn($query) => $query->where('environment_id', $envId))->get()
            ], 200);
    }

    protected function mapReports(): Closure
    {
        return function ($item) {

            $currency = Currency::fromKey($item->currency);

            $condition = user()->condition;

            $winLose = $item->win_lose;
        
            if($item->userType == 'agent') {
                $user = User::find($item->id);
                
                $reports = self::query(self::columns([
                    'users.id',
                    'users.name',
                    'users.currency',
                    'users.type as userType',
                    'users.condition'
                ]), true, $user)
                    ->paginate(25)
                    ->through($this->mapAgentReports($user->condition));

                $winLose = $reports->sum('uplineProfits.winLose.value');
            }
            
            $profitWinLose = $winLose * (int) $condition['down_line_share'] / 100;  
            
            $uplineProfitWinLose =  $winLose * (100 - $condition['down_line_share']) / 100; 
            
            return [
                'id' => $item->id,
                'name' => $item->name,
                'currency' => $item->currency,
                'userType' => $item->userType,
                'betAmount' => [
                    'value' => fromKHRtoCurrency($item->bet_amount, $currency),
                    'label' => priceFormat(fromKHRtoCurrency($item->bet_amount, $currency), $currency),
                ],
                'members' => [
                    'winLose' => [
                        'value' => fromKHRtoCurrency($winLose, $currency),
                        'label' => priceFormat(fromKHRtoCurrency($winLose, $currency), $currency)
                    ],
                    'com' => 0,
                    'winLoseAndCom' => 0 
                ],
                'profits' => [
                    'winLose' => [
                        'value' => fromKHRtoCurrency($profitWinLose, $currency),
                        'label' => priceFormat(fromKHRtoCurrency($profitWinLose, $currency), $currency)
                    ],
                    'com' => 0,
                    'winLoseAndCom' => 0
                ],
                'uplineProfits' => [
                    'winLose' => [
                        'value' => fromKHRtoCurrency($uplineProfitWinLose, $currency),
                        'label' => priceFormat(fromKHRtoCurrency($uplineProfitWinLose, $currency), $currency)
                    ],
                    'com' => 0,
                    'winLoseAndCom' => 0
                ]
            ];

        };
    }

    protected function mapAgentReports($condition): Closure
    {
        return function ($item) use ($condition) {

            $currency = Currency::fromKey($item->currency);

            $winLose = $item->win_lose;
        
            $profitWinLose = $winLose * (int) $condition['down_line_share'] / 100;  
            
            $uplineProfitWinLose =  $winLose * (100 - $condition['down_line_share']) / 100; 
            
            return [
                'id' => $item->id,
                'name' => $item->name,
                'currency' => $item->currency,
                'userType' => $item->userType,
                'betAmount' => [
                    'value' => fromKHRtoCurrency($item->bet_amount, $currency),
                    'label' => priceFormat(fromKHRtoCurrency($item->bet_amount, $currency), $currency),
                ],
                'members' => [
                    'winLose' => [
                        'value' => fromKHRtoCurrency($winLose, $currency),
                        'label' => priceFormat(fromKHRtoCurrency($winLose, $currency), $currency)
                    ],
                    'com' => 0,
                    'winLoseAndCom' => 0 
                ],
                'profits' => [
                    'winLose' => [
                        'value' => fromKHRtoCurrency($profitWinLose, $currency),
                        'label' => priceFormat(fromKHRtoCurrency($profitWinLose, $currency), $currency)
                    ],
                    'com' => 0,
                    'winLoseAndCom' => 0
                ],
                'uplineProfits' => [
                    'winLose' => [
                        'value' => $uplineProfitWinLose,
                        'label' => priceFormat(fromKHRtoCurrency($uplineProfitWinLose, $currency), $currency)
                    ],
                    'com' => 0,
                    'winLoseAndCom' => 0
                ]
            ];

        };
    }

    protected static function query(array $select, bool $groupBy = false, $user = null)
    {
        $userId = request('userId') ?? null;
        
        $currentUser = $user ?? request()->user();
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
            ->withoutLiveMatch()
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
                    $query->whereDate($column, Date::today()->format('Y-m-d'));
                }

                if ($date === 'yesterday') {
                    $query->whereDate($column, Date::today()->subDay()->format('Y-m-d'));
                }

                if ($date === 'this-week') {

                    $query->whereBetween($column, [
                        now()->startOfWeek()->format('Y-m-d'),
                        now()->endOfWeek()->format('Y-m-d')
                    ]);
                }

                if ($date === 'last-week') {

                    $query->whereBetween($column, [
                        now()->subWeek()->startOfWeek()->format('Y-m-d'),
                        now()->subWeek()->endOfWeek()->format('Y-m-d')
                    ]);
                }

                if ($date === 'current-month') {

                    $query->whereMonth($column, date('m'))
                        ->whereYear($column, date('Y'));
                }

                if ($date === 'last-month') {

                    $query->whereBetween($column, [
                        now()->subMonth()->startOfMonth()->format('Y-m-d'),
                        now()->subMonth()->endOfMonth()->format('Y-m-d')
                    ]);
                }

            });

        if ($groupBy) {
            $query = $query->groupBy("bet_records.{$underUserType}");
        }


        return $query;
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

    protected function preventUserId(User $user): int|null
    {
        $userId = user()->id;
        $userType = (string)$user->type;

        if (user()->type->is(UserType::SUB_ACCOUNT)) {
            $parent = user()->getParent();
            $userId = $parent['id'];
            $userType = (string)$parent['type'];
        }

        $types = [
            UserType::SENIOR => UserType::SUPER_SENIOR,
            UserType::MASTER_AGENT => UserType::SENIOR,
            UserType::AGENT => UserType::MASTER_AGENT,
        ];

        return Arr::exists($types, $userType)
        && $user->{$types[$userType]} != $userId
            ? $user->{$types[$userType]}
            : null;
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

    protected function trasformBalance(float|null $balance): array
    {
        return [
            'label' => priceFormat($balance ?? 0, ''),
            'value' => $balance ?? 0
        ];
    }

    protected function getValueFromArray(string $key, array $array, float $default = 0): float
    {
        return Arr::exists($array, $key) ? (float) $array[$key] : $default;
    }
}
