<?php

use App\Kravanh\Domain\Baccarat\Dto\BaccaratGameMemberShareCommissionData;
use App\Kravanh\Domain\Baccarat\Dto\ShareCommissionData;
use App\Kravanh\Domain\Baccarat\Models\BaccaratGame;
use App\Kravanh\Domain\Baccarat\tests\BaccaratTestHelper;
use App\Kravanh\Domain\Game\Database\Seeders\GameSeeder;
use App\Kravanh\Domain\User\Models\Member;
use App\Kravanh\Support\Enums\Currency;
use function Pest\Laravel\seed;

test('it can create member share commission data', function () {

    setupUser(Currency::USD);
    seed(GameSeeder::class);

    $baccarat = BaccaratGame::factory()->liveGame()->create();

    //$2500
    $member = Member::whereName('member_1')->whereType('member')->first();
    $member->group_id = $baccarat->game_table_id;
    $member->saveQuietly();

    //10, 10, 20, 10, 50
    BaccaratTestHelper::setUpConditionForMember($member);

    $shareCommission = BaccaratGameMemberShareCommissionData::make($member);

    expect($shareCommission)->toBeInstanceOf(BaccaratGameMemberShareCommissionData::class)
        ->and($shareCommission->member)->toBeInstanceOf(ShareCommissionData::class)
        ->and($shareCommission->member->share)->toBe(0)
        ->and($shareCommission->member->commission)->toBe(0.001)
        ->and($shareCommission->agent->share)->toBe(50)
        ->and($shareCommission->agent->commission)->toBe(0.001)
        ->and($shareCommission->masterAgent->share)->toBe(10)
        ->and($shareCommission->masterAgent->commission)->toBe(0.001)
        ->and($shareCommission->senior->share)->toBe(20)
        ->and($shareCommission->senior->commission)->toBe(0.001)
        ->and($shareCommission->superSenior->share)->toBe(10)
        ->and($shareCommission->superSenior->commission)->toBe(0.001)
        ->and($shareCommission->company->share)->toBe(10)
        ->and($shareCommission->company->commission)->toBe(0.0)
//        ->and($shareCommission->share())->toHaveKeys(['company', 'super_senior', 'senior', 'master_agent', 'agent', 'member'])
        ->and($shareCommission->share())->toMatchArray([
            'company' => 10,
            'super_senior' => 10,
            'senior' => 20,
            'master_agent' => 10,
            'agent' => 50,
            'member' => 0
        ])->and($shareCommission->commission())->toMatchArray([
            'company' => 0,
            'super_senior' => 0.001,
            'senior' => 0.001,
            'master_agent' => 0.001,
            'agent' => 0.001,
            'member' => 0.001
        ]);

});
