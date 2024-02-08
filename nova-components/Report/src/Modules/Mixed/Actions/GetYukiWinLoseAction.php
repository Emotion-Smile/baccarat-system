<?php

namespace KravanhEco\Report\Modules\Mixed\Actions;

use App\Models\User;
use Exception;
use KravanhEco\Report\Modules\Yuki\Actions\GetWinLoseAction;
use KravanhEco\Report\Modules\Yuki\Actions\WinLoseBuilderAction;

class GetYukiWinLoseAction
{
    public function __invoke(User $user, string $date): WinLoseBuilderAction
    {
        try {
            return WinLoseBuilderAction::from(
                items: (new GetWinLoseAction)(
                    user: $user,
                    date: $date 
                )
            );
        } catch (Exception $exception) {
            info($exception->getMessage());
            return WinLoseBuilderAction::from(collect());
        }
    }

    public static function make(User $user, string $date): WinLoseBuilderAction
    {
        return (new static)($user, $date);
    }
}