<?php

use App\Kravanh\Domain\Baccarat\Actions\BaccaratGameMemberGetUplineShareCommissionAction;
use App\Kravanh\Domain\Baccarat\Models\BaccaratGame;
use App\Kravanh\Domain\Baccarat\tests\BaccaratTestHelper;
use App\Kravanh\Domain\Game\Database\Seeders\GameSeeder;
use App\Kravanh\Domain\User\Models\Member;
use App\Kravanh\Support\Enums\Currency;
use function Pest\Laravel\seed;

test("it can get member upline share commission", function () {

    setupUser(Currency::USD);
    seed(GameSeeder::class);
    $dragonTiger = BaccaratGame::factory()->liveGame()->create();

    //$2500
    $member = Member::whereName('member_1')->whereType('member')->first();
    $member->group_id = $dragonTiger->game_table_id;
    $member->saveQuietly();

    //10, 10, 20, 10, 50
    BaccaratTestHelper::setUpConditionForMember($member);

    $shareCommission = (new BaccaratGameMemberGetUplineShareCommissionAction())($member);
    $userType = ['super_senior', 'senior', 'master_agent', 'agent', 'member'];

    expect($shareCommission->count())->toBe(5)
        ->and($shareCommission->map(fn($item) => $item->user_type)->toArray())->toMatchArray($userType);

});
