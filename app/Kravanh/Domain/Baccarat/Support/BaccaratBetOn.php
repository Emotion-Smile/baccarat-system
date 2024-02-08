<?php

namespace App\Kravanh\Domain\Baccarat\Support;

use App\Kravanh\Domain\Baccarat\Exceptions\BaccaratGameBetOnInvalidException;
use App\Kravanh\Domain\Baccarat\Support\BaccaratCard as Card;

final class BaccaratBetOn
{
    const Dragon = Card::Dragon . '_' . Card::Dragon;
    const Tiger = Card::Tiger . '_' . Card::Tiger;
    const Tie = Card::Tie . '_' . Card::Tie;

    const DragonRed = Card::Dragon . '_' . Card::Red;
    const DragonBlack = Card::Dragon . '_' . Card::Black;
    const TigerRed = Card::Tiger . '_' . Card::Red;
    const TigerBlack = Card::Tiger . '_' . Card::Black;

    const DragonBig = Card::Dragon . '_' . Card::Big;
    const DragonSmall = Card::Dragon . '_' . Card::Small;
    const TigerBig = Card::Tiger . '_' . Card::Big;

    const TigerSmall = Card::Tiger . '_' . Card::Small;

    const options = [
        self::Dragon, self::DragonRed, self::DragonBlack, self::DragonSmall, self::DragonBig,
        self::Tiger, self::TigerRed, self::TigerBlack, self::TigerSmall, self::TigerBig,
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

    public function isBetOnDragon(): bool
    {
        return self::Dragon === $this->bet();
    }

    public function isBetOnDragonRed(): bool
    {
        return self::DragonRed === $this->bet();
    }

    public function isBetOnDragonBlack(): bool
    {
        return self::DragonBlack === $this->bet();
    }

    public function isBetOnTiger(): bool
    {
        return self::Tiger === $this->bet();
    }

    public function isBetOnTigerRed(): bool
    {
        return self::TigerRed === $this->bet();
    }

    public function isBetOnTigerBlack(): bool
    {
        return self::TigerBlack === $this->bet();
    }

    public function isBetOnDragonOrTiger(): bool
    {
        return $this->isBetOnDragon() || $this->isBetOnTiger();
    }

    public function isBetOnRedOrBlack(): bool
    {
        return $this->isBetOnDragonRed()
            || $this->isBetOnDragonBlack()
            || $this->isBetOnTigerRed()
            || $this->isBetOnTigerBlack();
    }

}
