<?php

namespace App\Kravanh\Support\External\Trader;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class F88Trader
{

    const MAX_RETRY_TIME = 3;
    const MAX_WAIT_IN_MS = 500;

    public static function getToken(int $groupId): string|null
    {
        return appGetSetting('f88_token_' . $groupId);
    }

    public static function make(): static
    {
        return new static();
    }

    public function createNewMatch(array $payload): void
    {
        $response = Http::withToken(self::getToken($payload['groupId']))
            ->retry(self::MAX_RETRY_TIME, self::MAX_WAIT_IN_MS)
            ->acceptJson()
            ->post(route('api.match.new'),
                [
                    'totalPayout' => $payload['totalPayout'],
                    'meronPayout' => $payload['meronPayout'],
                    'fightNumber' => $payload['fightNumber']
                ]
            );

        Log::debug('startMatch => ' . $response->body());
    }

    public function endMatch(array $payload): void
    {

        $response = Http::withToken(self::getToken($payload['groupId']))
            ->retry(self::MAX_RETRY_TIME, self::MAX_WAIT_IN_MS)
            ->acceptJson()
            ->post(
                route('api.match.submit-result'),
                [
                    'result' => $payload['result']
                ]
            );

        Log::debug('endMatch => ' . $response->body());
    }

    public function openBet(array $payload): void
    {
        $response =
            Http::withToken(self::getToken($payload['groupId']))
                ->retry(self::MAX_RETRY_TIME, self::MAX_WAIT_IN_MS)
                ->acceptJson()
                ->post(route('api.match.toggle-bet'), [
                    'betStatus' => true
                ]);

        Log::debug('openBet => ' . $response->body());
    }

    public function closeBet(array $payload): void
    {
        $response = Http::withToken(self::getToken($payload['groupId']))
            ->retry(self::MAX_RETRY_TIME, self::MAX_WAIT_IN_MS)
            ->acceptJson()
            ->post(route('api.match.toggle-bet'), [
                'betStatus' => false
            ]);

        Log::debug('closeBet => ' . $response->body());
    }
}
