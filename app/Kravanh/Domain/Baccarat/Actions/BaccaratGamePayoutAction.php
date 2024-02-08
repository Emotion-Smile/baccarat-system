<?php

namespace App\Kravanh\Domain\Baccarat\Actions;

use App\Kravanh\Domain\Baccarat\Dto\BaccaratGameTransactionTicketPayoutMetaData;
use App\Kravanh\Domain\Baccarat\Models\BaccaratGame;
use App\Kravanh\Domain\User\Models\Member;
use App\Kravanh\Support\LockHelper;
use Bavix\Wallet\Models\Transaction;
use Exception;
use Illuminate\Support\Collection;
use Spatie\LaravelIgnition\Facades\Flare;

final class BaccaratGamePayoutAction
{
    public function __invoke(
        BaccaratGame $game,
        Collection $tickets
    ): Collection {
        $transactions = Collection::make();

        foreach ($tickets as $ticket) {
            try {
                $transactions->push($this->depositor($game, $ticket));
            } catch (Exception $e) {
                Flare::report(throwable: $e);
            }
        }

        return $transactions;
    }

    public function depositor(BaccaratGame $game, $ticket): Transaction
    {

        /**
         * @var Member $member
         */
        $member = $ticket->member;
        $locker = LockHelper::lockWallet($member->id);

        try {

            $locker->block(config('balance.waiting_time_in_sec'));

            $payout = $ticket->payout;
            $balanceBeforePayout = $member->balanceInt;

            $meta = BaccaratGameTransactionTicketPayoutMetaData::from(
                ticketIds: $ticket->ids,
                gameId: $game->id,
                amount: $payout,
                currency: $member->currency->value,
                beforeBalance: $balanceBeforePayout,
                currentBalance: $balanceBeforePayout + $payout,
                gameNumber: $game->gameNumber()
            );

            return $member->deposit(
                amount: $payout,
                meta: $meta->toMeta(),
            );

        } finally {
            $locker->release();
        }

    }
}
