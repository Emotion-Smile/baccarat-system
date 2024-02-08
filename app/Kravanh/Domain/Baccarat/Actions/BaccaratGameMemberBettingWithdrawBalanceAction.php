<?php

namespace App\Kravanh\Domain\Baccarat\Actions;

use App\Kravanh\Domain\Baccarat\Dto\TransactionTicketBettingMetaData;
use App\Kravanh\Domain\Baccarat\Models\BaccaratGame;
use App\Kravanh\Domain\Baccarat\Models\BaccaratTicket;
use App\Kravanh\Domain\User\Models\Member;
use App\Kravanh\Support\External\Telegram\Telegram;
use App\Kravanh\Support\LockHelper;
use Exception;
use Throwable;

final class BaccaratGameMemberBettingWithdrawBalanceAction
{

    /**
     * @throws Throwable
     */
    public static function from(
        Member            $member,
        BaccaratTicket $ticket,
        BaccaratGame   $game
    ): int
    {
        return (new BaccaratGameMemberBettingWithdrawBalanceAction())(
            member: $member,
            ticket: $ticket,
            game: $game
        );
    }

    /**
     * @throws Throwable
     */
    public function __invoke(
        Member            $member,
        BaccaratTicket $ticket,
        BaccaratGame   $game,
    ): int
    {
        $locker = LockHelper::lockWallet($member->id);

        try {
            $locker->block(config('balance.waiting_time_in_sec'));

            $beforeBalance = $member->balanceInt;
            $currentBalance = $beforeBalance - $ticket->amount;

            $member->withdraw(
                amount: $ticket->amount,
                meta: TransactionTicketBettingMetaData::from(
                    game: $game,
                    ticket: $ticket,
                    member: $member,
                    beforeBalance: $beforeBalance,
                    currentBalance: $currentBalance
                )->toArray()
            );

            return $currentBalance;

        } catch (Throwable|Exception $exception) {

            $ticket->delete();

            Telegram::send()
                ->to('-792979466') // KD
                ->text(
                    message: "DT withdraw failed: $member->name,amount: $ticket->amount, " . $exception->getMessage()
                );

            throw $exception;

        } finally {
            $locker->release();
        }
    }
}
