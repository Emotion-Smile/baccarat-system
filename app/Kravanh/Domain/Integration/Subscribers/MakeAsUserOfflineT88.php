<?php

namespace App\Kravanh\Domain\Integration\Subscribers;

use App\Kravanh\Domain\Integration\Contracts\Services\T88Contract;
use App\Kravanh\Domain\User\Supports\Enums\UserType;
use Exception;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;

class MakeAsUserOfflineT88
{
    public function handle($event): void
    {
        try {
            $user = $event->user;

            if($user->type->is(UserType::MEMBER) && $user->hasT88GameCondition('LOTTO-12')) {
                App::make(T88Contract::class)->makeAsUserOffline();
            }
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
        }
    }
}
