<?php

namespace App\Kravanh\Application\Admin\User\Actions;

use App\Kravanh\Domain\User\Events\RefreshPage;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;

class RefreshPageNovaAction extends Action
{
    use InteractsWithQueue, Queueable;

    public $name = 'Refresh Page';

    public $showOnTableRow = true;

    public function handle(ActionFields $fields, Collection $models)
    {
        foreach ($models as $user) {
            RefreshPage::dispatch($user->environment_id, $user->id);
        }
    }
}
