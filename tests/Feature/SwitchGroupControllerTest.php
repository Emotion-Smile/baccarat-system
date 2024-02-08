<?php

use App\Kravanh\Application\Member\Controllers\SwitchGroupController;
use App\Kravanh\Domain\User\Models\Member;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\putJson;

it('switches the user group and redirects on success', function () {
    $user = Member::factory()->create();
    actingAs($user);

    $response = putJson(action(SwitchGroupController::class), ['groupId' => 2]);

    $response->assertStatus(200);

    expect($user->fresh()->group_id)->toBe(2)
        ->and($user->fresh()->two_factor_secret)->toBeNull();
});

it('returns an error when group id is not provided', function () {
    $user = Member::factory()->create();
    actingAs($user);

    $response = putJson(action(SwitchGroupController::class));

    $response->assertStatus(422);
    $response->assertJsonValidationErrors('groupId');
});

it('returns an error when group id is not numeric', function () {
    $user = Member::factory()->create();
    actingAs($user);

    $response = putJson(action(SwitchGroupController::class), ['groupId' => 'abc']);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors('groupId');
});
