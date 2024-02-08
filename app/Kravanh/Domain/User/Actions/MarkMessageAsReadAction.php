<?php

namespace App\Kravanh\Domain\User\Actions;

use App\Kravanh\Domain\User\Models\Message;

class MarkMessageAsReadAction
{
    public function __invoke(Message $message): void
    {
        if(user()->messages()->whereMessageId($message->id)->exists()) return;
        
        $message->users()->attach(['user_id' => user()->id]);
    }
}