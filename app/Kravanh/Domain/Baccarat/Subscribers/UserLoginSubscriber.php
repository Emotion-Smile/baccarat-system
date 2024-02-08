<?php

namespace App\Kravanh\Domain\Baccarat\Subscribers;

use App\Kravanh\Domain\Baccarat\Actions\BaccaratGameAutoSetDefaultBetConditionAction;

class UserLoginSubscriber
{
    public function handle(mixed $event): void
    {
        app(BaccaratGameAutoSetDefaultBetConditionAction::class)(member: $event->user);
    }
}
