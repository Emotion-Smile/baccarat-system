<?php

namespace App\Kravanh\Domain\Baccarat\Exceptions;

use App\Kravanh\Support\DomainException;

final class BaccaratCardException extends DomainException
{
    public static function invalidRange(): BaccaratCardException
    {
        return new self("The valid card range is value between 0 and 9");
    }

    public static function invalidType(): BaccaratCardException
    {
        return new self("The valid card type are heart, diamond, spade, club");
    }
}
