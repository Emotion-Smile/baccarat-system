<?php

use App\Kravanh\Domain\Baccarat\Actions\BaccaratGameAutoSetDefaultBetConditionAction;
use App\Kravanh\Domain\Baccarat\Models\BaccaratGame;
use App\Kravanh\Domain\Baccarat\tests\BaccaratTestHelper;
use App\Kravanh\Domain\Game\Database\Seeders\GameSeeder;
use App\Kravanh\Domain\Game\Models\GameTableCondition;
use App\Kravanh\Support\Enums\Currency;

use function Pest\Laravel\seed;

test('it can auto create bet condition for member', function () {

    setupUser(Currency::USD);
    seed(GameSeeder::class);

    $Baccarat = BaccaratGame::factory()->liveGame()->create();
    $member = BaccaratTestHelper::member(groupId: $dragonTiger->game_table_id);

    //10, 10, 20, 10, 50
    BaccaratTestHelper::setUpConditionForMember($member);

    GameTableCondition::where('user_id', '!=', $member->super_senior)->delete();

    expect(GameTableCondition::count())->toBe(1);

    (new BaccaratGameAutoSetDefaultBetConditionAction())($member);

    expect(GameTableCondition::count())->toBe(5);

});
