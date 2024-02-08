<?php

namespace App\Kravanh\Domain\Integration\Contracts\Services;

use App\Kravanh\Domain\Integration\DataTransferObject\T88\CreateGameConditionData;
use App\Kravanh\Domain\Integration\DataTransferObject\T88\UpdateGameConditionData;
use App\Kravanh\Domain\Integration\Nova\Http\Requests\T88\SaveGameConditionRequest;
use App\Models\User;
use Laravel\Nova\Fields\ActionFields;

interface T88Contract
{
    public function setToken(): string;

    public function getToken(): string;

    public function destroyToken(): void;

    public function createUser(User $user, CreateGameConditionData $createGameConditionData): void;

    public function updateUser(User $user, UpdateGameConditionData $updateGameConditionData): void;

    public function createOrUpdateUser(SaveGameConditionRequest $request): void;

    public function createOrUpdateUserFromNovaAction(User $user, ActionFields $fields): void;

    public function getUserInformation(): array;

    public function getUserGameCondition(User $user, string $gameType): array;

    public function makeAsUserOffline(): void;
}