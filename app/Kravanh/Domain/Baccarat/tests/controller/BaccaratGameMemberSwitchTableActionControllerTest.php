<?php

use App\Kravanh\Domain\Baccarat\App\Member\Controllers\BaccaratGameMemberSwitchTableActionController;
use App\Kravanh\Domain\Baccarat\Support\Middleware\EnsureUserCanPlayBaccaratGame;
use App\Kravanh\Domain\Game\Database\Seeders\GameSeeder;
use App\Kravanh\Domain\User\Models\Member;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\putJson;
use function Pest\Laravel\seed;

it('switches the user to the specified table successfully', function () {
    seed(GameSeeder::class);

    $user = Member::factory()->create();

    actingAs($user);
    $this->withoutMiddleware([EnsureUserCanPlayBaccaratGame::class]);
    $response = putJson(action(BaccaratGameMemberSwitchTableActionController::class), ['tableId' => 3]);

    $response->assertStatus(302);

    expect($user->fresh()->group_id)->toBe(3)
        ->and($user->fresh()->two_factor_secret)->toBe('dragon_tiger');

});

it('returns an error when tableId is not provided', function () {

    seed(GameSeeder::class);
    $user = Member::factory()->create();
    actingAs($user);

    $this->withoutMiddleware([EnsureUserCanPlayBaccaratGame::class]);
    $response = putJson(action(BaccaratGameMemberSwitchTableActionController::class));

    $response->assertStatus(422);
    $response->assertJsonValidationErrors('tableId');
});

it('returns an error when tableId is not numeric', function () {

    seed(GameSeeder::class);
    $user = Member::factory()->create();
    actingAs($user);

    $this->withoutMiddleware([EnsureUserCanPlayBaccaratGame::class]);
    $response = putJson(action(BaccaratGameMemberSwitchTableActionController::class), ['tableId' => 'abc']);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors('tableId');
});
