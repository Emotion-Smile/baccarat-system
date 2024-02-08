<?php

namespace App\Kravanh\Domain\DragonTiger\Support;

use App\Kravanh\Domain\Game\Actions\GameTableIsAvailableAction;
use App\Kravanh\Domain\Game\Actions\UserCanPlayDragonTigerGameAction;
use App\Models\User;

/**
 * @mixin User
 */
trait HasDragonTigerGame
{
    /**
     * required append attribute in model can_play_dragon_tiger_game
     */
    public function getCanPlayDragonTigerAttribute(): bool
    {
        if (! $this->isMember()) {
            return false;
        }

        return $this->canPlayDragonTiger();

    }

    public function canPlayDragonTiger(): bool
    {
        if ($this->isSuperSenior()) {
            return true;
        }

        return app(UserCanPlayDragonTigerGameAction::class)(userId: $this->super_senior);
    }

    public function isDragonTraderGameTableAvailable(): bool
    {
        return app(GameTableIsAvailableAction::class)(gameTableId: $this->getGameTableId());
    }
}
