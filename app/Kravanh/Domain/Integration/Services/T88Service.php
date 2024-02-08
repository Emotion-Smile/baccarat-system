<?php

namespace App\Kravanh\Domain\Integration\Services;

use App\Kravanh\Domain\Integration\Actions\T88\CreateGameConditionAction;
use App\Kravanh\Domain\Integration\Actions\T88\CreateUserAction;
use App\Kravanh\Domain\Integration\Actions\T88\DestroyAuthenticationTokenAction;
use App\Kravanh\Domain\Integration\Actions\T88\GetAuthenticationTokenAction;
use App\Kravanh\Domain\Integration\Actions\T88\GetUserInformationAction;
use App\Kravanh\Domain\Integration\Actions\T88\MarkAsUserOfflineAction;
use App\Kravanh\Domain\Integration\Actions\T88\UpdateGameConditionAction;
use App\Kravanh\Domain\Integration\Actions\T88\UpdateUserAction;
use App\Kravanh\Domain\Integration\Contracts\Services\T88Contract;
use App\Kravanh\Domain\Integration\DataTransferObject\T88\CreateGameConditionData;
use App\Kravanh\Domain\Integration\DataTransferObject\T88\UpdateGameConditionData;
use App\Kravanh\Domain\Integration\Nova\Http\Requests\T88\SaveGameConditionRequest;
use App\Models\User;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Laravel\Nova\Fields\ActionFields;

class T88Service implements T88Contract
{
    public function setToken(): string
    {
        $token = (new GetAuthenticationTokenAction)();
        Cache::forever($this->getTokenCacheKey(), $token);

        return $token;
    }

    public function getToken(): string
    {
        if (Cache::get($this->getTokenCacheKey())) {
            return Cache::get($this->getTokenCacheKey());
        }

        return $this->setToken();
    }

    public function destroyToken(): void
    {
        try {
            $token = Cache::get($this->getTokenCacheKey());

            if (is_null($token)) {
                return;
            }

            (new DestroyAuthenticationTokenAction)($token);
        } catch (Exception $exception) {
            throw $exception;
        } finally {
            Cache::forget($this->getTokenCacheKey());
        }
    }

    public function createUser(User $user, CreateGameConditionData $createGameConditionData): void
    {
        try {
            (new CreateUserAction)(
                user: $user,
                token: $this->getToken(),
                createGameConditionData: $createGameConditionData
            );

            (new CreateGameConditionAction)($createGameConditionData);
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function updateUser(User $user, UpdateGameConditionData $updateGameConditionData): void
    {
        try {
            (new UpdateUserAction)(
                user: $user,
                token: $this->getToken(),
                updateGameConditionData: $updateGameConditionData
            );

            (new UpdateGameConditionAction)($updateGameConditionData);
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function createOrUpdateUser(SaveGameConditionRequest $request): void
    {
        $user = $request->getUser();

        try {
            $user->hasT88GameCondition($request->game_type)
                ? $this->updateUser($user, UpdateGameConditionData::fromRequest($user, $request))
                : $this->createUser($user, CreateGameConditionData::fromRequest($user, $request));
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function createOrUpdateUserFromNovaAction(User $user, ActionFields $fields): void
    {
        try {
            $user->hasT88GameCondition($fields->game_type)
                ? $this->updateUser($user, UpdateGameConditionData::fromNovaAction($user, $fields))
                : $this->createUser($user, CreateGameConditionData::fromNovaAction($user, $fields));
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function getUserInformation(): array
    {
        try {
            $token = $this->getToken();

            $userInformation = (new GetUserInformationAction)($token);

            return $userInformation;
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function getUserGameCondition(User $user, string $gameType): array
    {
        try {
            if ($user->hasT88GameCondition($gameType)) {
                $condition = $user->t88GameCondition($gameType)->condition;

                return [
                    'game_type' => $gameType,
                    'commission' => $condition['commission'],
                    'down_line_share' => $condition['down_line_share'],
                    'bet_limit' => $condition['bet_limit'],
                    'win_limit' => $condition['win_limit'],
                    'minimum_bet' => $condition['minimum_bet'],
                    'maximum_bet' => $condition['maximum_bet']
                ];
            }

            $userInformation = $this->getUserInformation();

            $condition = Collection::make($userInformation['conditions'])
                ->map(fn($condition) => [
                    'game_type' => $condition['game_type'],
                    'commission' => $condition['commission'],
                    'down_line_share' => $condition['hold_share'],
                    'bet_limit' => $condition['match_limit'],
                    'win_limit' => $condition['win_limit'],
                    'minimum_bet' => $condition['minimum_bet_per_ticket'],
                    'maximum_bet' => $condition['maximum_bet_per_ticket']
                ])
                ->firstWhere('game_type', $gameType);

            return $condition ?? [];
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function makeAsUserOffline(): void
    {
        $token = $this->getToken();

        (new MarkAsUserOfflineAction)($token);
    }

    protected function getTokenCacheKey(): string
    {
        $user = request()->user();

        return "t88.authentication.token.{$user->id}";
    }
}
