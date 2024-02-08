<?php

namespace App\Kravanh\Domain\DragonTiger\Support;

use App\Kravanh\Domain\DragonTiger\Exceptions\DragonTigerCardException;

final class DragonTigerCard
{
    const Red = 'red';

    const Black = 'black';

    const Heart = 'heart';

    const Diamond = 'diamond';

    const Spade = 'spade';

    const Club = 'club';

    const Small = 'small';

    const Big = 'big';

    const Middle = 'middle';

    const Dragon = 'dragon';

    const Tiger = 'tiger';

    const Tie = 'tie';

    /**
     * @throws DragonTigerCardException
     */
    public function __construct(
        public readonly string $type,
        public readonly int $number,
    ) {
        if (! $this->isValidRange()) {
            throw DragonTigerCardException::invalidRange();
        }

        if (! $this->isValidType()) {
            throw DragonTigerCardException::invalidType();
        }
    }

    /**
     * @throws DragonTigerCardException
     */
    public static function make(string $type, int $number): DragonTigerCard
    {

        return new DragonTigerCard($type, $number);
    }

    /**
     * @throws DragonTigerCardException
     */
    public static function validate(string $type, int $number): void
    {
        DragonTigerCard::make($type, $number);
    }

    /**
     * @throws DragonTigerCardException
     */
    public static function getColor(string $type): string
    {
        return self::make($type, 1)->color();
    }

    /**
     * @throws DragonTigerCardException
     */
    public static function getRange(int $number): string
    {
        return self::make(self::Heart, $number)->range();
    }

    /**
     * @throws DragonTigerCardException
     */
    public function getLabel(int $number): string
    {
        return self::make(self::Heart, $number)->label();
    }

    public function label(): string
    {
        return match ($this->number) {
            1 => 'A',
            11 => 'J',
            12 => 'Q',
            13 => 'K',
            2, 3, 4, 5, 6, 7, 8, 9, 10 => $this->number.'',
            default => 'unknown'
        };
    }

    public function isValidRange(): bool
    {
        if ($this->number < 1) {
            return false;
        }

        if ($this->number > 13) {
            return false;
        }

        return true;
    }

    public function isValidType(): bool
    {

        return in_array(
            $this->type,
            [self::Heart, self::Diamond, self::Spade, self::Club]
        );
    }

    public function range(): string
    {
        /**
         * @todo need discuss
         */
        if ($this->number === 7) {
            return self::Middle;
        }

        if ($this->number > 7) {
            return self::Big;
        }

        return self::Small;

    }

    public function color(): string
    {
        return match ($this->type) {
            self::Heart, self::Diamond => self::Red,
            self::Club, self::Spade => self::Black
        };
    }

    public function icon(): string
    {
        //https://fontawesomeicons.com/svg/icons/diamond-thin
        return match ($this->type) {
            self::Heart => <<<'HTML'
                            <svg style="width: 24px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 256"><rect width="256" height="256" fill="none"/><path d="M128,216S28,160,28,92A52,52,0,0,1,128,72h0A52,52,0,0,1,228,92C228,160,128,216,128,216Z" fill="none" stroke="red" stroke-linecap="round" stroke-linejoin="round" stroke-width="12"/></svg>
                           HTML,
            self::Diamond => <<<'HTML'
                            <svg style="width: 24px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 256"><rect width="256" height="256" fill="none"/><rect x="51.6" y="51.6" width="152.7" height="152.74" rx="8" transform="translate(-53 128) rotate(-45)" fill="none" stroke="red" stroke-linecap="round" stroke-linejoin="round" stroke-width="12"/></svg>
                            HTML,
            self::Spade => <<<'HTML'
                            <svg style="width: 24px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 256"><rect width="256" height="256" fill="none"/><path d="M160,232H96l8.7-46.2A52.1,52.1,0,0,1,28,140C28,88,128,24,128,24S228,88,228,140a52.1,52.1,0,0,1-76.7,45.8Z" fill="none" stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-width="12"/></svg>
                           HTML,
            self::Club => <<<'HTML'
                            <svg style="width: 24px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 256"><rect width="256" height="256" fill="none"/><path d="M112.5,179.2A48,48,0,1,1,76,100a51.2,51.2,0,0,1,11.2,1.3h0A47.3,47.3,0,0,1,80,76a48,48,0,0,1,96,0,47.3,47.3,0,0,1-7.2,25.3h0A51.2,51.2,0,0,1,180,100a48,48,0,1,1-36.5,79.2L160,232H96Z" fill="none" stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-width="12"/></svg>
                          HTML,
            default => ''
        };
    }
}
