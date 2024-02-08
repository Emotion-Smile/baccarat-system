<?php

namespace App\Kravanh\Application\Admin\User\Actions;

use App\Kravanh\Domain\Environment\Models\Group;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\BooleanGroup;

class DisableTVNovaAction extends Action
{
    use InteractsWithQueue, Queueable;

    public $name = 'Disable TV';

    public $showOnTableRow = true;

    public int $resourceId;

    public function handle(ActionFields $fields, Collection $models)
    {
        //https://meki.medium.com/context-aware-table-row-actions-for-laravel-nova-51087f3176a8
        foreach ($models as $model) {
            /**
             * @var User $model
             */
            $model->disableGroups()->detach();
            $model->disableGroups()->attach($this->resolveDisableGroupId($fields));
        }
    }

    public function fields(): array
    {
        return [
            BooleanGroup::make('TV')
                ->options($this->getGroups())
                ->withMeta([
                    'value' => $this->getGroupUserCurrentlyIn()
                ]),
        ];
    }

    private function resolveDisableGroupId($fields): array
    {
        return collect($fields['tv'])
            ->filter(fn($item) => $item === true)
            ->keys()
            ->all();
    }

    private function getGroupUserCurrentlyIn(): array
    {
        return DB::table('group_user')
            ->select('group_id')
            ->where('user_id', $this->resourceId)
            ->get()
            ->mapWithKeys(fn($item) => [$item->group_id => true])
            ->all();
    }

    private function getGroups(): array
    {
        return Group::query()
            ->select(['id', 'name'])
            ->where('active', true)
            ->where('use_second_trader', false)
            ->get()
            ->mapWithKeys(fn($item, $key) => [$item['id'] => $item['name']])
            ->all();
    }

    public function setResourceId(int|null $value): static
    {
        $this->resourceId = $value ?? 0;
        return $this;
    }


}
