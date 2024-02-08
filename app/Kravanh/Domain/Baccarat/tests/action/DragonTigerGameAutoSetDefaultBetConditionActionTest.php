<?php

use App\Kravanh\Domain\DragonTiger\Actions\DragonTigerGameAutoSetDefaultBetConditionAction;
use App\Kravanh\Domain\DragonTiger\Models\DragonTigerGame;
use App\Kravanh\Domain\DragonTiger\tests\DragonTigerTestHelper;
use App\Kravanh\Domain\Game\Database\Seeders\GameSeeder;
use App\Kravanh\Domain\Game\Models\GameTableCondition;
use App\Kravanh\Support\Enums\Currency;

use function Pest\Laravel\seed;

test('it can auto create bet condition for member', function () {

    setupUser(Currency::USD);
    seed(GameSeeder::class);

    $dragonTiger = DragonTigerGame::factory()->liveGame()->create();
    $member = DragonTigerTestHelper::member(groupId: $dragonTiger->game_table_id);

    //10, 10, 20, 10, 50
    DragonTigerTestHelper::setUpConditionForMember($member);

    GameTableCondition::where('user_id', '!=', $member->super_senior)->delete();

    expect(GameTableCondition::count())->toBe(1);

    (new DragonTigerGameAutoSetDefaultBetConditionAction())($member);

    expect(GameTableCondition::count())->toBe(5);

});
