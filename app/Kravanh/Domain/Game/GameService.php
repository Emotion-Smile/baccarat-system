<?php

namespace App\Kravanh\Domain\Game;

use App\Kravanh\Domain\Game\Actions\GameTableConditionUpdateOrCreateAction;
use App\Kravanh\Domain\Game\Dto\GameTableConditionData;

final class GameService implements IGameService
{

    public function setTableConditionForUser(GameTableConditionData $data)
    {
        return app(GameTableConditionUpdateOrCreateAction::class)(data: $data);
    }


}
