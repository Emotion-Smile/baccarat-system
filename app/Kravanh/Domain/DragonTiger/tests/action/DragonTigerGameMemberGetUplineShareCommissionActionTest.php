<?php

use App\Kravanh\Domain\DragonTiger\Actions\DragonTigerGameMemberGetUplineShareCommissionAction;
use App\Kravanh\Domain\DragonTiger\Models\DragonTigerGame;
use App\Kravanh\Domain\DragonTiger\tests\DragonTigerTestHelper;
use App\Kravanh\Domain\Game\Database\Seeders\GameSeeder;
use App\Kravanh\Domain\User\Models\Member;
use App\Kravanh\Support\Enums\Currency;
use function Pest\Laravel\seed;

test("it can get member upline share commission", function () {

    setupUser(Currency::USD);
    seed(GameSeeder::class);
    $dragonTiger = DragonTigerGame::factory()->liveGame()->create();

    //$2500
    $member = Member::whereName('member_1')->whereType('member')->first();
    $member->group_id = $dragonTiger->game_table_id;
    $member->saveQuietly();

    //10, 10, 20, 10, 50
    DragonTigerTestHelper::setUpConditionForMember($member);

    $shareCommission = (new DragonTigerGameMemberGetUplineShareCommissionAction())($member);
    $userType = ['super_senior', 'senior', 'master_agent', 'agent', 'member'];

    expect($shareCommission->count())->toBe(5)
        ->and($shareCommission->map(fn($item) => $item->user_type)->toArray())->toMatchArray($userType);

});
