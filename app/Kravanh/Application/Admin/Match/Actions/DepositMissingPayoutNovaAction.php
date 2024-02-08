<?php

namespace App\Kravanh\Application\Admin\Match\Actions;

use App\Kravanh\Domain\Match\Actions\DepositMissingPayoutAction;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;

class DepositMissingPayoutNovaAction extends Action
{
    use InteractsWithQueue, Queueable;

    public $name = 'Deposit Missing Payout';
    public $showOnTableRow = true;  // Show this action on the table row


    public function handle(ActionFields $fields, Collection $models): array
    {
        foreach ($models as $match) {
            $totalMissingTickets = (new DepositMissingPayoutAction())($match->id);
            return Action::message('Total tickets: ' . $totalMissingTickets);
        }

        return Action::message('No, ticket');

    }

}
