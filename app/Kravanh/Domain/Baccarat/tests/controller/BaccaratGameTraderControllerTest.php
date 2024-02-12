<?php

use App\Kravanh\Domain\User\Models\Trader;
use Inertia\Testing\Assert;

use function Pest\Laravel\actingAs;

test('it can render trader page', function () {

    actingAs(Trader::factory(['two_factor_secret' => 'dragon_tiger'])->create(), 'member')
        ->get(route('dragon-tiger.trader'))
        ->assertInertia(fn (Assert $page) => $page
            ->component('DragonTiger/Trader/Index')
            ->hasAll([
                'table',
                'gameState',
                'scoreboard',
                'scoreboardCount',
                'betSummary']
            ));
});
