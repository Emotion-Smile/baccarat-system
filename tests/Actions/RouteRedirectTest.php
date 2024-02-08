<?php

use App\Kravanh\Domain\Game\Database\Seeders\GameSeeder;
use App\Kravanh\Domain\User\Models\Member;
use App\Kravanh\Domain\User\Models\Trader;
use function Pest\Laravel\{actingAs, get, seed};

beforeEach(/**
 * @return void
 */ function () {
    seed(['DatabaseSeeder']);
    seed(GameSeeder::class);
});

test('if guest visit / it will redirect to login page')
    ->get('/')
    ->assertRedirect('login');

test('if member visit / it will redirect to member page', function () {
    actingAs(Member::factory()->create(), 'member')
        ->get('/')
        ->assertRedirect(route('member'));
});

test('if trader cockfight visit / it will redirect to accept ticket page', function () {
    actingAs(Trader::factory()->create())
        ->get('/')
        ->assertRedirect(route('accept-ticket'));
});

test('ensure member cannot visit cockfight trader page', function () {

    actingAs(Member::factory()->create());

    get(route('accept-ticket'))
        ->assertRedirect(route('member'));

    get(route('open-bet'))
        ->assertRedirect(route('member'));

});

test('ensure trader cannot visit member page', function () {
    actingAs(Trader::factory()->create());

    $redirectRoute = route('open-bet');

    get(route('member'))
        ->assertRedirect($redirectRoute);

    get(route('member.betting.history'))
        ->assertRedirect($redirectRoute);

    get(route('member.deposit'))
        ->assertRedirect($redirectRoute);

    get(route('member.feedback'))
        ->assertRedirect($redirectRoute);

    get(route('member.messages'))
        ->assertRedirect($redirectRoute);

});

test('ensure member can visit page: member cockfight, betting history cockfight, deposit, feedback, message', function () {

    $member = Member::factory()->create();

    actingAs($member, 'member')
        ->get(route('member'))
        ->assertOk();

    get(route('member.betting.history'))->assertOk();
    get(route('member.deposit'))->assertOk();
    get(route('member.feedback'))->assertOk();
    get(route('member.messages'))->assertOk();

});

test('ensure cockfight trader can visit page: accept-ticket, open-bet', function () {

    actingAs(Trader::factory()->create(), 'member')
        ->get(route('accept-ticket'))
        ->assertOk();

    get(route('open-bet'))->assertOk();

});

test('ensure cockfight trader cannot visit page: dragon tiger trader', function () {

    actingAs(Trader::factory()->create(), 'member')
        ->get(route('dragon-tiger.trader'))
        ->assertRedirect(route('open-bet'));
});

test('ensure dragon tiger trader can visit page: dragon tiger trader', function () {

    actingAs(Trader::factory(['two_factor_secret' => 'dragon_tiger'])->create(), 'member')
        ->get(route('dragon-tiger.trader'))
        ->assertOk();
});

test('ensure dragon tiger trader cannot visit page: cockfight trader', function () {

    actingAs(Trader::factory(['two_factor_secret' => 'dragon_tiger'])->create(), 'member')
        ->get(route('open-bet'))
        ->assertRedirect(route('dragon-tiger.trader'));
});

test('ensure member can call group endpoint', function () {

    actingAs(Member::factory()->create())
        ->get(route('env.group'))
        ->assertOk();
});

test('ensure trader can call group endpoint', function () {

    actingAs(Trader::factory()->create())
        ->get(route('env.group'))
        ->assertOk();

});

test('ensure user can call user balance endpoint', function () {
    $user = \App\Kravanh\Domain\User\Models\Agent::factory()->create();

    actingAs($user)
        ->post(route('user.balance', ['id' => $user->id, 'type' => 'agent']))
        ->assertOk();
});



