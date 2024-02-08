<?php

namespace KravanhEco\Report\Http\Controllers\T88WinLose;

use App\Kravanh\Domain\Environment\Models\Group;
use App\Kravanh\Domain\Match\Models\Matches;
use App\Kravanh\Domain\User\Models\Member;
use Bavix\Wallet\Models\Transaction;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Str;
use KravanhEco\Report\Http\Requests\T88Request;

class BalanceStatementController
{
    public function __invoke(
        T88Request $request, 
        string $name
    ): JsonResponse
    {
        $member = Member::query()
            ->whereName($name)
            ->firstOrFail();

        $previousUser = $request->getDetailPreviousUser($member);

        return asJson([
            'previousUser' => $previousUser, 
            'balanceStatement' => $this->transactions($member->id),
            'currentBalance' => priceFormat(
                value: $member->getCurrentBalance(),
                prefix: $member->currency
            ),
        ]);
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
                    $query->whereDate($column, '>=', now()->startOfWeek()->format('Y-m-d'))
                        ->whereDate($column, '<=', now()->endOfWeek()->format('Y-m-d'));
                }

                if ($date === 'last-week') {
                    $query->whereDate($column, '>=', now()->subWeek()->startOfWeek()->format('Y-m-d'))
                        ->whereDate($column, '<=', now()->subWeek()->endOfWeek()->format('Y-m-d'));
                }

                if ($date === 'current-month') {
                    $query->whereMonth($column, date('m'))
                        ->whereYear($column, date('Y'));
                }

                if ($date === 'last-month') {
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
            
            if($fightNumber && $game === 'dragon_tiger') {
                $fightNumber = Str::replace('/', '_', $fightNumber);
            }

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
