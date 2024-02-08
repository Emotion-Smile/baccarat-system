<?php

use App\Kravanh\Domain\Game\Database\Seeders\GameSeeder;
use App\Kravanh\Domain\Match\Models\Matches;
use App\Kravanh\Support\Enums\Currency;
use Inertia\Testing\Assert;
use function Pest\Laravel\seed;

beforeEach(function () {
    seed(['DatabaseSeeder']);
    seed(GameSeeder::class);
    setupUser(Currency::KHR);
    Matches::factory(['group_id' => 1])->createQuietly();
});


test('it can load with proper props', function () {
    loginAsMember();
    \Pest\Laravel\get(route('member.betting.history'))
        ->assertInertia(
            fn(Assert $page) => $page
                ->component('Member/BettingHistory')
                ->hasAll([
                    'filters',
                    'betTypeFilters',
                    'betHistoryRecords'
                ])
        );
});
