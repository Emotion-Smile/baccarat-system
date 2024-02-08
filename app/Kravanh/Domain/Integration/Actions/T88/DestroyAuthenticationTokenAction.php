<?php

namespace App\Kravanh\Domain\Integration\Actions\T88;

use App\Kravanh\Domain\Integration\Exceptions\T88Exception;
use App\Kravanh\Domain\Integration\Supports\Helpers\HttpJsonHelper;
use App\Kravanh\Domain\Integration\Supports\Traits\T88\HasApi;

class DestroyAuthenticationTokenAction
{
    use HasApi;

    public function __invoke(
        string $token
    ): void
    {
        $response = HttpJsonHelper::prepare()
            ->authorization($token)
            ->post(
                url: $this->requestUrl('/logout') 
            );

        if($response->failed()) {
            throw new T88Exception($response->object()?->message);
        }
    }
}