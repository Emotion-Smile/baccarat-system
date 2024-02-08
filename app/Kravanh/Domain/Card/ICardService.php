<?php

namespace App\Kravanh\Domain\Card;

interface ICardService
{
    public function get(int $code): array;
}
