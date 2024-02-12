<?php

use App\Kravanh\Domain\Baccarat\App\Member\Controllers\BaccaratGameMemberController;
use App\Kravanh\Domain\Baccarat\tests\BaccaratTestHelper;
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

    BaccaratTestHelper::setUpConditionForMember($member);

    get(action(BaccaratGameMemberController::class))
        ->assertInertia(
            fn (Assert $page) => $page
                ->component('Baccarat/Member/Baccarat')
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
