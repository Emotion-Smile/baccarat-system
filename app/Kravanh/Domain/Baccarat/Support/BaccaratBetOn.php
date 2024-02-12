<?php

namespace App\Kravanh\Domain\Baccarat\Support;

use App\Kravanh\Domain\Baccarat\Exceptions\BaccaratGameBetOnInvalidException;
use App\Kravanh\Domain\Baccarat\Support\BaccaratCard as Card;

final class BaccaratBetOn
{
    const Player = Card::Player . '_' . Card::Player;
    const Banker = Card::Banker . '_' . Card::Banker;
    const Tie = Card::Tie . '_' . Card::Tie;

    const Big = Card::Big . '_' . Card::Big;
    const Small = Card::Small . '_' . Card::Small;
    const PlayerPair = Card::PlayerPair . '_' . Card::PlayerPair;
    const BankerPair = Card::BankerPair . '_' . Card::BankerPair;

    const options = [
        self::Player, self::PlayerPair,
        self::Banker, self::BankerPair,
        self::Big, self::Small,
        self::Tie
    ];

    public function __construct(
        public readonly string $betOn,
        public readonly string $betType)
    {

    }

    public static function make(string $betOn, string $betType): BaccaratBetOn
    {
        return (new BaccaratBetOn(betOn: $betOn, betType: $betType));
    }

    public function isValid(): bool
    {
        if (!in_array($this->bet(), self::options)) {
            return false;
        }
        return true;
    }

    /**
     * @throws BaccaratGameBetOnInvalidException
     */
    public function validate(): void
    {
        if (!in_array($this->bet(), self::options)) {
            throw new BaccaratGameBetOnInvalidException();
        }
    }

    public function bet(): string
    {
        return $this->betOn . '_' . $this->betType;
    }

    public function isBetTie(): bool
    {
        return self::Tie === $this->bet();
    }

    public function isBetOnPlayer(): bool
    {
        return self::Player === $this->bet();
    }

    public function isBetOnPlayerPair(): bool
    {
        return self::PlayerPair === $this->bet();
    }

    public function isBetOnBanker(): bool
    {
        return self::Banker === $this->bet();
    }

    public function isBetOnBankerPair(): bool
    {
        return self::BankerPair === $this->bet();
    }

    public function isBetOnBig(): bool
    {
        return self::Big === $this->bet();
    }

    public function isBetOnSmall(): bool
    {
        return self::Small === $this->bet();
    }

    public function isBetOnPlayerOrBanker(): bool
    {
        return $this->isBetOnPlayer() || $this->isBetOnBanker();
    }

    public function isBetOnBigOrSmall(): bool
    {
        return $this->isBetOnBig() || $this->isBetOnSmall();
    }

    public function isBetOnPlayerPairOrBankerPair(): bool
    {
        return $this->isBetOnPlayerPair() || $this->isBetOnBankerPair();
    }

}
