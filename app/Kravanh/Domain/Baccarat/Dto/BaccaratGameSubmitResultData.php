<?php

namespace App\Kravanh\Domain\Baccarat\Dto;

use App\Kravanh\Domain\Baccarat\Exceptions\BaccaratCardException;
use App\Kravanh\Domain\Baccarat\Exceptions\BaccaratGameUnauthorizedToSubmitGameResultException;
use App\Kravanh\Domain\Baccarat\Support\BaccaratCard;
use App\Kravanh\Domain\Baccarat\Support\BaccaratGameWinner;
use App\Models\User;
use Illuminate\Http\Request;
use Throwable;

final class BaccaratGameSubmitResultData
{
    public BaccaratCard $playerCard;

    public BaccaratCard $bankerCard;

    /**
     * @throws BaccaratCardException
     */
    public function __construct(
        public readonly User $user,
        public readonly int $baccaratGameId,
        public readonly int $playerResult,
        public readonly string $playerType,
        public readonly int $bankerResult,
        public readonly string $bankerType
    ) {
        $this->playerCard = BaccaratCard::make($this->playerType, $this->playerResult);
        $this->bankerCard = BaccaratCard::make($this->bankerType, $this->bankerResult);

    }

    /**
     * @throws Throwable
     */
    public static function make(
        User $user,
        int $baccaratGameId,
        int $playerResult,
        string $playerType,
        int $bankerResult,
        string $bankerType): BaccaratGameSubmitResultData
    {

        throw_if(
            condition: ! $user->isTraderBaccarat(),
            exception: BaccaratGameUnauthorizedToSubmitGameResultException::class
        );

        return new BaccaratGameSubmitResultData(
            user: $user,
            baccaratGameId: $baccaratGameId,
            playerResult: $playerResult,
            playerType: $playerType,
            bankerResult: $bankerResult,
            bankerType: $bankerType
        );
    }

    /**
     * @throws Throwable
     */
    public static function fromRequest(Request $request): BaccaratGameSubmitResultData
    {
        $request->validate([
            'playerResult' => ['required', 'int'],
            'playerType' => ['required', 'string'],
            'bankerResult' => ['required', 'int'],
            'bankerType' => ['required', 'string'],
            'gameId' => ['required', 'int'],
        ]);

        return BaccaratGameSubmitResultData::make(
            user: $request->user(),
            baccaratGameId: $request->get('gameId'),
            playerResult: $request->get('playerResult'),
            playerType: $request->get('playerType'),
            bankerResult: $request->get('bankerResult'),
            bankerType: $request->get('bankerType')
        );
    }

    public function winner(): string
    {
        if ($this->playerResult === $this->bankerResult) {
            return BaccaratGameWinner::Tie;
        }

        if ($this->playerResult > $this->bankerResult) {
            return BaccaratGameWinner::Player;
        }

        return BaccaratGameWinner::Banker;

    }
}
