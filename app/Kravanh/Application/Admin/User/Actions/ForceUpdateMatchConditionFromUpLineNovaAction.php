<?php

namespace App\Kravanh\Application\Admin\User\Actions;

use App\Kravanh\Domain\User\Actions\ForceUpdateMatchConditionFromUpLineAction;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;

class ForceUpdateMatchConditionFromUpLineNovaAction extends Action
{
    use InteractsWithQueue, Queueable;

    public $name = 'Force Update Match Condition From Upline';

    public $showOnTableRow = true;

    public function handle(ActionFields $fields, Collection $models)
    {
        foreach ($models as $user) {
            (new ForceUpdateMatchConditionFromUpLineAction)($user);
        }
    }

    public static function build(): ForceUpdateMatchConditionFromUpLineNovaAction
    {

        return (new ForceUpdateMatchConditionFromUpLineNovaAction)
            ->onlyOnDetail()
            ->canSee(fn($request) => $request->user()->isCompany())
            ->canRun(fn($request) => $request->user()->isCompany());

    }

}
