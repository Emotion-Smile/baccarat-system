<?php

use App\Kravanh\Domain\DragonTiger\App\Member\Controllers\DragonTigerGameMemberSwitchTableActionController;
use App\Kravanh\Domain\DragonTiger\Support\Middleware\EnsureUserCanPlayDragonTigerGame;
use App\Kravanh\Domain\Game\Database\Seeders\GameSeeder;
use App\Kravanh\Domain\User\Models\Member;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\putJson;
use function Pest\Laravel\seed;

it('switches the user to the specified table successfully', function () {
    seed(GameSeeder::class);

    $user = Member::factory()->create();

    actingAs($user);
    $this->withoutMiddleware([EnsureUserCanPlayDragonTigerGame::class]);
    $response = putJson(action(DragonTigerGameMemberSwitchTableActionController::class), ['tableId' => 3]);

    $response->assertStatus(302);

    expect($user->fresh()->group_id)->toBe(3)
        ->and($user->fresh()->two_factor_secret)->toBe('dragon_tiger');

});

it('returns an error when tableId is not provided', function () {

    seed(GameSeeder::class);
    $user = Member::factory()->create();
    actingAs($user);

    $this->withoutMiddleware([EnsureUserCanPlayDragonTigerGame::class]);
    $response = putJson(action(DragonTigerGameMemberSwitchTableActionController::class));

    $response->assertStatus(422);
    $response->assertJsonValidationErrors('tableId');
});

it('returns an error when tableId is not numeric', function () {

    seed(GameSeeder::class);
    $user = Member::factory()->create();
    actingAs($user);

    $this->withoutMiddleware([EnsureUserCanPlayDragonTigerGame::class]);
    $response = putJson(action(DragonTigerGameMemberSwitchTableActionController::class), ['tableId' => 'abc']);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors('tableId');
});
