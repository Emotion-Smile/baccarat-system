<?php

namespace App\Kravanh\Domain\Integration\Subscribers;

use App\Kravanh\Domain\Integration\Jobs\AutoCreateT88GameConditionJob;
use App\Kravanh\Domain\Integration\Supports\Enums\T88Game;
use App\Models\User;

class AutoCreateT88GameCondition
{
    public function handle($event): void
    {
        $user = $event->user;

        AutoCreateT88GameConditionJob::dispatchIf($this->dispatchCondition($user), $user);
    }

    protected function dispatchCondition(User $user): bool
    {
        return $user->isMember() && $user->notHasT88GameCondition(T88Game::LOTTO_12) && $user->hasUpLineAllowedT88Game(T88Game::LOTTO_12);
    }
}
