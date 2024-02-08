<?php

namespace App\Kravanh\Support\External\Trader;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class As88Trader
{
    protected string $baseUrl = 'http://localhost:8181/api/auto-trader/';

    protected array $token = [
        1 => '3|PF73LG298CJw3x8wxE6Iy1DlYGytd4XTh8IZd8LK',
        2 => '2',
        4 => '3'
    ];

    protected array $productionToken = [
        1 => '1|EsFmC8IgNoDm68ATvTigGUVUwNxYb0Znn64ODwfi',
        2 => '2|sFKCfKqD8vapfpkrwLopr3r4mEqpc2scszO1MrYW',
        4 => '3|angFcbf7MIiXhdY6LW974WJlezo6QAzz8E2CCXEt',
        5 => '4|PXjSnTPEvvSHFjFP6Rprv661xJiT55cG4GZHfYU1',
        6 => '5|SiqZe1z7GizSTN16RvpqZgqfrsNCSlfdnRBWXpgj'
    ];


    public function __construct()
    {
        if (App::environment('staging')) {

            $this->baseUrl = 'https://as88.live/api/auto-trader/';
            $this->token = $this->productionToken;

            Log::debug('staging => ' . $this->baseUrl);
        }
    }

    public static function action(): static
    {
        return (new static());
    }

    public function startMatch(array $payload): void
    {
        $response = Http::withToken($this->token[$payload['groupId']])
            ->acceptJson()
            ->post(
                $this->baseUrl . 'start-new-match',
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

        $response = Http::withToken($this->token[$payload['groupId']])
            ->acceptJson()
            ->post(
                $this->baseUrl . 'end-match',
                [
                    'result' => $payload['result']
                ]
            );

        Log::debug('endMatch => ' . $response->body());
    }

    public function openBet(array $payload): void
    {
        $response = Http::withToken($this->token[$payload['groupId']])
            ->acceptJson()
            ->post($this->baseUrl . 'open-bet', [
                'betStatus' => true
            ]);

        Log::debug('openBet => ' . $response->body());
    }

    public function closeBet(array $payload): void
    {
        $response = Http::withToken($this->token[$payload['groupId']])
            ->acceptJson()
            ->post($this->baseUrl . 'close-bet', [
                'betStatus' => false
            ]);

        Log::debug('closeBet => ' . $response->body());
    }

    public function adjustPayout(array $payload): void
    {
        $response = Http::withToken($this->token[$payload['groupId']])
            ->acceptJson()
            ->post($this->baseUrl . 'adjust-payout', [
                'totalPayout' => $payload['totalPayout'],
                'meronPayout' => $payload['meronPayout'],
            ]);


        Log::debug('adjustPayout => ' . $response->body());
    }
}
