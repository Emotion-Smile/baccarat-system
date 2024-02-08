<?php

namespace App\Kravanh\Domain\Integration\Actions\AF88;

use App\Kravanh\Domain\Integration\Supports\Helpers\HttpJsonHelper;
use App\Kravanh\Domain\Integration\Supports\Traits\AF88\HasApi;
use Exception;

class MarkAsUserOfflineAction
{
    use HasApi;

    public function __invoke(
        string $token
    ): void
    {
        $response = HttpJsonHelper::prepare()
            ->authorization($token)
            ->post(
                url: $this->requestUrl('/mark/as/offline') 
            );

        if($response->failed()) {
            throw new Exception($response->object()?->message);
        }
    }
}