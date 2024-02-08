<?php

namespace App\Kravanh\Application\Admin\Match\Actions;

use App\Kravanh\Domain\Match\Events\MatchResultUpdated;
use App\Kravanh\Domain\Match\Supports\Enums\MatchResult;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Select;

class UpdatePendingResultNovaAction extends Action
{
    use InteractsWithQueue, Queueable;

    public $name = 'Update Result';
    public $confirmButtonText = 'Update';
    public $showOnTableRow = true;  // Show this action on the table row

    public function handle(ActionFields $fields, Collection $models): void
    {
        foreach ($models as $match) {

            $match->result = $fields['result'];
            $match->saveQuietly();

            MatchResultUpdated::dispatch([
                'id' => $match->id
            ]);
        }

    }

    public function fields(): array
    {
        $options = MatchResult::asSelectArray();

        unset($options[array_search('Pending', $options)]);
        unset($options[array_search('None', $options)]);

        return [
            Select::make('Result')
                ->options(
                    $options
                )->rules('required', 'numeric'),
        ];
    }
}
