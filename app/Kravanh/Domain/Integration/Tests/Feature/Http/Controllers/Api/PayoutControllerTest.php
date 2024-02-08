<?php

use App\Kravanh\Domain\User\Models\Agent;
use App\Kravanh\Domain\User\Models\Member;
use Bavix\Wallet\Models\Transaction;
use Spatie\Fork\Fork;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\postJson;

beforeEach(function () {
    $this->baseUrl = 'api/integration/member/payout';
});

it(
    'make sure user unauthenticated if user not login yet.',
    function () {
        $response = postJson($this->baseUrl);

        $response->assertStatus(401);

        $response->assertJson([
            'message' => 'Unauthenticated.',
        ]);
    }
);

it(
    'make sure valid fields.',
    function () {
        $member = Member::factory()->create();

        loginJson($member);

        $response = postJson($this->baseUrl);

        $response->assertStatus(422);

        $response->assertJson([
            'message' => 'The given data was invalid.',
            'errors' => [
                'game' => [
                    'The game field is required.',
                ],
                'amount' => [
                    'The amount field is required.',
                ],
                'meta' => [
                    'The meta field is required.',
                ],
            ],
        ]);
    }
);

it(
    'ensure member only can process payout.',
    function () {
        $member = Agent::factory()->create();
        $member->deposit(4000);

        loginJson($member);

        $response = postJson($this->baseUrl, [
            'game' => 'yuki',
            'amount' => 1000,
            'meta' => [
                'ticket_id' => '31,32,33',
                'game_code' => 1,
                'game_id' => 1,
                'result' => 'TEN',
                'remark' => 'Payout, With commission: $0.00',
            ],
        ]);

        $response->assertStatus(403);
    }
);

it(
    'can payout user balance correctly.',
    function () {
        $member = Member::factory()->create();
        $member->deposit(4000);

        loginJson($member);

        $response = postJson($this->baseUrl, [
            'game' => 'yuki',
            'amount' => 1000,
            'meta' => [
                'ticket_id' => '31,32,33',
                'game_code' => 1,
                'game_id' => 1,
                'result' => 'TEN',
                'remark' => 'Payout, With commission: $0.00',
            ],
        ]);

        $response->assertStatus(200);

        $response->assertJson([
            'type' => 'success',
            'message' => 'Payout successfully.',
        ]);

        expect($member->balanceInt)->toBe(5000);
    }
);

it(
    'ensure protected multi request at the same time.',
    function () {
        $member = Member::factory()->create();
        $member->deposit(4000);

        loginJson($member);

        Fork::new()
            ->run(
                function () {
                    return postJson($this->baseUrl, [
                        'game' => 'yuki',
                        'amount' => 1000,
                        'meta' => [
                            'ticket_id' => '31,32,33',
                            'game_code' => 1,
                            'game_id' => 1,
                            'result' => 'TEN',
                            'remark' => 'Payout, With commission: $0.00',
                        ],
                    ]);
                },

                function () {
                    sleep(1);

                    return postJson($this->baseUrl, [
                        'game' => 'yuki',
                        'amount' => 1000,
                        'meta' => [
                            'ticket_id' => '31,32,33',
                            'game_code' => 1,
                            'game_id' => 1,
                            'result' => 'TEN',
                            'remark' => 'Payout, With commission: $0.00',
                        ],
                    ]);
                }
            );

        expect($member->balanceInt)->toBe(5000);
    }
)->skip();

it(
    'can write transaction meta correctly.',
    function () {
        $member = Member::factory()->create();
        $member->deposit(4000);

        loginJson($member);

        $response = postJson($this->baseUrl, [
            'game' => 'yuki',
            'amount' => 1000,
            'meta' => [
                'ticket_id' => '31,32,33',
                'game_code' => 1,
                'game_id' => 1,
                'result' => 'TEN',
                'remark' => 'Payout, With commission: $0.00',
            ],
        ]);

        $response->assertStatus(200);

        $response->assertJson([
            'type' => 'success',
            'message' => 'Payout successfully.',
        ]);

        $transaction = Transaction::query()
            ->orderByDesc('id')
            ->first();

        expect((int) $transaction->amount)->toBe(1000);

        expect($transaction->type)->toBe(Transaction::TYPE_DEPOSIT);

        expect($transaction->meta)->toMatchArray([
            'game' => 'yuki',
            'type' => 'payout',
            'before_balance' => 4000,
            'current_balance' => 5000,
            'bet_id' => '31,32,33',
            'currency' => 'KHR',
            'remark' => 'Payout, With commission: $0.00',
            'fight_number' => 1,
            'match_id' => 1,
            'match_status' => 'TEN',
            'amount' => 1000,
            'action' => 'PayoutDepositorAction',
        ]);
    }
)->skip();

it(
    'will create payout deposited record after payout.',
    function () {
        $member = Member::factory()->create();
        $member->deposit(4000);

        loginJson($member);

        $response = postJson($this->baseUrl, [
            'game' => 'yuki',
            'amount' => 1000,
            'meta' => [
                'ticket_id' => '31,32,33',
                'game_code' => 1,
                'game_id' => 1,
                'result' => 'TEN',
                'remark' => 'Payout, With commission: $0.00',
            ],
        ]);

        $response
            ->assertStatus(200)
            ->assertJson([
                'type' => 'success',
                'message' => 'Payout successfully.',
            ]);

        $transaction = Transaction::query()
            ->orderByDesc('id')
            ->first();

        assertDatabaseHas('t88_payout_depositeds', [
            'member_id' => $member->id,
            'transaction_id' => $transaction->id,
            'game_id' => 1,
            'ticket_id' => '31,32,33',
            'depositor' => 'deposit_missing',
        ]);
    }
);

it(
    'ensure payout only one time.',
    function () {
        $member = Member::factory()->create();
        $member->deposit(4000);

        loginJson($member);

        Fork::new()
            ->run(
                function () {
                    return postJson($this->baseUrl, [
                        'game' => 'yuki',
                        'amount' => 1000,
                        'meta' => [
                            'ticket_id' => '31,32,33',
                            'game_code' => 1,
                            'game_id' => 1,
                            'result' => 'TEN',
                            'remark' => 'Payout, With commission: $0.00',
                        ],
                    ]);
                },

                function () {
                    sleep(1);

                    return postJson($this->baseUrl, [
                        'game' => 'yuki',
                        'amount' => 1000,
                        'meta' => [
                            'ticket_id' => '31,32,33',
                            'game_code' => 1,
                            'game_id' => 1,
                            'result' => 'TEN',
                            'remark' => 'Payout, With commission: $0.00',
                        ],
                    ]);
                },

                function () {
                    sleep(2);

                    return postJson($this->baseUrl, [
                        'game' => 'yuki',
                        'amount' => 1000,
                        'meta' => [
                            'ticket_id' => '31,32,33',
                            'game_code' => 1,
                            'game_id' => 1,
                            'result' => 'TEN',
                            'remark' => 'Payout, With commission: $0.00',
                        ],
                    ]);
                },

                function () {
                    sleep(3);

                    return postJson($this->baseUrl, [
                        'game' => 'yuki',
                        'amount' => 1000,
                        'meta' => [
                            'ticket_id' => '31,32,33',
                            'game_code' => 1,
                            'game_id' => 1,
                            'result' => 'TEN',
                            'remark' => 'Payout, With commission: $0.00',
                        ],
                    ]);
                }
            );

        expect($member->balanceInt)->toBe(5000);
    }
)->skip();
