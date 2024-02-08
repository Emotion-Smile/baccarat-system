<?php

namespace App\Kravanh\Domain\DragonTiger\Dto;

use App\Kravanh\Domain\DragonTiger\Exceptions\DragonTigerCardException;
use App\Kravanh\Domain\DragonTiger\Exceptions\DragonTigerGameUnauthorizedToSubmitGameResultException;
use App\Kravanh\Domain\DragonTiger\Support\DragonTigerCard;
use App\Kravanh\Domain\DragonTiger\Support\DragonTigerGameWinner;
use App\Models\User;
use Illuminate\Http\Request;
use Throwable;

final class DragonTigerGameSubmitResultData
{
    public DragonTigerCard $dragonCard;

    public DragonTigerCard $tigerCard;

    /**
     * @throws DragonTigerCardException
     */
    public function __construct(
        public readonly User $user,
        public readonly int $dragonTigerGameId,
        public readonly int $dragonResult,
        public readonly string $dragonType,
        public readonly int $tigerResult,
        public readonly string $tigerType
    ) {
        $this->dragonCard = DragonTigerCard::make($this->dragonType, $this->dragonResult);
        $this->tigerCard = DragonTigerCard::make($this->tigerType, $this->tigerResult);

    }

    /**
     * @throws Throwable
     */
    public static function make(
        User $user,
        int $dragonTigerGameId,
        int $dragonResult,
        string $dragonType,
        int $tigerResult,
        string $tigerType): DragonTigerGameSubmitResultData
    {

        throw_if(
            condition: ! $user->isTraderDragonTiger(),
            exception: DragonTigerGameUnauthorizedToSubmitGameResultException::class
        );

        return new DragonTigerGameSubmitResultData(
            user: $user,
            dragonTigerGameId: $dragonTigerGameId,
            dragonResult: $dragonResult,
            dragonType: $dragonType,
            tigerResult: $tigerResult,
            tigerType: $tigerType
        );
    }

    /**
     * @throws Throwable
     */
    public static function fromRequest(Request $request)
    {
        $request->validate([
            'dragonResult' => ['required', 'int'],
            'dragonType' => ['required', 'string'],
            'tigerResult' => ['required', 'int'],
            'tigerType' => ['required', 'string'],
            'gameId' => ['required', 'int'],
        ]);

        return DragonTigerGameSubmitResultData::make(
            user: $request->user(),
            dragonTigerGameId: $request->get('gameId'),
            dragonResult: $request->get('dragonResult'),
            dragonType: $request->get('dragonType'),
            tigerResult: $request->get('tigerResult'),
            tigerType: $request->get('tigerType')
        );
    }

    public function winner(): string
    {
        if ($this->dragonResult === $this->tigerResult) {
            return DragonTigerGameWinner::Tie;
        }

        if ($this->dragonResult > $this->tigerResult) {
            return DragonTigerGameWinner::Dragon;
        }

        return DragonTigerGameWinner::Tiger;

    }
}
