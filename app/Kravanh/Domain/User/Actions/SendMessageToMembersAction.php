<?php

namespace App\Kravanh\Domain\User\Actions;

use App\Kravanh\Domain\User\Events\NotifyNewMessage;
use App\Kravanh\Domain\User\Models\Message;

class SendMessageToMembersAction
{
    public function __invoke(Message $message): void
    {
        (new MarkMessageAsSentAction)($message);

        NotifyNewMessage::dispatch(user()->environment_id);
    }
}