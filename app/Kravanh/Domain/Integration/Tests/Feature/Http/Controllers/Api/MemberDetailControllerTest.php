<?php

use App\Kravanh\Domain\User\Models\Agent;
use App\Kravanh\Domain\User\Models\Member;

use function Pest\Laravel\getJson;

beforeEach(function () {
    $this->baseUrl = 'api/integration/member/detail';
});

it(
    'make sure user unauthenticated if user not login yet.', 
    function () {
        $response = getJson($this->baseUrl);

        $response->assertStatus(401);

        $response->assertJson([
            'message' => 'Unauthenticated.'
        ]);
    }
);

it(
    'ensure member only can get detail.', 
    function () {
        $member = Agent::factory()->create();
        $member->deposit(40000);

        loginJson($member);

        $response = getJson($this->baseUrl);

        $response->assertStatus(403);
    }
);

it(
    'can get member detail.', 
    function () {
        $member = Member::factory()->create();
        $member->deposit(40000);

        loginJson($member);

        $response = getJson($this->baseUrl);

        $response->assertStatus(200);

        $response->assertJson([
            'name' => $member->name,
            'balance' => 40000,
            'currency' => 'KHR',
        ]);
    }
);