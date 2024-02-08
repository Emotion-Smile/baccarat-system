<?php

namespace App\Kravanh\Domain\Baccarat\Dto;

use App\Kravanh\Domain\Baccarat\Actions\BaccaratGameGetLastRoundAction;
use App\Kravanh\Domain\Baccarat\Actions\BaccaratGameGetNextNumberInRoundAction;
use App\Kravanh\Domain\Baccarat\Exceptions\BaccaratGameUnauthorizedToCreateNewGameException;
use App\Kravanh\Domain\Baccarat\Support\RoundMode;
use App\Models\User;
use Illuminate\Http\Request;
use Throwable;

final class BaccaratGameCreateData
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
    public static function fromRequest(Request $request): BaccaratGameCreateData
    {
        return BaccaratGameCreateData::from(
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
        int $betIntervalInSecond = 60): BaccaratGameCreateData
    {
        throw_if(
            condition: ! $user->isTraderBaccarat(),
            exception: BaccaratGameUnauthorizedToCreateNewGameException::class
        );

        $round = (new BaccaratGameGetLastRoundAction())(
            gameTableId: $user->getGameTableId(),
            roundMode: $roundMode,
        );

        return new BaccaratGameCreateData(
            gameTableId: $user->getGameTableId(),
            userId: $user->id,
            round: $round,
            number: (new BaccaratGameGetNextNumberInRoundAction())(
                gameTableId: $user->getGameTableId(),
                round: $round
            ),
            betIntervalInSecond: $betIntervalInSecond
        );
    }
}
