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
//        public readonly int $playerResult,
//        public readonly string $playerType,
//        public readonly int $bankerResult,
//        public readonly string $bankerType
        public readonly int $playerFirstCardValue,
        public readonly string $playerFirstCardType,
        public readonly int $playerSecondCardValue,
        public readonly string $playerSecondCardType,
        public readonly ?int $playerThirdCardValue,
        public readonly string $playerThirdCardType,
        public readonly int $playerPoints,
        public readonly int $bankerFirstCardValue,
        public readonly string $bankerFirstCardType,
        public readonly int $bankerSecondCardValue,
        public readonly string $bankerSecondCardType,
        public readonly ?int $bankerThirdCardValue,
        public readonly string $bankerThirdCardType,
        public readonly int $bankerPoints,
    ) {
//        $this->playerCard = BaccaratCard::make($this->playerType, $this->playerResult);
//        $this->bankerCard = BaccaratCard::make($this->bankerType, $this->bankerResult);
        $this->playerCard = BaccaratCard::make('player', $this->playerPoints);
        $this->bankerCard = BaccaratCard::make('banker', $this->bankerPoints);

    }

    /**
     * @throws Throwable
     */
    public static function make(
        User $user,
        int $baccaratGameId,
//        int $playerResult,
//        string $playerType,
//        int $bankerResult,
//        string $bankerType
        int $playerFirstCardValue,
        string $playerFirstCardType,
        int $playerSecondCardValue,
        string $playerSecondCardType,
        int $playerThirdCardValue,
        string $playerThirdCardType,
        int $playerPoints,
        int $bankerFirstCardValue,
        string $bankerFirstCardType,
        int $bankerSecondCardValue,
        string $bankerSecondCardType,
        int $bankerThirdCardValue,
        string $bankerThirdCardType,
        int $bankerPoints
    ): BaccaratGameSubmitResultData
    {

        throw_if(
            condition: ! $user->isTraderBaccarat(),
            exception: BaccaratGameUnauthorizedToSubmitGameResultException::class
        );

        return new BaccaratGameSubmitResultData(
            user: $user,
            baccaratGameId: $baccaratGameId,
            playerFirstCardValue: $playerFirstCardValue,
            playerFirstCardType: $playerFirstCardType,
            playerSecondCardValue: $playerSecondCardValue,
            playerSecondCardType: $playerSecondCardType,
            playerThirdCardValue: $playerThirdCardValue,
            playerThirdCardType: $playerThirdCardType,
            playerPoints: $playerPoints,
            bankerFirstCardValue: $bankerFirstCardValue,
            bankerFirstCardType: $bankerFirstCardType,
            bankerSecondCardValue: $bankerSecondCardValue,
            bankerSecondCardType: $bankerSecondCardType,
            bankerThirdCardValue: $bankerThirdCardValue,
            bankerThirdCardType: $bankerThirdCardType,
            bankerPoints: $bankerPoints
        );
    }

    /**
     * @throws Throwable
     */
    public static function fromRequest(Request $request): BaccaratGameSubmitResultData
    {
        $request->validate([
//            'playerResult' => ['required', 'int'],
//            'playerType' => ['required', 'string'],
//            'bankerResult' => ['required', 'int'],
//            'bankerType' => ['required', 'string'],
            'playerFirstCardValue' => ['required', 'int'],
            'playerFirstCardType' => ['required', 'string'],
            'playerSecondCardValue' => ['required', 'int'],
            'playerSecondCardType' => ['required', 'string'],
            'playerThirdCardValue' => ['required', 'int'],
            'playerThirdCardType' => ['required', 'string'],
            'playerPoints' => ['required', 'int'],

            'bankerFirstCardValue' => ['required', 'int'],
            'bankerFirstCardType' => ['required', 'string'],
            'bankerSecondCardValue' => ['required', 'int'],
            'bankerSecondCardType' => ['required', 'string'],
            'bankerThirdCardValue' => ['required', 'int'],
            'bankerThirdCardType' => ['required', 'string'],
            'bankerPoints' => ['required', 'int'],
            'gameId' => ['required', 'int'],
        ]);

        return BaccaratGameSubmitResultData::make(
            user: $request->user(),
            baccaratGameId: $request->get('gameId'),
            playerFirstCardValue: $request->get('playerFirstCardValue'),
            playerFirstCardType: $request->get('playerFirstCardType'),
            playerSecondCardValue: $request->get('playerSecondCardValue'),
            playerSecondCardType: $request->get('playerSecondCardType'),
            playerThirdCardValue: $request->get('playerThirdCardValue'),
            playerThirdCardType: $request->get('playerThirdCardType'),
            playerPoints: $request->get('playerPoints'),
            bankerFirstCardValue: $request->get('bankerFirstCardValue'),
            bankerFirstCardType: $request->get('bankerFirstCardType'),
            bankerSecondCardValue: $request->get('bankerSecondCardValue'),
            bankerSecondCardType: $request->get('bankerSecondCardType'),
            bankerThirdCardValue: $request->get('bankerThirdCardValue'),
            bankerThirdCardType: $request->get('bankerThirdCardType'),
            bankerPoints: $request->get('bankerPoints')
//            playerResult: $request->get('playerResult'),
//            playerType: $request->get('playerType'),
//            bankerResult: $request->get('bankerResult'),
//            bankerType: $request->get('bankerType')
        );
    }

    public function winner(): array //: string
    {
//        if ($this->playerPoints === $this->bankerPoints) {
//            return BaccaratGameWinner::Tie;
//        }
//
//        if ($this->playerPoints > $this->bankerPoints) {
//            return BaccaratGameWinner::Player;
//        }
//
//        return BaccaratGameWinner::Banker;

        $results = [];
        if ($this->playerPoints === $this->bankerPoints) {
            $results[] = BaccaratGameWinner::Tie;
        }

        if ($this->playerPoints > $this->bankerPoints) {
            $results[] = BaccaratGameWinner::Player;
        }

        if ($this->playerPoints < $this->bankerPoints) {
            $results[] = BaccaratGameWinner::Banker;
        }

        if ($this->playerThirdCardValue || $this->bankerThirdCardValue) {
            $results[] = BaccaratGameWinner::Big;
        }

        if (!$this->playerThirdCardValue || !$this->bankerThirdCardValue) {
            $results[] = BaccaratGameWinner::Small;
        }

        if ($this->playerFirstCardValue === $this->playerSecondCardValue) {
            $results[] = BaccaratGameWinner::PlayerPair;
        }

        if ($this->bankerFirstCardValue === $this->bankerSecondCardValue) {
            $results[] = BaccaratGameWinner::BankerPair;
        }
//        dd(implode(',', $results));
        return $results;
    }
}
