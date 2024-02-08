<?php

namespace App\Kravanh\Domain\Integration\Contracts\Services;

use App\Kravanh\Domain\Integration\DataTransferObject\AF88\UpdateGameConditionData;
use App\Kravanh\Domain\Integration\DataTransferObject\AF88\CreateGameConditionData;
use App\Models\User;
use Laravel\Nova\Fields\ActionFields;

interface AF88Contract
{
    public function setToken(): string;

    public function getToken(): string;

    public function destroyToken(): void;

    public function save(User $user, ActionFields $fields): void;

    public function getUserDetail(): array;

    public function createUser(User $user, CreateGameConditionData $createGameConditionData): void;

    public function updateUser(User $user, UpdateGameConditionData $updateGameConditionData): void;

    public function makeAsUserOffline(): void;

    public function getTokenCacheKey(): string;
}