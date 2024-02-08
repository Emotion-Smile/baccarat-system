<?php

namespace App\Kravanh\Application\Member\Controllers;

use App\Kravanh\Domain\User\Models\Message;
use Inertia\Inertia;
use Inertia\Response;

class MessageController
{
    public function __invoke(): Response
    {
        return Inertia::render('Member/Message', [
            'messages' => $this->messages()
        ]);
    }

    protected function messages()
    {
        return Message::query()
            ->with('users', function($query) {
                $query->select('id')->whereId(user()->id);
            })
            ->whereNotNull('sent_at')
            ->where('sent_at', '>=', user()->created_at)
            ->orderByDesc('sent_at')
            ->paginate(10)
            ->through(fn($message) => [
                'id' => $message->id,
                'content' => $message->message,
                'timeHumans' => $message->sent_at->format('d/m/Y h:i:s A'),
                'unread' => ! (bool) $message->users->count()
            ]);
    }
}