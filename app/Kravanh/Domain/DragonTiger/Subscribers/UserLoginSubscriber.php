<?php

namespace App\Kravanh\Domain\DragonTiger\Subscribers;

use App\Kravanh\Domain\DragonTiger\Actions\DragonTigerGameAutoSetDefaultBetConditionAction;

class UserLoginSubscriber
{
    public function handle(mixed $event): void
    {
        app(DragonTigerGameAutoSetDefaultBetConditionAction::class)(member: $event->user);
    }
}
