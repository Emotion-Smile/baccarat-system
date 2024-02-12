<?php

use App\Kravanh\Domain\User\Models\Member;
use App\Kravanh\Domain\User\Models\Trader;
use App\Models\User;
use Database\Seeders\UserTestSeeder;
use Illuminate\Contracts\Auth\Authenticatable;
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\actingAs;

uses(Tests\TestCase::class)->in(
    'Feature',
    'Actions',
    'Unit',
    'Components',
    'IpInfo',
    'Game',
    'DragonTiger',
    'Baccarat',
    '../app/Kravanh/Domain/IpInfo/tests',
    '../app/Kravanh/Domain/GroupUser/tests',
    '../app/Kravanh/Domain/Environment/tests',
    '../app/Kravanh/Domain/UserOption/tests',
    '../app/Kravanh/Domain/WalletBackup/tests',
    '../app/Kravanh/Domain/BetCondition/tests',
    '../app/Kravanh/Domain/Game/tests',
    '../app/Kravanh/Domain/DragonTiger/tests',
    '../app/Kravanh/Domain/Baccarat/tests',
    '../app/Kravanh/Domain/Integration/Tests',
    '../nova-components/Report/tests'
);

uses(Tests\TestCaseDatabaseMigration::class)
    ->in('ParallelRequest', 'Transactions');

function login(User $user = null)
{
    actingAs($user ?? User::factory()->create());
}

function novaLogin(User $user)
{
    actingAs($user, 'web');
}

function loginAsMember(string $name = 'member_1'): Member
{
    $member = Member::whereName($name)->first();
    cache()->set('lang:'.$member->id, 'en');

    actingAs($member);

    return $member;
}

function loginAsTrader(string $name = 'trader'): Trader
{
    $trader = Trader::whereName($name)->first();

    actingAs($trader);

    return $trader;
}

function loginJson(User $user, array $abilities = [], string $guard = 'sanctum'): Authenticatable
{
    return Sanctum::actingAs(
        user: $user,
        abilities: $abilities,
        guard: $guard
    );
}

function setupUser(string $currency, int $deposit = 10_000_000): void
{
    (new UserTestSeeder())
        ->callWith(
            UserTestSeeder::class,
            ['currency' => $currency, 'deposit' => $deposit]
        );
}
