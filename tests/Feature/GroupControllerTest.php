<?php

use App\Kravanh\Domain\Environment\Models\Group;
use App\Kravanh\Domain\User\Models\Member;
use App\Kravanh\Support\Enums\Currency;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

use function Pest\Laravel\getJson;
use function Pest\Laravel\seed;

beforeEach(function () {
    seed(['DatabaseSeeder']);
    Group::factory()->create();
    setupUser(Currency::USD);
    loginAsMember();
});

it('cannot view video streaming if account is blocked', function () {

    $member = Member::whereName('member_1')->first();
    Cache::put(User::KEY_BLOCK_VIDEO_STREAM.$member->id, true);

    $response = getJson(route('env.group'))
        ->assertOk();

    expect($response['message'])->not()->toBeEmpty()
        ->and($response['streamingLink'])->toBeEmpty();

    Cache::forget(User::KEY_BLOCK_VIDEO_STREAM.$member->id);

    $response = getJson(route('env.group'))
        ->assertOk();

    expect($response['streamingLink1'])->not()->toBeEmpty()
        ->and($response['message'])->toBeEmpty();

});

it('cannot view video stream if balance less than minimum allowed', function () {
    $member = Member::whereName('member_1')->first();
    $member->forceWithdraw(100_000_000);

    $response = getJson(route('env.group'))
        ->assertOk();

    expect($response['message'])->not()->toBeEmpty()
        ->and($response['streamingLink1'])->toBeEmpty();
})->skip();

it('can view video streaming', function () {

    getJson(route('env.group'))
        ->assertOk()
        ->assertJson([
            'message' => '',
            'streamingLink1' => 'https://www.youtube.com/embed/ai4yT-qLuEU',
            'showFightNumber' => false,
        ]);
});
