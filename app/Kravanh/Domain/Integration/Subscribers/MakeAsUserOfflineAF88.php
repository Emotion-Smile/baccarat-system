<?php

namespace App\Kravanh\Domain\Integration\Subscribers;

use App\Kravanh\Domain\Integration\Contracts\Services\AF88Contract;
use App\Kravanh\Domain\User\Supports\Enums\UserType;
use Exception;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;

class MakeAsUserOfflineAF88
{
    public function handle($event): void
    {
        try {
            $user = $event->user;
            
            if($user->type->is(UserType::MEMBER) && $user->hasAF88GameCondition()) {
                App::make(AF88Contract::class)->makeAsUserOffline();
            }
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
        }
    }
}
