<?php

namespace App\Kravanh\Application\Admin\User\Actions;

use App\Kravanh\Domain\User\Events\ForceUserLogout;
use Illuminate\Bus\Queueable;
use Illuminate\Http\Request;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;

class ForceUserLogoutAction extends Action
{
    use InteractsWithQueue, Queueable;

    public $name = 'Logout';

    public $showOnTableRow = true;

    public function handle(ActionFields $fields, Collection $models)
    {
        foreach ($models as $user) {
            ForceUserLogout::dispatch($user->environment_id, $user->id);
        }
    }

    public function authorizedToSee(Request $request): bool
    {
        return true;
    }
}
