<?php

use App\Kravanh\Domain\DragonTiger\App\Member\Controllers\DragonTigerGameMemberController;
use App\Kravanh\Domain\DragonTiger\tests\DragonTigerTestHelper;
use App\Kravanh\Domain\Game\Database\Seeders\GameSeeder;
use App\Kravanh\Support\Enums\Currency;
use Inertia\Testing\Assert;

use function Pest\Laravel\get;
use function Pest\Laravel\seed;

beforeEach(function () {
    seed(['DatabaseSeeder']);
    seed(GameSeeder::class);
    setupUser(Currency::KHR);
});

test('it will redirect to cockfight route if member is not allowed to play dragon tiger', function () {
    loginAsMember();
    get(route('dragon-tiger'))->assertRedirect('member');
});

test('it can render dragon tiger page', function () {

    $member = loginAsMember();

    DragonTigerTestHelper::setUpConditionForMember($member);

    get(action(DragonTigerGameMemberController::class))
        ->assertInertia(
            fn (Assert $page) => $page
                ->component('DragonTiger/Member/DragonTiger')
                ->hasAll([
                    'allTable',
                    'table',
                    'gameState',
                    'chips',
                    'outstandingTickets',
                    'scoreboard',
                    'scoreboardCount',
                    'memberBetState',
                    'betLimit',
                ])
        );
});
