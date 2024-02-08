<?php

namespace App\Kravanh\Domain\Integration\Subscribers;

use App\Kravanh\Domain\Integration\Contracts\Services\AF88Contract;
use App\Kravanh\Domain\User\Supports\Enums\UserType;
use Exception;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;

class LogoutFromAF88
{
    public function handle($event): void
    {
        $user = $event->user;

        try {
            if($user->type->in([UserType::AGENT, UserType::MASTER_AGENT])) {
                App::make(AF88Contract::class)->destroyToken();
            }
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
        }
    }
}
