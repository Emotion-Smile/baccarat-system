<?php

namespace App\Kravanh\Domain\Integration\Actions\T88;

use App\Kravanh\Domain\Integration\Exceptions\T88Exception;
use App\Kravanh\Domain\Integration\Supports\Helpers\HttpJsonHelper;
use App\Kravanh\Domain\Integration\Supports\Traits\T88\HasApi;
use App\Models\User;

class GetAuthenticationTokenAction
{
    use HasApi;
    
    public function __invoke(?array $requestBody = null): string
    {
        $response = HttpJsonHelper::prepare()
            ->post(
                url: $this->requestUrl('/login'),
                data: $requestBody ?? $this->requestBody()
            );
            
        $responseBody = $response->object();

        if($response->failed()) {
            throw new T88Exception($responseBody?->message);
        }

        return $responseBody->token; 
    }

    protected function requestBody(): array
    {
        $user = request()->user();
        
        if($user->isCompany() || $user->isCompanySubAccount()) {
            return [
                'username' => 'wso.f88',
                'password' => 'KvF88!@#123',
            ];
        }

        if($user->isSubAccount()) {
            $user = User::find($user->getEnsureId());
        }

        return [
            'username' => $user->name . '.f88',
            'password' => 'KvF88!@#123',
        ];
    }
}