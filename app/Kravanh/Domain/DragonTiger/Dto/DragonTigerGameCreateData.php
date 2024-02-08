<?php

namespace App\Kravanh\Domain\DragonTiger\Dto;

use App\Kravanh\Domain\DragonTiger\Actions\DragonTigerGameGetLastRoundAction;
use App\Kravanh\Domain\DragonTiger\Actions\DragonTigerGameGetNextNumberInRoundAction;
use App\Kravanh\Domain\DragonTiger\Exceptions\DragonTigerGameUnauthorizedToCreateNewGameException;
use App\Kravanh\Domain\DragonTiger\Support\RoundMode;
use App\Models\User;
use Illuminate\Http\Request;
use Throwable;

final class DragonTigerGameCreateData
{
    public function __construct(
        public readonly int $gameTableId,
        public readonly int $userId,
        public readonly int $round,
        public readonly int $number,
        public readonly int $betIntervalInSecond
    ) {
    }

    /**
     * @throws Throwable
     */
    public static function fromRequest(Request $request): DragonTigerGameCreateData
    {
        return DragonTigerGameCreateData::from(
            user: $request->user(),
            roundMode: $request->get('roundMode', RoundMode::LastRound)
        );
    }

    /**
     * @throws Throwable
     */
    public static function from(
        User $user,
        string $roundMode = RoundMode::LastRound,
        int $betIntervalInSecond = 60): DragonTigerGameCreateData
    {
        throw_if(
            condition: ! $user->isTraderDragonTiger(),
            exception: DragonTigerGameUnauthorizedToCreateNewGameException::class
        );

        $round = (new DragonTigerGameGetLastRoundAction())(
            gameTableId: $user->getGameTableId(),
            roundMode: $roundMode,
        );

        return new DragonTigerGameCreateData(
            gameTableId: $user->getGameTableId(),
            userId: $user->id,
            round: $round,
            number: (new DragonTigerGameGetNextNumberInRoundAction())(
                gameTableId: $user->getGameTableId(),
                round: $round
            ),
            betIntervalInSecond: $betIntervalInSecond
        );
    }
}
