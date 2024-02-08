<?php

namespace App\Kravanh\Application\Admin\User\Actions;

use App\Kravanh\Application\Admin\Support\UseNovaAction;
use App\Kravanh\Domain\UserOption\Actions\UserOptionCreateAction;
use App\Kravanh\Domain\UserOption\Actions\UserOptionDeleteAction;
use App\Kravanh\Domain\UserOption\Actions\UserOptionHasUseSecondTraderAction;
use App\Kravanh\Domain\UserOption\Support\Enum\Option;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Boolean;

class UseSecondTraderNovaAction extends Action
{
    use InteractsWithQueue, Queueable;
    use UseNovaAction;

    public $name = 'Use Second Trader';

    public $showOnTableRow = true;

    public function __construct(public int $resourceId)
    {
    }

    public function handle(ActionFields $fields, Collection $models)
    {

        foreach ($models as $model) {
            /**
             * @var User $model
             */
            if (!$fields->use_second_trader) {
                app(UserOptionDeleteAction::class)($model->id, Option::USE_SECOND_TRADER);
                return Action::message('The action ran successfully');
            }

            if (!app(UserOptionHasUseSecondTraderAction::class)($model->id)) {
                app(UserOptionCreateAction::class)($model->id, Option::USE_SECOND_TRADER);
            }

            return Action::message('The action ran successfully');
        }
    }

    public function fields(): array
    {
        return [
            Boolean::make('Use Second Trader')
                ->withMeta([
                    'value' => app(UserOptionHasUseSecondTraderAction::class)($this->resourceId)
                ]),
        ];
    }

}
