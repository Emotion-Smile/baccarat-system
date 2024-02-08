<?php

namespace App\Kravanh\Domain\Integration\Services;

use App\Kravanh\Domain\Integration\Actions\AF88\CreateGameConditionAction;
use App\Kravanh\Domain\Integration\Actions\AF88\CreateUserAction;
use App\Kravanh\Domain\Integration\Actions\AF88\DestroyAuthenticationTokenAction;
use App\Kravanh\Domain\Integration\Actions\AF88\GetAuthenticationTokenAction;
use App\Kravanh\Domain\Integration\Actions\AF88\GetUserDetailAction;
use App\Kravanh\Domain\Integration\Actions\AF88\MarkAsUserOfflineAction;
use App\Kravanh\Domain\Integration\Actions\AF88\UpdateGameConditionAction;
use App\Kravanh\Domain\Integration\Actions\AF88\UpdateUserAction;
use App\Kravanh\Domain\Integration\Contracts\Services\AF88Contract;
use App\Kravanh\Domain\Integration\DataTransferObject\AF88\UpdateGameConditionData;
use App\Kravanh\Domain\Integration\DataTransferObject\AF88\CreateGameConditionData;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Laravel\Nova\Fields\ActionFields;

class AF88Service implements AF88Contract
{
    public function setToken(): string
    {
        $token = (new GetAuthenticationTokenAction)();
        
        Cache::forever($this->getTokenCacheKey(), $token);

        return $token;
    }

    public function getToken(): string
    {
        $tokenCacheKey = $this->getTokenCacheKey();
        
        if (Cache::get($tokenCacheKey)) {
            return Cache::get($tokenCacheKey);
        }
        
        return $this->setToken();
    }

    public function destroyToken(): void
    {
        $tokenCacheKey = $this->getTokenCacheKey();

        $token = Cache::get($tokenCacheKey);

        if (! $token) {
            return;
        }

        (new DestroyAuthenticationTokenAction)($token);
        
        Cache::forget($tokenCacheKey);
    }

    public function save(User $user, ActionFields $fields): void
    {
        $user->hasAF88GameCondition()
            ? $this->updateUser($user, UpdateGameConditionData::fromNovaAction($user, $fields))
            : $this->createUser($user, CreateGameConditionData::fromNovaAction($user, $fields));
    }

    public function getUserDetail(): array
    {
        $token = $this->getToken();

        $userDetail = (new GetUserDetailAction)($token);

        return $userDetail;
    }

    public function createUser(User $user, CreateGameConditionData $createGameConditionData): void
    {
        (new CreateUserAction)(
            user: $user,
            token: $this->getToken(),
            createGameConditionData: $createGameConditionData
        );

        (new CreateGameConditionAction)($createGameConditionData);
    }

    public function updateUser(User $user, UpdateGameConditionData $updateGameConditionData): void
    {
        (new UpdateUserAction)(
            user: $user,
            token: $this->getToken(),
            updateGameConditionData: $updateGameConditionData
        );

        (new UpdateGameConditionAction)($updateGameConditionData);
    }

    public function makeAsUserOffline(): void
    {
        $token = $this->getToken();
       
        (new MarkAsUserOfflineAction)($token);
    }

    public function getTokenCacheKey(): string
    {
        return 'af88.authentication.token.' . request()->user()->id;
    }
}