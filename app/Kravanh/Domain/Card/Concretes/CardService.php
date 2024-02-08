<?php

namespace App\Kravanh\Domain\Card\Concretes;

use App\Kravanh\Domain\Card\Actions\CardGetAction;
use App\Kravanh\Domain\Card\ICardService;

class CardService implements ICardService
{
    public function get(int $code): array
    {
        return app(CardGetAction::class)($code);
    }
}
