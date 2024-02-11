<?php

use App\Kravanh\Domain\Baccarat\Support\Middleware\EnsureBaccaratGameTableIdOnMember;
use App\Kravanh\Domain\Game\Database\Seeders\GameSeeder;
use Illuminate\Http\Request;

use function Pest\Laravel\seed;

it('allows request to proceed when user is on dragon tiger game table and it is available', function () {

    $user = Mockery::mock();
    $user->shouldReceive('isBaccaratGameTable')->andReturn(true);
    $user->shouldReceive('isDragonTraderGameTableAvailable')->andReturn(true);

    $request = new Request();
    $request->setUserResolver(fn () => $user);

    $middleware = new EnsureBaccaratGameTableIdOnMember();

    $response = $middleware->handle($request, fn ($request) => 'next');

    expect($response)->toBe('next');
});

it('sets up user for game when user is not on dragon tiger game table', function () {
    seed(GameSeeder::class);
    $user = Mockery::mock();
    $user->shouldReceive('isBaccaratGameTable')->andReturn(false);
    $user->shouldReceive('isDragonTraderGameTableAvailable')->andReturn(true);
    $user->shouldReceive('saveQuietly');

    $request = new Request();
    $request->setUserResolver(fn () => $user);

    $middleware = new EnsureBaccaratGameTableIdOnMember();

    $response = $middleware->handle($request, fn ($request) => 'next');

    expect($response)->toBe('next');
});

it('sets up user for game when dragon tiger game table is not available', function () {

    seed(GameSeeder::class);

    $user = Mockery::mock();
    $user->shouldReceive('isBaccaratGameTable')->andReturn(true);
    $user->shouldReceive('isDragonTraderGameTableAvailable')->andReturn(false);
    $user->shouldReceive('saveQuietly');

    $request = new Request();
    $request->setUserResolver(fn () => $user);

    $middleware = new EnsureBaccaratGameTableIdOnMember();

    $response = $middleware->handle($request, fn ($request) => 'next');

    expect($response)->toBe('next');
});
