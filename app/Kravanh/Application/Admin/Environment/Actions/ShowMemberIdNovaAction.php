<?php

namespace App\Kravanh\Application\Admin\Environment\Actions;

use App\Kravanh\Domain\Environment\Events\ShowMemberId;
use Illuminate\Bus\Queueable;
use Illuminate\Http\Request;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;

class ShowMemberIdNovaAction extends Action
{
    use InteractsWithQueue, Queueable;

    public $name = 'Show Member Id';

    public $showOnTableRow = true;

    public function handle(ActionFields $fields, Collection $models)
    {
        foreach ($models as $env) {
            ShowMemberId::dispatch($env->id);
        }
    }

    public function authorizedToSee(Request $request): bool
    {
        return true;
    }
}
