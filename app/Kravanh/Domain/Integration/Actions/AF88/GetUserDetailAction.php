<?php

namespace App\Kravanh\Domain\Integration\Actions\AF88;

use App\Kravanh\Domain\Integration\Supports\Helpers\HttpJsonHelper;
use App\Kravanh\Domain\Integration\Supports\Traits\AF88\HasApi;
use Exception;

class GetUserDetailAction
{
    use HasApi;

    public function __invoke(
        string $token
    ): array
    {
        $response = HttpJsonHelper::prepare()
            ->authorization($token)
            ->get($this->requestUrl('/user/detail'));

        if($response->failed()) {
            throw new Exception($response->object()?->message);
        }

        return $response->json();
    }
}