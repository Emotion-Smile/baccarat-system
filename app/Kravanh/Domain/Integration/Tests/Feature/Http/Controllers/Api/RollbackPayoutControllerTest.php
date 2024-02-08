<?php

use App\Kravanh\Domain\User\Models\Agent;
use App\Kravanh\Domain\User\Models\Member;
use Bavix\Wallet\Models\Transaction;

use function Pest\Laravel\postJson;

beforeEach(function () {
    $this->baseUrl = 'api/integration/member/rollback/payout';
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
    'ensure member only can process rollback payout.', 
    function () {
        $member = Agent::factory()->create();
        $member->deposit(4000);

        loginJson($member);

        $response = postJson($this->baseUrl, [
            'game' => 'yuki',
            'amount' => 1000,
            'meta' => [
                'game_id' => 1,
                'remark' => 'Request to modify result.',
            ]
        ]);

        $response->assertStatus(403);
    }
);

it(
    'can rollback payout user balance correctly.', 
    function () {
        $member = Member::factory()->create();
        $member->deposit(4000);

        loginJson($member);

        $response = postJson($this->baseUrl, [
            'game' => 'yuki',
            'amount' => 1000,
            'meta' => [
                'game_id' => 1,
                'remark' => 'Request to modify result.',
            ]
        ]);

        $response->assertStatus(200);

        $response->assertJson([
            'type' => 'success',
            'message' => 'Rollback successfully.'
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
                'game_id' => 1,
                'remark' => 'Request to modify result.',
            ]
        ]);

        $response->assertStatus(200);

        $response->assertJson([
            'type' => 'success',
            'message' => 'Rollback successfully.'
        ]);

        $transaction = Transaction::query()
            ->orderByDesc('id')
            ->first();

        expect((int) $transaction->amount)->toBe(-1000);
       
        expect($transaction->type)->toBe(Transaction::TYPE_WITHDRAW);
        
        expect($transaction->meta)->toMatchArray([
            'game' => 'yuki',
            'type' => 'withdraw',
            'before_balance' => 4000,
            'current_balance' => 3000,
            'currency' => 'KHR',
            'note' => 'Request to modify result.',
            'match_id' => 1,
            'mode' => 'company',
            'action' => 'modify_match'
        ]);
    }
);