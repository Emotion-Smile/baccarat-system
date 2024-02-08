<?php

use App\Kravanh\Domain\DragonTiger\Models\DragonTigerGame;
use App\Kravanh\Domain\DragonTiger\Models\DragonTigerTicket;
use App\Kravanh\Domain\DragonTiger\tests\DragonTigerTestHelper;
use App\Kravanh\Domain\Game\Database\Seeders\GameSeeder;
use App\Kravanh\Support\Enums\Currency;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\seed;

test('successful rebetting in dragon tiger game', function () {

    setupUser(Currency::USD);
    seed(GameSeeder::class);

    $dragonTigerGame = DragonTigerGame::factory()->create();
    $member = DragonTigerTestHelper::member(groupId: $dragonTigerGame->game_table_id);

    DragonTigerTestHelper::setUpConditionForMember($member);

    DragonTigerTicket::factory([
        'dragon_tiger_game_id' => $dragonTigerGame->id,
        'user_id' => $member->id,
        'amount' => 20000,
    ])
        ->count(4)
        ->create();

    $liveGame = DragonTigerGame::factory()->liveGame()->create();

    $response = actingAs($member)
        ->postJson(route('dragon-tiger.rebetting'));

    $rebettingTicketCount = DragonTigerTicket::query()->where('dragon_tiger_game_id', $liveGame->id)->count();

    expect($rebettingTicketCount)->toBe(4)
        ->and($response['message'])->toContain('2,480.00');

});
