<?php

namespace App\Kravanh\Application\Test;

use App\Kravanh\Domain\User\Models\Member;
use App\Kravanh\Support\LockHelper;
use Illuminate\Contracts\Cache\LockTimeoutException;
use Illuminate\Http\Request;

class RaceConditionController
{
    public function withdraw(Request $request): array
    {
        
        $user = Member::find($request->id);

        $lock = LockHelper::lockWallet($request->id, sec: 20);

        try {
            $lock->block(15);

            $transaction = $user->withdraw($request->amount);
            $currentBalance = $user->balanceInt;

            $transaction->meta = [
                'before_balance' => $currentBalance + $request->amount,
                'current_balance' => $currentBalance,
            ];

            $transaction->saveQuietly();
        } finally {
            $lock->release();
        }

        return [
            'cacheBalance' => $user->balanceInt,
            'balance' => $user->wallet->getRawOriginal('balance'),
        ];
    }

    public function withdrawLockTimeoutException(Request $request): array
    {
        $user = Member::find($request->id);

        $this->recallWithdraw($user, $request->all());

        return [
            'cacheBalance' => $user->balanceInt,
            'balance' => $user->wallet->getRawOriginal('balance'),
        ];
    }

    /**
     * @throws LockTimeoutException
     * @throws \Throwable
     */
    protected function recallWithdraw(Member $user, array $request): void
    {
        $lock = LockHelper::lockWallet($request['id'], sec: 3);

        try {
            $lock->block(1);

            $transaction = $user->withdraw($request['amount']);
            $currentBalance = $user->balanceInt;

            $transaction->meta = [
                'before_balance' => $currentBalance + $request['amount'],
                'current_balance' => $currentBalance,
            ];

            $transaction->saveQuietly();
            sleep(1);
        } finally {
            $lock->release();
        }
    }

    public function deposit(Request $request): array
    {
        $user = Member::find($request->id);

        $lock = LockHelper::lockWallet($user->id, sec: 20);

        try {
            $lock->block(seconds: 15);

            $transaction = $user->deposit($request->amount);
            $currentBalance = $user->balanceInt;

            $transaction->meta = [
                'before_balance' => $currentBalance - $request->amount,
                'current_balance' => $currentBalance,
            ];

            $transaction->saveQuietly();
        } finally {
            $lock->release();
        }

        return [
            'cacheBalance' => $user->balanceInt,
            'balance' => $user->wallet->getRawOriginal('balance'),
        ];
    }
}
