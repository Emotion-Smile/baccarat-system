<?php

use App\Kravanh\Domain\Environment\Models\Group;
use App\Kravanh\Domain\User\Models\Member;

use function Pest\Laravel\get;
use function Pest\Laravel\postJson;


beforeEach(function () {
    $this->baseUrl = 'api/integration/member/mark/as/offline';
});

it(
    'make sure user unauthenticated if user not login yet.', 
    function () {
        postJson($this->baseUrl)
            ->assertStatus(401)
            ->assertJson([
                'message' => 'Unauthenticated.'
            ]);
    }
);

it(
    'can mark as member offline.', 
    function () {
        $group = Group::factory()->createQuietly();

        $member = Member::factory()
            ->state([
                'group_id' => $group->id, 
                'name' => 'ltest'
            ])
            ->createQuietly();

        loginAsMember('ltest');

        get('/member')
            ->assertInertia(fn ($page) => $page->component('Member/Cockfight'));

        loginJson($member);

        postJson($this->baseUrl)
            ->assertStatus(200)
            ->assertJson([
                'message' => 'Mark as offline successfully.'
            ]);

        get('/member')
            ->assertInertia(fn ($page) => $page->component('Member/Cockfight'));
    }
)
    ->skip();