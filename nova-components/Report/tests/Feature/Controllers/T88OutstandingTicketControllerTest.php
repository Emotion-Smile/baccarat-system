<?php

use App\Kravanh\Domain\User\Models\MasterAgent;
use Illuminate\Support\Facades\Http;
use Illuminate\Testing\Fluent\AssertableJson;
use KravanhEco\Report\Http\Controllers\T88OutstandingTicket\T88OutstandingTicketController;

use function Pest\Laravel\getJson;

beforeEach(function () {
    config([
        't88.base_url' => 'https://dev-loto1.kravanh.co',
        't88.api_prefix' => '/api/v1'
    ]);

    $user = MasterAgent::factory(['name' => 'ma_khr'])->createQuietly();

    Http::fake([
        'https://dev-loto1.kravanh.co/api/v1/login' => Http::response([
            'token' => '1|jP2heSoLMmt99u7kww30RgmlHhU00J1eQQdlHXM7'
        ], 201),

        "https://dev-loto1.kravanh.co/api/v1/outstanding-bets?name={$user->name}&type=master_agent&page=1" => Http::response([
            'data' => [
                [
                    'id' => 131,
                    'name' => 'mm_khr',
                    'ip_address' => '167.179.40.169',
                    'game_code' => '001 - 00009231791',
                    'bet' => 'SIX',
                    'amount' => '10000',
                    'rate' => 11,
                    'amount_text' => '៛10,000',
                    'total_amount_text' => '៛110,000',
                    'date_time' => '2023-09-23 12:56:29 PM',
                ],
                [
                    'id' => 132,
                    'name' => 'mm_khr',
                    'ip_address' => '167.179.40.169',
                    'game_code' => '001 - 00009231891',
                    'bet' => 'SEVEN',
                    'amount' => '5000',
                    'rate' => 11,
                    'amount_text' => '៛5,000',
                    'total_amount_text' => '៛55,000',
                    'date_time' => '2023-09-23 12:56:29 PM',
                ]
            ],

            'links' => [
                'first' => 'https://dev-loto1.kravanh.co/api/v1/outstanding-bets?page=1',
                'last' => 'https://dev-loto1.kravanh.co/api/v1/outstanding-bets?page=1',
                'prev' => null,
                'next' => null,
            ],

            'meta' => [
                'current_page' => 1,
                'from' => 1,
                'last_page' => 1,
                'links' => [
                    [
                        'url' => null,
                        'label' => '&laquo; Previous',
                        'active' => false,
                    ],
                    [
                        'url' => 'https://dev-loto1.kravanh.co/api/v1/outstanding-bets?page=1',
                        'label' => '1',
                        'active' => true,
                    ],
                    [
                        'url' => null,
                        'label' => 'Next &raquo;',
                        'active' => false,
                    ],
                ],
                'path' => 'https://dev-loto1.kravanh.co/api/v1/outstanding-bets',
                'per_page' => 15,
                'to' => 5,
                'total' => 5,
            ]
        ], 200)
    ]);

    novaLogin($user);
});

it('can fetch data correctly.', function () {
    getJson(action(T88OutstandingTicketController::class))
        ->assertOk()
        ->assertJson(function (AssertableJson $json) {
            $json
                ->has('outstandingTicket', 3)
                ->has('outstandingTicket.data', 2)
                ->has('outstandingTicket.data.0', function (AssertableJson $json) {
                    $json->has('id')
                        ->has('name')
                        ->has('ip_address')
                        ->has('game_code')
                        ->has('bet')
                        ->has('amount')
                        ->has('rate')
                        ->has('amount_text')
                        ->has('total_amount_text')
                        ->has('date_time')
                        ->etc();
                });
        });
});
