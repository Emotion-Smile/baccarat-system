<?php

namespace KravanhEco\Report\Http\Controllers\Payment;

use App\Kravanh\Domain\User\Models\Agent;
use App\Kravanh\Domain\User\Models\Company;
use App\Kravanh\Domain\User\Models\MasterAgent;
use App\Kravanh\Domain\User\Models\Member;
use App\Kravanh\Domain\User\Models\Senior;
use App\Kravanh\Domain\User\Models\SuperSenior;
use App\Kravanh\Domain\User\Supports\Enums\UserType;
use App\Models\User;
use Closure;
use KravanhEco\Report\Models\Transaction;
use KravanhEco\Report\Models\Transfer;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;

class PaymentReportController
{
    public function __invoke(User $subUser = null): JsonResponse
    {
        $currentUser = user();
        $type = $currentUser->type;

        if ($currentUser->type->is(UserType::SUB_ACCOUNT)) {
            $parent = $currentUser->getParent();
            $userId = (int)$parent['id'];
            if ($parent['type'] === UserType::COMPANY) {
                $type = UserType::COMPANY;
            } else {
                $currentUser = User::find($userId);
            }

        }

        $user = $subUser ?? $currentUser;
        $currency = $user->currency;

        if (!in_array($type, exceptUserType()) && $subUser) $this->canAccess($currentUser, $user);

        if(in_array($user->type, exceptUserType())) {
            $payments = $this->paymentsForCompany();

            $totalDeposit = $this->totalPaymentsForCompany('deposit');
            $totalWithdraw = $this->totalPaymentsForCompany('withdraw');
        } else {
            $payments = $this->payments($user);

            $totalDeposit = $this->totalPayments($user, 'withdraw');
            $totalWithdraw = $this->totalPayments($user, 'deposit');

        }

        return response()->json([
            'viewAs' => $user->name,
            'payments' => $payments,
            'totalDeposit' => priceFormat(fromKHRtoCurrency($totalDeposit, $currency), $currency),
            'totalWithdraw' => priceFormat(fromKHRtoCurrency($totalWithdraw, $currency), $currency),
            'preventUserId' => $this->preventUserId($user)
        ], 200);
    }

    protected function queryPaymentForCompany()
    {
        return Transaction::query()
            ->with('payable:id,name,currency')
            ->select('transactions.*')
            ->leftJoin('transfers', function ($leftJoin) {
                $leftJoin->whereRaw(
                    '(
                        CASE
                            WHEN transactions.type = "deposit" THEN transactions.id = transfers.deposit_id
                            WHEN transactions.type = "withdraw" THEN transactions.id = transfers.withdraw_id
                        END
                    )');
            })
            ->where([
                'transfers.id' => null,
                'transactions.payable_type' => SuperSenior::class,
            ])
            ->when(request('search') ?? null, function (Builder $query, string $search) {
                $query->whereHas('payable', function (Builder $query) use ($search) {
                    $query->where('name', 'like', "%{$search}%");
                });
            })
            ->filterByPeriod('transactions.created_at')
            ->filterByDateRage('transactions.created_at');
    }

    protected function paymentsForCompany(): LengthAwarePaginator
    {
        return $this->queryPaymentForCompany() 
            ->orderByDesc('transactions.id')
            ->paginate(perPage: request('perPage') ?? 10)
            ->through($this->mapPaymentForCompany());
    }

    protected function totalPaymentsForCompany(string $type)
    {
        return $this->queryPaymentForCompany() 
            ->where('transactions.type', $type)
            ->sum('transactions.amount');
    }

    protected function mapPaymentForCompany(): Closure
    {
        return function ($transaction) {

            $isMember = $transaction->payable?->type == UserType::MEMBER;
            $beforeBalance = takeValueIfKeyExist($transaction->meta, 'before_balance') ?? 0;
            $currentBalance = takeValueIfKeyExist($transaction->meta, 'current_balance') ?? 0;
            $remark = takeValueIfKeyExist($transaction->meta, 'remark') ?? '-';
            $ip = takeValueIfKeyExist($transaction->meta, 'ip') ?? '-';

            $currency = $transaction->payable?->currency;

            $beforeBalance = fromKHRtoCurrency($beforeBalance, $currency);
            $currentBalance = fromKHRtoCurrency($currentBalance, $currency);
            $amount = fromKHRtoCurrency($transaction->amount, $currency);

            return [
                'id' => $transaction->id,
                'userId' => $transaction->payable?->id,
                'userName' => $transaction->payable?->name,
                'beforeBalance' => $this->trasformBalance($beforeBalance, $currency),
                'amount' => $this->trasformBalance($amount, $currency),
                'currentBalance' => $this->trasformBalance($currentBalance, $currency),
                'date' => $transaction->created_at->format('d-m-Y'),
                'time' => $transaction->created_at->format('H:i:s'),
                'statement' => $transaction->type,
                'remark' => $remark,
                'isMember' => $isMember,
                'ip' => $ip
            ];
        };
    }

    protected function queryPayment(User $user)
    {
        return Transfer::query()
            ->with([
                'to:id,name,type', 
                'from.holder:id,name,type', 
                'deposit.payable:id,currency', 
                'withdraw.payable:id,currency'
            ])
            ->where(function (Builder $query) use ($user) {
                $query->where(function (Builder $query) use ($user) {
                    $query->whereHas('from', function (Builder $query) use ($user) {
                        $query->whereHolderType($this->subHolderType($user->type));
                    })
                        ->whereToId($user->id)
                        ->whereToType($this->toType($user->type));
                })
                ->orWhere(function (Builder $query) use ($user) {
                    $query->whereHas('from', function (Builder $query) use ($user) {
                        $query->whereHolderId($user->id);
                    })
                        ->where('to_type', '<>', $this->parentToType($user->type));
                });
            })
            ->when(request('search') ?? null, function (Builder $query, string $search) use ($user) {
                $query->where(function (Builder $query) use ($search, $user) {
                    $query->whereHas('to', function (Builder $query) use ($search, $user) {
                        $query->where('name', 'like', "%{$search}%")
                            ->where('id', '<>', $user->id);
                    })
                        ->orWhereHas('from', function (Builder $query) use ($search, $user) {
                            $query->whereHas('holder', function (Builder $query) use ($search, $user) {
                                $query->where('name', 'like', "%{$search}%")
                                    ->where('id', '<>', $user->id);
                            });
                        });
                });
            })
            ->filterByPeriod('created_at')
            ->filterByDateRage('created_at');
    }

    protected function mapPayment(User $user): Closure
    {
        return function ($payment) use ($user) {
            $to = $payment->to;
            $transaction = $payment->deposit;

            if ($to?->type->is($user->type)) {
                $to = $payment->from?->holder;
                $transaction = $payment->withdraw;
            }

            $beforeBalance = takeValueIfKeyExist($transaction->meta, 'before_balance') ?? 0;
            $currentBalance = takeValueIfKeyExist($transaction->meta, 'current_balance') ?? 0;
            $remark = takeValueIfKeyExist($transaction->meta, 'remark') ?? '-';
            $ip = takeValueIfKeyExist($transaction->meta, 'ip') ?? '-';

            $currency = $transaction->payable?->currency;
        
            $beforeBalance = fromKHRtoCurrency($beforeBalance, $currency);
            $currentBalance = fromKHRtoCurrency($currentBalance, $currency);
            $amount = fromKHRtoCurrency($transaction->amount, $currency);

            $isMember = $to?->type == UserType::MEMBER;

            return [
                'id' => $payment->id,
                'userId' => $to?->id,
                'userName' => $to?->name,
                'beforeBalance' => $this->trasformBalance($beforeBalance, $currency),
                'amount' => $this->trasformBalance($amount, $currency),
                'currentBalance' => $this->trasformBalance($currentBalance, $currency),
                'date' => $transaction->created_at->format('d-m-Y'),
                'time' => $transaction->created_at->format('H:i:s'),
                'statement' => $transaction->type,
                'remark' => $remark,
                'isMember' => $isMember,
                'ip' => $ip
            ];
        };
    }

    protected function payments(User $user): LengthAwarePaginator
    {
        return $this->queryPayment($user) 
            ->orderByDesc('id')
            ->paginate(perPage: request('perPage') ?? 10)
            ->through($this->mapPayment($user));
    }

    protected function totalPayments(User $user, string $type)
    {
        $load = $type === 'withdraw' ? 'withdraw.to:id,name' : 'deposit.from.holder:id,name,type';

        $totalPayment = Transaction::query()
            ->with([
                'wallet:id,holder_id,holder_type',
                $load
            ])
            ->has($type)
            ->where('type', $type)
            ->whereHas('wallet', function (Builder $query) use ($user) {
                $query->where([
                    'holder_type' => $this->toType($user->type->value),
                    'holder_id' => $user->id
                ]);
            })
            ->when($type === 'withdraw', function (Builder $query) use ($user) {
                $query->whereHas('withdraw', function (Builder $query) use ($user) {
                    $query->whereHas('to', function (Builder $query) use ($user) {
                        $query->when(request('search') ?? null, function (Builder $query, string $search) {
                            $query->where('name', 'like', "%{$search}%");
                        })
                            ->where('type', '<>', $this->parentToStringType($user->type->value));;
                    });
                });
            }, function (Builder $query) use ($user) {
                $query->whereHas('deposit', function (Builder $query) use ($user) {
                    $query->whereHas('from', function (Builder $query) use ($user) {
                        $query->whereHas('holder', function (Builder $query) use ($user) {
                            $query->when(request('search') ?? null, function (Builder $query, string $search) {
                                $query->where('name', 'like', "%{$search}%");
                            })
                                ->where('type', '<>', $this->parentToStringType($user->type->value));
                        });
                    });
                });
            })
            ->filterByPeriod('created_at')
            ->filterByDateRage('created_at')
            ->sum('amount');

        return abs($totalPayment);
    }

    protected function subHolderType(string $userType): string
    {
        $types = [
            UserType::COMPANY => SuperSenior::class,
            UserType::SUPER_SENIOR => Senior::class,
            UserType::SENIOR => MasterAgent::class,
            UserType::MASTER_AGENT => Agent::class,
            UserType::AGENT => Member::class,
        ];

        return Arr::exists($types, $userType)
            ? $types[$userType]
            : SuperSenior::class;
    }

    protected function toType(string $userType): string
    {
        $types = [
            UserType::COMPANY => Company::class,
            UserType::SUPER_SENIOR => SuperSenior::class,
            UserType::SENIOR => Senior::class,
            UserType::MASTER_AGENT => MasterAgent::class,
            UserType::AGENT => Agent::class,
        ];

        return Arr::exists($types, $userType)
            ? $types[$userType]
            : SuperSenior::class;
    }

    protected function parentToType(string $userType): string
    {
        $types = [
            UserType::SUPER_SENIOR => Company::class,
            UserType::SENIOR => SuperSenior::class,
            UserType::MASTER_AGENT => Senior::class,
            UserType::AGENT => MasterAgent::class,
        ];

        return Arr::exists($types, $userType)
            ? $types[$userType]
            : Company::class;
    }

    protected function parentToStringType(string $userType): string
    {
        $types = [
            UserType::SUPER_SENIOR => UserType::COMPANY,
            UserType::SENIOR => UserType::SUPER_SENIOR,
            UserType::MASTER_AGENT => UserType::SENIOR,
            UserType::AGENT => UserType::MASTER_AGENT,
        ];

        return Arr::exists($types, $userType)
            ? $types[$userType]
            : UserType::COMPANY;
    }

    protected function preventUserId(User $user): int|null
    {
        $userId = user()->id;
        $userType = (string)$user->type;

        if (user()->type->is(UserType::SUB_ACCOUNT)) {
            $parent = user()->getParent();
            $userId = (int)$parent['id'];
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

    protected function canAccess(User $currentUser, User $subUser): void
    {
        if ($currentUser->id != $subUser->{$currentUser->type}) abort(403);
    }

    protected function trasformBalance(float|null $balance, $currency = null): array
    {
        return [
            'label' => priceFormat($balance ?? 0, $currency ?? ''),
            'value' => $balance ?? 0
        ];
    }
}
