<?php

namespace KravanhEco\Report\Modules\Mixed\Actions;

use Illuminate\Http\Request;
use KravanhEco\Report\Modules\Cockfight\Actions\GetWinLoseAction;
use KravanhEco\Report\Modules\Cockfight\Actions\WinLoseBuilderAction;

class GetCockfightWinLoseAction
{
    public function __invoke(Request $request): WinLoseBuilderAction
    {
        return WinLoseBuilderAction::from(
            items: (new GetWinLoseAction)($request)
        );
    }

    public static function make(Request $request): WinLoseBuilderAction 
    {
        return (new static)($request);
    }
}