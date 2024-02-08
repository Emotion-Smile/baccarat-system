<?php

namespace App\Kravanh\Domain\DragonTiger\App\Nova\Http\Controllers;

use App\Kravanh\Domain\DragonTiger\App\Nova\Http\Requests\SaveGameConditionRequest;
use App\Kravanh\Domain\Game\Actions\GameDragonTigerGetAction;
use App\Kravanh\Domain\Game\Dto\GameTableConditionData;
use App\Kravanh\Domain\Game\IGameService;
use App\Models\User;
use Exception;
use Laravel\Nova\Actions\Action;

class SaveGameConditionController
{
    public function __invoke(SaveGameConditionRequest $request): array
    {
        try {
            $user = $request->getUser(); 

            $this->dragonTigerSetTableConditionForUser(
                user: $user,
                fields: $request->all()
            );

            return Action::message("The table condition for user $user->name create successfully");
        } catch (Exception $exception) {
            return Action::message($exception->getMessage());
        }
    }

    public function dragonTigerSetTableConditionForUser(
        User  $user,
        array $fields
    ): void
    {
        $game = app(GameDragonTigerGetAction::class)();

        app(IGameService::class)->setTableConditionForUser(
            data: GameTableConditionData::fromBuild(
                gameId: $game->id,
                gameTableId: $game->firstTableId(),
                userId: $user->id,
                userType: $user->type->value,
                build: GameTableConditionData::build($fields)
            )
        );
    }
}