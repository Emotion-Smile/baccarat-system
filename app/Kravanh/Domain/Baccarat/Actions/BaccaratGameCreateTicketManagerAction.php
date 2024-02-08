<?php

namespace App\Kravanh\Domain\Baccarat\Actions;

use App\Kravanh\Domain\Baccarat\Dto\BaccaratGameCreateTicketData;
use App\Kravanh\Domain\Baccarat\Dto\BaccaratGameMemberBetData;
use App\Kravanh\Domain\Baccarat\Exceptions\BaccaratGameBetConditionException;
use App\Kravanh\Domain\Baccarat\Support\Balance;
use Throwable;

final class BaccaratGameCreateTicketManagerAction
{
    /**
     * @throws BaccaratGameBetConditionException
     * @throws Throwable
     */
    public function __invoke(BaccaratGameMemberBetData $data): int
    {
        //https://kirschbaumdevelopment.com/insights/build-sequences-with-laravel-pipelines#:~:text=Laravel%20pipelines%20are%20a%20powerful,addition%20to%20any%20Laravel%20project.
        BaccaratGameBetValidatorAction::validate($data);

        $ticket = BaccaratGameCreateTicketAction::from(
            data: BaccaratGameCreateTicketData::make($data)
        );

        //        BaccaratTicketCreated::dispatch(
//            $betData->game->game_table_id,
//            []
//        );

        return BaccaratGameMemberBettingWithdrawBalanceAction::from(
            member: $data->member,
            ticket: $ticket,
            game: $data->game
        );

    }

    /**
     * @throws Throwable
     * @throws BaccaratGameBetConditionException
     */
    public static function from(BaccaratGameMemberBetData $data): int
    {
        return (new BaccaratGameCreateTicketManagerAction())($data);
    }

    /**
     * @throws Throwable
     * @throws BaccaratGameBetConditionException
     */
    public static function withBalanceFormat(BaccaratGameMemberBetData $data): string
    {
        return Balance::format(
            amount: BaccaratGameCreateTicketManagerAction::from($data),
            currency: $data->member->currency
        );
    }
}
