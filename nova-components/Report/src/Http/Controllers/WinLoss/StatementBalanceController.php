<?php

namespace KravanhEco\Report\Http\Controllers\WinLoss;

use App\Kravanh\Domain\Environment\Models\Group;
use App\Kravanh\Domain\Match\Models\Matches;
use App\Kravanh\Domain\User\Models\Member;
use App\Kravanh\Domain\User\Supports\Enums\UserType;
use App\Models\User;
use Bavix\Wallet\Models\Transaction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Str;

class StatementBalanceController
{
    public function __invoke(Member $member): JsonResponse
    {
        return response()->json([
            'currentBalance' => priceFormat($member->getCurrentBalance(), $member->currency),
            'statementBalances' => $this->transactions($member->id),
            'preventUserId' => $this->getDetailPreviousUser($member)?->id,
        ], 200);
    }

    protected function getDetailPreviousUser(User $member): ?User
    {
        $userType = user()->type->value;

        if(user()->isSubAccount()) {
            $userType = user()->getEnsureType(); 
        }
    
        return $userType !== UserType::AGENT
            ? User::find($member->agent)
            : null;
    }

    public function transactions(int $memberId): LengthAwarePaginator
    {
        return Transaction::query()
            ->with('payable')
            ->where('payable_type', Member::class)
            ->where('payable_id', $memberId)
            ->when(request('from') && request('to'), function (Builder $query) {
                $from = request('from');
                $to = request('to');

                if ($from === $to) {
                    $query->whereDate('created_at', $from);
                } else {
                    // $query->whereBetween('created_at', [
                    //     request('from'),
                    //     request('to')
                    // ]);

                    $query->whereDate('created_at', '>=', $from)
                        ->whereDate('created_at', '<=', $to);
                }
            })
            ->when(request('date') ?? null, function (Builder $query, string $date) {
                $column = 'created_at';

                if ($date === 'today') {
                    $query->whereDate($column, Date::today()->format('Y-m-d'));
                }

                if ($date === 'yesterday') {
                    $query->whereDate($column, Date::today()->subDay()->format('Y-m-d'));
                }

                if ($date === 'this-week') {
                    // $query->whereBetween($column, [
                    //     now()->startOfWeek()->format('Y-m-d'),
                    //     now()->endOfWeek()->format('Y-m-d')
                    // ]);

                    $query->whereDate($column, '>=', now()->startOfWeek()->format('Y-m-d'))
                        ->whereDate($column, '<=', now()->endOfWeek()->format('Y-m-d'));
                }

                if ($date === 'last-week') {

                    // $query->whereBetween($column, [
                    //     now()->subWeek()->startOfWeek()->format('Y-m-d'),
                    //     now()->subWeek()->endOfWeek()->format('Y-m-d')
                    // ]);

                    $query->whereDate($column, '>=', now()->subWeek()->startOfWeek()->format('Y-m-d'))
                        ->whereDate($column, '<=', now()->subWeek()->endOfWeek()->format('Y-m-d'));
                }

                if ($date === 'current-month') {

                    $query->whereMonth($column, date('m'))
                        ->whereYear($column, date('Y'));
                }

                if ($date === 'last-month') {

                    // $query->whereBetween($column, [
                    //     now()->subMonth()->startOfMonth()->format('Y-m-d'),
                    //     now()->subMonth()->endOfMonth()->format('Y-m-d')
                    // ]);

                    $query->whereDate($column, '>=', now()->subMonthNoOverflow()->startOfMonth()->format('Y-m-d'))
                        ->whereDate($column, '<=', now()->subMonthNoOverflow()->endOfMonth()->format('Y-m-d'));
                }
            })
            ->orderByDesc('id')
            ->paginate(perPage: 10)
            ->through($this->makeTransactions());
    }

    protected function makeTransactions(): \Closure
    {
        return function ($transaction) {
            $metaType = $transaction->meta['type'] ?? '';

            $status = match ($metaType) {
                'bet' => 'bet',
                'payout' => 'payout',
                'refund' => 'refund',
                default => $transaction->type
            };

            $beforeBalance = $transaction->meta['before_balance'] ?? 0;
            $currentBalance = $transaction->meta['current_balance'] ?? 0;

            $currency = $transaction->payable?->currency;
            $beforeBalance = fromKHRtoCurrency($beforeBalance, $currency);
            $currentBalance = fromKHRtoCurrency($currentBalance, $currency);
            $amount = fromKHRtoCurrency($transaction->amount, $currency);

            $game = $transaction->meta['game'] ?? 'cock_fight';
            $matchId = $transaction->meta['match_id'] ?? null;

            $match = null;
            $group = null;

            if (in_array($metaType, ['deposit', 'withdraw'])) {
                $game = null;
            }

            if ($game === 'cock_fight' && $matchId) {
                $match = Cache::remember(
                    'match:bl:' . $matchId,
                    now()->addSeconds(10),
                    function () use ($matchId) {
                        return Matches::select(['id', 'group_id', 'fight_number'])
                            ->find($matchId);
                    }
                );

                $group = Cache::remember(
                    'match:group:' . $match->group_id,
                    now()->addSeconds(10),
                    function () use ($match) {
                        return Group::select(['id', 'name'])->find($match->group_id);
                    }
                );
            }

            $fightNumber = $match?->fight_number ?? $transaction->meta['fight_number'] ?? '';
            $groupName = $group?->name ?? '';

            return [
                'id' => $transaction->id,
                'username' => $transaction->payable?->name,
                'before_balance' => priceFormat($beforeBalance, $currency),
                'transaction_amount' => $transaction->amount,
                'current_balance' => priceFormat($currentBalance, $currency),
                'date' => $transaction->created_at->format('d-m-Y'),
                'time' => $transaction->created_at->format(config('kravanh.time_format')),
                'status' => $status,
                'fight_number' => $fightNumber,
                'group' => $groupName,
                'game' => Str::of($game)->headline(),
                'bet_id' => $transaction->meta['bet_id'] ?? '',
                'amount' => priceFormat($amount, $currency),
                'note' => $transaction->meta['note'] ?? '',
                'meta' => $transaction->meta ?? ['type' => '-', 'fight_number' => '-', 'match_status' => '-']
            ];
        };
    }
}
