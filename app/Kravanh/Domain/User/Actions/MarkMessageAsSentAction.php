<?php

namespace App\Kravanh\Domain\User\Actions;

use App\Kravanh\Domain\User\Models\Message;

class MarkMessageAsSentAction
{
    public function __invoke(Message $message): void
    {
        $message->sent_at = now();
        $message->save();
    }
}