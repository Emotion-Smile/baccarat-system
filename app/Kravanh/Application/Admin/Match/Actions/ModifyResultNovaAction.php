<?php

namespace App\Kravanh\Application\Admin\Match\Actions;

use App\Kravanh\Domain\Match\Actions\ModifyMatchResultAction;
use App\Kravanh\Domain\Match\Models\Matches;
use App\Kravanh\Domain\Match\Supports\Enums\MatchResult;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;

class ModifyResultNovaAction extends Action
{
    use InteractsWithQueue, Queueable;

    public $name = 'Modify Match Result';
    public $showOnTableRow = true;  // Show this action on the table row

    public function __construct(public MatchResult $prevResult)
    {
    }


    public function handle(ActionFields $fields, Collection $models): array
    {

        foreach ($models as $match) {
            /**
             * @var Matches $match
             */

            //cancel <> draw -> just update match result no perform transaction

            // cancel or draw <> wala -> withdraw bet amount all and deposit to winner
            // meron <> wala -> withdraw (bet amount + payout) all and deposit to winner
            // cancel or draw <> meron -> withdraw bet amount all and deposit to winner
            $rollbackTransactionCount = app(ModifyMatchResultAction::class)(
                match: $match,
                result: $fields['result'],
                note: $fields['note']
            );

            ray('yes');

            return Action::message(
                "The rollback transactions ($rollbackTransactionCount) and modification match result successfully"
            );

        }

        return Action::message("The action ran successfully");
    }


    public function fields(): array
    {
        $options = MatchResult::asSelectArray();

        unset($options[array_search($this->prevResult->description, $options)]);
        unset($options[array_search('Pending', $options)]);
        unset($options[array_search('None', $options)]);

        return [
            Select::make('New result', 'result')
                ->options(
                    $options
                )->rules('required', 'numeric')
                ->help('Modify result from: ' . $this->prevResult->description . ' to')
            ,
            Text::make('Note', 'note')
                ->rules('required')
        ];
    }
}
