<?php

use App\Kravanh\Domain\Baccarat\Models\BaccaratGame;
use App\Kravanh\Domain\Baccarat\Models\BaccaratTicket;
use App\Kravanh\Domain\Baccarat\tests\BaccaratTestHelper;
use App\Kravanh\Domain\Game\Database\Seeders\GameSeeder;
use App\Kravanh\Support\Enums\Currency;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\seed;

test('successful rebetting in dragon tiger game', function () {

    setupUser(Currency::USD);
    seed(GameSeeder::class);

    $dragonTigerGame = BaccaratGame::factory()->create();
    $member = BaccaratTestHelper::member(groupId: $dragonTigerGame->game_table_id);

    BaccaratTestHelper::setUpConditionForMember($member);

    BaccaratTicket::factory([
        'dragon_tiger_game_id' => $dragonTigerGame->id,
        'user_id' => $member->id,
        'amount' => 20000,
    ])
        ->count(4)
        ->create();

    $liveGame = BaccaratGame::factory()->liveGame()->create();

    $response = actingAs($member)
        ->postJson(route('dragon-tiger.rebetting'));

    $rebettingTicketCount = BaccaratTicket::query()->where('dragon_tiger_game_id', $liveGame->id)->count();

    expect($rebettingTicketCount)->toBe(4)
        ->and($response['message'])->toContain('2,480.00');

});
