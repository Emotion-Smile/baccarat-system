<?php

namespace App\Kravanh\Domain\DragonTiger\Actions;

use App\Kravanh\Domain\DragonTiger\Dto\DragonTigerGameCreateTicketData;
use App\Kravanh\Domain\DragonTiger\Dto\DragonTigerGameMemberBetData;
use App\Kravanh\Domain\DragonTiger\Exceptions\DragonTigerGameBetConditionException;
use App\Kravanh\Domain\DragonTiger\Support\Balance;
use Throwable;

final class DragonTigerGameCreateTicketManagerAction
{
    /**
     * @throws DragonTigerGameBetConditionException
     * @throws Throwable
     */
    public function __invoke(DragonTigerGameMemberBetData $data): int
    {
        //https://kirschbaumdevelopment.com/insights/build-sequences-with-laravel-pipelines#:~:text=Laravel%20pipelines%20are%20a%20powerful,addition%20to%20any%20Laravel%20project.
        DragonTigerGameBetValidatorAction::validate($data);

        $ticket = DragonTigerGameCreateTicketAction::from(
            data: DragonTigerGameCreateTicketData::make($data)
        );

        //        DragonTigerTicketCreated::dispatch(
//            $betData->game->game_table_id,
//            []
//        );

        return DragonTigerGameMemberBettingWithdrawBalanceAction::from(
            member: $data->member,
            ticket: $ticket,
            game: $data->game
        );

    }

    /**
     * @throws Throwable
     * @throws DragonTigerGameBetConditionException
     */
    public static function from(DragonTigerGameMemberBetData $data): int
    {
        return (new DragonTigerGameCreateTicketManagerAction())($data);
    }

    /**
     * @throws Throwable
     * @throws DragonTigerGameBetConditionException
     */
    public static function withBalanceFormat(DragonTigerGameMemberBetData $data): string
    {
        return Balance::format(
            amount: DragonTigerGameCreateTicketManagerAction::from($data),
            currency: $data->member->currency
        );
    }
}
