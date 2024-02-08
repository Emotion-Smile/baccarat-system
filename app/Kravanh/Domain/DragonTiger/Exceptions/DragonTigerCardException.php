<?php

namespace App\Kravanh\Domain\DragonTiger\Exceptions;

use App\Kravanh\Support\DomainException;

final class DragonTigerCardException extends DomainException
{
    public static function invalidRange(): DragonTigerCardException
    {
        return new self("The valid card range is value between 1 and 13");
    }

    public static function invalidType(): DragonTigerCardException
    {
        return new self("The valid card type are heart, diamond, spade, club");
    }
}
