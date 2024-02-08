<?php

namespace App\Kravanh\Domain\User\Jobs;

use App\Kravanh\Support\External\Telegram\Telegram;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;

class TraceUserJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    public function __construct(
        public string $userName,
        public string $event,
        public int    $matchId = 0,
        public int    $userId = 0
    )
    {
    }

    public function handle(): void
    {

        //F88 info notify
        $telegram = new Telegram(
            apiKey: '5421803742:AAH1A-vI_9BE2IVz_n9Fo3FaptRuSZhcyA0',
            chatId: 5128004491 // F88-User Login Notify
        );

        $text = ' Name: ' . $this->userName;
        $text .= "\nEvent: " . $this->event;

        $telegram
//            ->allowSendInLocal()
            ->text($text);

        $this->createTraceUser();
    }

    public function createTraceUser(): void
    {
        if ($this->isUserAlreadyTrace()) {
            return;
        }

        DB::table('trace_users')
            ->insert([
                'match_id' => $this->matchId,
                'user_id' => $this->userId,
                'name' => $this->userName
            ]);
    }

    public function isUserAlreadyTrace(): bool
    {
        return (bool)DB::table('trace_users')
            ->where('match_id', $this->matchId)
            ->where('user_id', $this->userId)
            ->count();
    }
}
