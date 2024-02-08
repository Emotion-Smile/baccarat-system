<?php

use App\Kravanh\Domain\User\Models\Agent;
use App\Kravanh\Domain\User\Models\Member;
use Bavix\Wallet\Models\Transaction;

use function Pest\Laravel\postJson;

beforeEach(function () {
    $this->baseUrl = 'api/integration/member/betting';
});

it(
    'make sure user unauthenticated if user not login yet.', 
    function () {
        $response = postJson($this->baseUrl);

        $response->assertStatus(401);

        $response->assertJson([
            'message' => 'Unauthenticated.'
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
                    'The game field is required.'
                ],
                'amount' => [
                    'The amount field is required.'
                ],
                'meta' => [
                    'The meta field is required.'
                ]
            ]
        ]);
    }
);

it(
    'ensure member only can process withdraw.', 
    function () {
        $member = Agent::factory()->create();
        $member->deposit(4000);

        loginJson($member);

        $response = postJson($this->baseUrl, [
            'game' => 'yuki',
            'amount' => 1000,
            'meta' => [
                'ticket_id' => 32,
                'game_code' => 1,
                'game_id' => 1,
                'bet_on' => 'TEN',
                'remark' => 'Member bet game.',
            ]
        ]);

        $response->assertStatus(403);
    }
);

it(
    'ensure can not withdraw if amount more than current balance.', 
    function () {
        $member = Member::factory()->create();
        $member->deposit(4000);

        loginJson($member);

        $response = postJson($this->baseUrl, [
            'game' => 'yuki',
            'amount' => 5000,
            'meta' => [
                'ticket_id' => 32,
                'game_code' => 1,
                'game_id' => 1,
                'bet_on' => 'TEN',
                'remark' => 'Member bet game.',
            ]
        ]);

        $response->assertStatus(200);

        $response->assertJson([
            'type' => 'error',
            'message' => 'Insufficient funds'
        ]);
    }
);

it(
    'can withdraw user balance correctly.', 
    function () {
        $member = Member::factory()->create();
        $member->deposit(4000);

        loginJson($member);

        $response = postJson($this->baseUrl, [
            'game' => 'yuki',
            'amount' => 1000,
            'meta' => [
                'ticket_id' => 32,
                'game_code' => 1,
                'game_id' => 1,
                'bet_on' => 'TEN',
                'remark' => 'Member bet game.',
            ]
        ]);

        $response->assertStatus(200);

        $response->assertJson([
            'type' => 'success',
            'message' => 'Betting successfully.'
        ]);

        expect($member->balanceInt)->toBe(3000);
    }
);

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
                'ticket_id' => 32,
                'game_code' => 1,
                'game_id' => 1,
                'bet_on' => 'TEN',
                'remark' => 'Member bet game.',
            ]
        ]);

        $response->assertStatus(200);

        $response->assertJson([
            'type' => 'success',
            'message' => 'Betting successfully.'
        ]);

        $transaction = Transaction::query()
            ->orderByDesc('id')
            ->first();

        expect((int) $transaction->amount)->toBe(-1000);
       
        expect($transaction->type)->toBe(Transaction::TYPE_WITHDRAW);
        
        expect($transaction->meta)->toMatchArray([
            'game' => 'yuki',
            'type' => 'bet',
            'before_balance' => 4000,
            'current_balance' => 3000,
            'bet_id' => 32,
            'currency' => 'KHR',
            'remark' => 'Member bet game.',
            'fight_number' => 1,
            'match_id' => 1,
            'bet_on' => 'TEN',
            'amount' => 1000,
            'payout_rate' => 0,
            'payout' => 0
        ]);
    }
);
