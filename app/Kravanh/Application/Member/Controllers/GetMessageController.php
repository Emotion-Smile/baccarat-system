<?php

namespace App\Kravanh\Application\Member\Controllers;

use App\Kravanh\Domain\User\Models\Message;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;

class GetMessageController
{
    public function __invoke(): JsonResponse
    {
        return asJson([
            'messages' => $this->messages(), 
            'totalUnreadMessage' => $this->totalUnreadMessage(),
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
            ->limit(5)
            ->get()
            ->map(fn($message) => [
                'id' => $message->id,
                'content' => $message->message,
                'timeHumans' => $message->sent_at->format('d/m/Y h:i:s A'),
                'unread' => ! (bool) $message->users->count()
            ]);
    }

    protected function totalUnreadMessage(): int
    {
        return Message::query()
            ->whereDoesntHave('users', function(Builder $query) {
                $query->whereId(user()->id);
            })
            ->whereNotNull('sent_at')
            ->where('sent_at', '>=', user()->created_at)
            ->count();
    }
}
