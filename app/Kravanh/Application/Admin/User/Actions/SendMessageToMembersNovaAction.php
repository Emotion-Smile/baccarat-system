<?php

namespace App\Kravanh\Application\Admin\User\Actions;

use App\Kravanh\Domain\User\Actions\SendMessageToMembersAction;
use Illuminate\Bus\Queueable;
use Laravel\Nova\Actions\Action;
use Illuminate\Support\Collection;
use Laravel\Nova\Fields\ActionFields;
use Illuminate\Queue\InteractsWithQueue;

class SendMessageToMembersNovaAction extends Action
{
    use InteractsWithQueue, Queueable;

    public $name = 'Send Message To Members';

    public function handle(ActionFields $fields, Collection $messages)
    {
        foreach ($messages as $message) {
            (new SendMessageToMembersAction)($message);
        }

        return Action::message('Message Sent.');
    }
}