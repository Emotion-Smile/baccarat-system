<?php

namespace App\Kravanh\Application\Admin\Match\Actions;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Number;

class ModifyFightNumberNovaAction extends Action
{
    use InteractsWithQueue, Queueable;

    public $name = 'Modify Fight Number';
    public $showOnTableRow = true;  // Show this action on the table row

    public function handle(ActionFields $fields, Collection $models)
    {
        foreach ($models as $match) {

            $match->fight_number = $fields['fight_number'];
            $match->saveQuietly();

            if (is_null($match->match_end_at)) {
                $match->liveClearCache();
            }

        }
    }

    public function fields(): array
    {

        return [
            Number::make('Fight Number', 'fight_number')->required()
        ];
    }
}
