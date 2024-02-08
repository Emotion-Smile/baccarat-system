<?php

namespace App\Kravanh\Domain\Game;

use App\Kravanh\Domain\Game\Dto\GameTableConditionData;

interface IGameService
{
    public function setTableConditionForUser(GameTableConditionData $data);
}
