<?php

namespace KravanhEco\Report\Http\Controllers\WinLoss;

use App\Kravanh\Domain\Environment\Models\Group;
use App\Kravanh\Domain\Match\Models\BetRecord;
use App\Kravanh\Domain\Match\Supports\Enums\BetOn;
use App\Kravanh\Domain\Match\Supports\Enums\BetStatus;
use App\Kravanh\Domain\Match\Supports\Enums\MatchResult;
use App\Kravanh\Domain\User\Supports\Enums\UserType;
use App\Kravanh\Support\Enums\Currency;
use Closure;
use Illuminate\Support\Arr;

class BetSummaryV2Controller
{
    public function __invoke()
    {
        $reports = $this->reportQuery();
        $total = $this->total($reports);

        return response()->json([
            'groups' => $this->groups(), 
            'preventUserId' => null,
            'reports' => $reports,
            'total' => $total,
        ], 200);
    }

    protected function reportQuery()
    {
        return BetRecord::query()
            ->with([
                'match:id,result', 
                '_super_senior:id,name,currency,type,phone,condition',
                '_senior:id,name,currency,type,phone,condition', 
                '_master_agent:id,name,currency,type,phone,condition', 
                '_agent:id,name,currency,type,phone,condition',
                'user:id,name,currency,type,phone,condition'
            ])
            ->when(!in_array(user()->type->value, exceptUserType()), function ($query) {
                $query->where(user()->type->value, user()->id);
            })
            ->when($this->hasGroup(), function($query) {
                $query->whereGroupId(request('group'));
            })
            ->whereHas('match', function($query) {
                $query->whereNotIn('result', [
                    MatchResult::CANCEL,
                    MatchResult::DRAW,
                    MatchResult::PENDING,
                    MatchResult::NONE
                ]);
            })
            ->whereStatus(BetStatus::ACCEPTED)
            ->whereBetween('bet_date', [
                now()->subWeek()->startOfWeek()->format('Y-m-d'),
                now()->subWeek()->endOfWeek()->format('Y-m-d')
            ])
            ->withoutLiveMatch()
            ->get()
            ->groupBy($this->underCurrentUserPerLevel(user()->type->value))
            ->map($this->mapReports(user(), $this->underCurrentUserPerLevel(user()->type->value)));
    }

    protected function mapReports($user, $currentLevel = 'user_id', $useCurrencyFormat = true): Closure
    {
        return function($item) use ($user, $currentLevel, $useCurrencyFormat) {
        
            if(!$user->type->is(UserType::AGENT)) {
                $relation = '_' . $this->underCurrentUserPerLevel(user()->type->value);
                
                $user = $item->first()->{$relation};
                
                $reports = $item->groupBy($user->type->value)
                    ->map($this->mapReports($user, $this->underCurrentUserPerLevel(user()->type->value), false));
                
                $winLose = $reports->sum('uplineProfits.winLose.value');
            } else {
                $winLose = $item->sum(function ($item) {
                    $betOn = BetOn::fromValue($item->bet_on);
                    $result = MatchResult::fromValue($item->match->result); 
                   
                    if($betOn->value == $result->value) return $item->payout;
                    
                    return $item->amount * -1;
                });
            }

            $condition = $user->condition;

            $currency = Currency::fromKey($user->currency);

            $downLineShare = Arr::exists($condition ?? [], 'down_line_share') 
                ? $condition['down_line_share']
                : 0;

            $profitWinLose = $winLose * (int) $downLineShare / 100;  
            
            $uplineProfitWinLose =  $winLose * (100 - $downLineShare) / 100;
            
            $betAmount = $item->sum('amount');

            if($currentLevel === 'user_id') $user = $item->first()->user;

            return [
                'id' => $user->id,
                'name' => $user->name,
                'currency' => $user->currency,
                'userType' => $user->type,
                'contact' => $user->phone,
                'betAmount' => [
                    'value' => $useCurrencyFormat ? fromKHRtoCurrency($betAmount, $currency) : $betAmount,
                    'label' => priceFormat(fromKHRtoCurrency($betAmount, $currency), $currency),
                ],
                'members' => [
                    'winLose' => [
                        'value' => $useCurrencyFormat ? fromKHRtoCurrency($winLose, $currency) : $winLose,
                        'label' => priceFormat(fromKHRtoCurrency($winLose, $currency), $currency)
                    ],
                    'com' => 0,
                    'winLoseAndCom' => 0 
                ],
                'profits' => [
                    'winLose' => [
                        'value' => $useCurrencyFormat ? fromKHRtoCurrency($profitWinLose, $currency) : $profitWinLose,
                        'label' => priceFormat(fromKHRtoCurrency($profitWinLose, $currency), $currency)
                    ],
                    'com' => 0,
                    'winLoseAndCom' => 0
                ],
                'uplineProfits' => [
                    'winLose' => [
                        'value' => $useCurrencyFormat ? fromKHRtoCurrency($uplineProfitWinLose, $currency) : $uplineProfitWinLose,
                        'label' => priceFormat(fromKHRtoCurrency($uplineProfitWinLose, $currency), $currency) 
                    ],
                    'com' => 0,
                    'winLoseAndCom' => 0
                ]
            ];

        };
    }

    protected function total($reports): array 
    {
        $currency = Currency::fromKey(user()->currency);

        $betAmount = $reports->sum('betAmount.value'); 
        $membersWinLose = $reports->sum('members.winLose.value');
        $profitsWinLose = $reports->sum('profits.winLose.value');
        $uplineProfitsWinLose = $reports->sum('uplineProfits.winLose.value');

        return [
            'betAmount' => [
                'value' => $betAmount,
                'label' => priceFormat($betAmount, $currency)
            ],
            'members' => [
                'winLose' => [
                    'value' => $membersWinLose,
                    'label' => priceFormat($membersWinLose, $currency)
                ],
                'com' => [
                    'value' => 0,
                    'label' => 0
                ],
                'winLoseAndCom' => [
                    'value' => 0,
                    'label' => 0
                ] 
            ],
            'profits' => [
                'winLose' => [
                    'value' => $profitsWinLose,
                    'label' => priceFormat($profitsWinLose, $currency)
                ],
                'com' => [
                    'value' => 0,
                    'label' => 0
                ],
                'winLoseAndCom' => [
                    'value' => 0,
                    'label' => 0
                ]
            ],
            'uplineProfits' => [
                'winLose' => [
                    'value' => $uplineProfitsWinLose,
                    'label' => priceFormat($uplineProfitsWinLose, $currency)
                ],
                'com' => [
                    'value' => 0,
                    'label' => 0
                ],
                'winLoseAndCom' => [
                    'value' => 0,
                    'label' => 0
                ]
            ]
        ];
    }

    protected function underCurrentUserPerLevel(string $userType): string
    {    
        return match($userType) {
            UserType::SUPER_SENIOR => UserType::SENIOR,
            UserType::SENIOR => UserType::MASTER_AGENT,
            UserType::MASTER_AGENT => UserType::AGENT,
            UserType::AGENT => 'user_id',
            default => UserType::SUPER_SENIOR
        };
    }

    protected function groups()
    {
        $envId = user()->isCompany() || user()->isRoot() 
            ?: user()->environment_id;

        return Group::select(['id', 'name'])
            ->when($envId, function ($query, $envId) {
                $query->where('environment_id', $envId);
            })
            ->get();
    }

    protected function hasGroup()
    {
        return ! (request('group') === null || request('group') === '0');
    }
}
