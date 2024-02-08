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

    \Pest\Laravel\get(route('member'))
        ->assertInertia(fn(Assert $page) => $page
            ->component('Member/Cockfight')
            ->hasAll([
                'betRecords',
                'betValueRange',
                'matchInfo',
                'resultCount',
                'resultSymbols',
                'betConfiguration',
                'groups',
            ])
        //->where('groups.0', fn($group) => $group['name'] === 'CockFight')
        );
});
