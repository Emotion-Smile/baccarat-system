<?php

namespace App\Kravanh\Domain\Integration\Subscribers;

use App\Kravanh\Domain\Integration\Contracts\Services\T88Contract;
use Exception;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;

class LogoutFromT88
{
    public function handle($event): void
    {
        try {
            App::make(T88Contract::class)->destroyToken();
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
        }
    }
}
