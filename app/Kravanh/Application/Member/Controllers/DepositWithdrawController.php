<?php

namespace App\Kravanh\Application\Member\Controllers;

use App\Kravanh\Domain\User\Models\Member;
use Bavix\Wallet\Models\Transaction;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Date;
use Inertia\Inertia;
use Inertia\Response;

class DepositWithdrawController
{
    public function __invoke(): Response
    {
        return Inertia::render('Member/DepositWithDraw', [
            'filters' => [
                'status' => request('status')
            ],
            'transactionRecords' => $this->transactions(),
            'transactionTypeFilter' => $this->transactionTypeFilter()
        ]);
    }

    public function transactions(): LengthAwarePaginator
    {
        //all-transaction=true&per-page=50&exclude-payout=true
        $status = request('status') ?? null;
        $allTransaction = request('all-transaction');
        $perPage = request('per-page', 10);
        $date = request('date');

        if ($allTransaction) {
            $date = null;
        }

        return Transaction::query()
            ->with('payable:id,currency')
            ->where('payable_id', user()->id)
            ->where('payable_type', Member::class)
            ->when($status, function ($query) use ($status) {
                if ($status === 'deposit') {
                    $query->whereType($status);
                } else if ($status === 'withdraw') {
                    $query->whereType($status);
                }
            })
            ->when($date, function (Builder $query) use ($date) {

                if ($date === 'today') {
                    $query->whereDate('created_at', Date::today()->format('Y-m-d'));
                }

                if ($date === 'yesterday') {
                    $query->whereDate('created_at', Date::today()->subDay()->format('Y-m-d'));
                }

                if ($date === 'this-week') {

                    $query->whereBetween('created_at', [
                        now()->startOfWeek()->format('Y-m-d'),
                        now()->endOfWeek()->format('Y-m-d')
                    ]);
                }

                if ($date === 'last-week') {

                    $query->whereBetween('created_at', [
                        now()->subWeek()->startOfWeek()->format('Y-m-d'),
                        now()->subWeek()->endOfWeek()->format('Y-m-d')
                    ]);
                }

            })
            ->when(!$allTransaction, function (Builder $query) {
                $query->where('meta->type', '!=', 'bet')
                    ->where('meta->type', '!=', 'payout');
            })->when(request('exclude-payout'), function (Builder $query) {
                $query->where('meta->type', '!=', 'payout');
            })
            ->orderByDesc('id')
            ->paginate(perPage: $perPage)
            ->through($this->makeTransactions($allTransaction));
    }

    protected function transactionTypeFilter()
    {
        return [
            'deposit' => 'Deposit',
            'withdraw' => 'Withdraw',
        ];
    }

    protected function makeTransactions($allTransaction = null): \Closure
    {
        return function ($transaction) use ($allTransaction) {

            $metaType = $transaction->meta['type'] ?? '';

            $status = match ($metaType) {
                'bet' => 'bet',
                'payout' => 'payout',
                default => $transaction->type
            };

            $beforeBalance = $transaction->meta['before_balance'] ?? 0;
            $currentBalance = $transaction->meta['current_balance'] ?? 0;
            $walletBalance = 0;

            if (isset($transaction->meta['wallet_balance'])) {
                $walletBalance = $transaction->meta['wallet_balance'];
                $currentBalance = $walletBalance;
            }

            $currency = $transaction->payable?->currency;
            $beforeBalance = fromKHRtoCurrency($beforeBalance, $currency);
            $currentBalance = fromKHRtoCurrency($currentBalance, $currency);
            $amount = fromKHRtoCurrency($transaction->amount, $currency);

            return [
                'id' => $transaction->id,
                'show_all_transaction' => !is_null($allTransaction),
                'before_balance' => priceFormat($beforeBalance, ''),
                'transaction_amount' => $amount,
                'current_balance' => priceFormat($currentBalance, ''),
                'wallet_balance' => priceFormat($walletBalance, ''),
                'date' => $transaction->created_at->format('d-m-Y'),
                'time' => $transaction->created_at->format(config('kravanh.time_format')),
                'status' => $status,
                'note' => $transaction->meta['note'] ?? '',
                'fight_number' => $transaction->meta['fight_number'] ?? '',
                'bet_id' => $transaction->meta['bet_id'] ?? '',
                'amount' => priceFormat($amount, ''),
                'meta' => $transaction->meta ?? ['type' => '-', 'fight_number' => '-', 'match_status' => '-']
            ];
        };
    }
}
