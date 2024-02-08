<?php

namespace App\Kravanh\Domain\User\Jobs;

use App\Kravanh\Support\External\Telegram\Telegram;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;

class UserLoginNotifyJobs implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    public function __construct(public array $payload)
    {

    }

    public function handle()
    {
        //F88 info notify
        $telegram = new Telegram(
            apiKey: '5421803742:AAH1A-vI_9BE2IVz_n9Fo3FaptRuSZhcyA0',
            chatId: -693430040 // F88-User Login Notify
        );

        $text = " name: " . $this->payload['name'];
        $text .= "\n privacy: " . $this->payload['privacy'];
        $text .= "\n country: " . $this->payload['country'];
        $text .= "\n city: " . $this->payload['city'];
        $text .= "\n region: " . $this->payload['region'];
        $text .= "\n ip: " . $this->payload['ip'];

        $telegram
//            ->allowSendInLocal()
            ->text($text);
        //send notification to telegram
    }
}
