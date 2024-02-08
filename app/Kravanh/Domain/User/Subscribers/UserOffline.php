<?php

namespace App\Kravanh\Domain\User\Subscribers;

class UserOffline
{
    public function handle($event)
    {
        if ($event->user) {
            $event->user->online = 0;
            $event->user->saveQuietly();
        }
    }

}
