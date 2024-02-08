<?php

use App\Kravanh\Domain\Environment\Models\Group;
use App\Kravanh\Domain\Match\Models\Spread;
use App\Kravanh\Domain\User\Models\Member;
use App\Kravanh\Support\Enums\Currency;
use App\Models\User;
use function Pest\Laravel\seed;

beforeEach(function () {
    seed(['DatabaseSeeder']);
    Group::factory()->create();
    setupUser(Currency::USD);
});

test('it can compute payout deduction correctly when member belong to spread', function () {
    $member = Member::where('name', 'member_1')->first();
    //spread b -> payout deduction = 3
    $spread = Spread::where('name', 'B')->first();

    $member->spread_id = $spread->id;
    $member->saveQuietly();

    expect($member->computePayoutDeduction(0.90))->toBe(0.87);
});

test('it can compute payout deduction correctly when agent of member belong to spread', function () {

    $member = Member::where('name', 'member_1')->first();
    //spread C -> payout deduction = 5
    $spread = Spread::where('name', 'C')->first();
    $agent = User::find($member->agent);
    $agent->spread_id = $spread->id;
    $agent->saveQuietly();

    expect($member->computePayoutDeduction(0.90))->toBe(0.85);

});


test('it can compute payout deduction correctly when agent and member has different spread', function () {

    $member = Member::where('name', 'member_1')->first();
    //spread C -> payout deduction = 5
    $spread = Spread::where('name', 'C')->first();
    $agent = User::find($member->agent);
    $agent->spread_id = $spread->id;
    $agent->saveQuietly();

    //spread b -> payout deduction = 3
    $spread = Spread::where('name', 'B')->first();
    $member->spread_id = $spread->id;
    $member->saveQuietly();


    expect($member->computePayoutDeduction(0.90))->toBe(0.87);

});

test('it can compute payout deduction correctly when spread disable', function () {

    $member = Member::where('name', 'member_1')->first();
    //spread C -> payout deduction = 5
    $spread = Spread::where('name', 'C')->first();
    $agent = User::find($member->agent);
    $agent->spread_id = $spread->id;
    $agent->saveQuietly();

    $spread->active = 0;
    $spread->saveQuietly();

    expect($member->computePayoutDeduction(0.90))->toBe(0.90);

});
