<?php

namespace App\Kravanh\Domain\Integration\Actions\AF88;

use App\Kravanh\Domain\Integration\Supports\Helpers\HttpJsonHelper;
use App\Kravanh\Domain\Integration\Supports\Traits\AF88\HasApi;
use App\Kravanh\Domain\User\Supports\Enums\UserType;
use App\Kravanh\Support\Enums\Currency;
use Exception;

class GetAuthenticationTokenAction
{
    use HasApi;
    
    public function __invoke(): string
    {
        $response = HttpJsonHelper::prepare()
            ->post(
                url: $this->requestUrl('/login'),
                data: $this->requestBody()
            );
        
        $responseBody = $response->object();
    
        if($response->failed()) {
            throw new Exception($responseBody?->message);
        }

        return $responseBody->token; 
    }

    protected function requestBody(): array
    {
        $user = request()->user();

        if($user->type->is(UserType::MASTER_AGENT)) {
            $username = match ($user->currency->value) {
                Currency::KHR => 'ma_int_khr',
                Currency::USD => 'ma_int_usd',
                Currency::THB => 'ma_int_thb',
                Currency::VND => 'ma_int_vnd',
                default => ''
            };

            return [
                'username' => $username,
                'password' => 'imsi00',
            ];
        }

        return [
            'username' => $user->name . '.f88',
            'password' => 'imsi00',
        ];
    }
}