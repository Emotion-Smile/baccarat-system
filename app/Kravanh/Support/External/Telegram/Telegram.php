<?php

namespace App\Kravanh\Support\External\Telegram;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class Telegram
{
    protected bool $allowSendInLocal = false;

    public function __construct(
        protected string  $apiKey,
        protected ?string $chatId = null
    )
    {
    }

    protected function botEndpoint(): string
    {
        //https://api.telegram.org/bot:5421803742:AAH1A-vI_9BE2IVz_n9Fo3FaptRuSZhcyA0/sendMessage?chat_id=
        return 'https://api.telegram.org/bot' . $this->apiKey . '/sendMessage?chat_id=';
    }

    public static function send(string $chatId = null): self
    {
        if (app()->environment('local')) {
            $chatId = 5128004491;
        }

        return new Telegram(
            apiKey: config('services.kravanh_telegram.apiKey'),
            chatId: $chatId ?? config('services.kravanh_telegram.chatId')
        );
    }

    public function setApiKey(string $apiKey): self
    {
        $this->apiKey = $apiKey;
        return $this;
    }

    public function to(string $chatId): self
    {
        $this->chatId = $chatId;
        return $this;
    }

    public function text(string $message): ?Response
    {

        $endpoint = $this->botEndpoint() . $this->chatId . '&text=' . $message;
        if (app()->environment('staging')) {
            return Http::get($endpoint);
        }

        if (app()->runningUnitTests()) {
            return null;
        }

        //$endpoint = "https://api.telegram.org/bot1871663595:AAEjPrJsT9vIMcEQjxrwwgAKBkIP1k2z4tM/sendMessage?chat_id={$userId}&text={$message}";
        return Http::get($endpoint);
    }

    public function textMarkDownV2(string $message): ?Response
    {
        $endpoint = $this->botEndpoint() . $this->chatId . '&text=' . $message . '&parse_mode=MarkdownV2';

        if (app()->environment('staging')) {
            return Http::get($endpoint);
        }

        if (app()->runningUnitTests()) {
            return null;
        }

        return Http::get($endpoint);
    }

    public function allowSendInLocal(): static
    {
        $this->allowSendInLocal = true;
        return $this;
    }
}
