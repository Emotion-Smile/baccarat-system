<?php

namespace KravanhEco\Report\Modules\Mixed\Actions;

use App\Models\User;
use KravanhEco\Report\Modules\DragonTiger\Actions\GetWinLoseAction;
use KravanhEco\Report\Modules\DragonTiger\Actions\WinLoseBuilderAction;

class GetDragonTigerWinLoseAction
{
    public function __invoke(User $user, string $date): WinLoseBuilderAction
    {
        return WinLoseBuilderAction::from(
            items: (new GetWinLoseAction)(
                user: $user,
                date: $date 
            )
        );
    }

    public static function make(User $user, string $date): WinLoseBuilderAction 
    {
        return (new static)($user, $date);
    }
}