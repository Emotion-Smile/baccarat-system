<?php

use App\Kravanh\Domain\Match\Models\BetRecord;
use App\Kravanh\Domain\Match\Models\Matches;
use App\Kravanh\Domain\Match\Supports\Enums\BetOn;
use App\Kravanh\Domain\User\Models\Member;
use App\Kravanh\Domain\User\Models\Transaction;
use Recca0120\LaravelParallel\ParallelRequest;
use function Pest\Laravel\seed;

beforeEach(function () {
    seed(['DatabaseSeeder']);
    Matches::factory()->createQuietly();
});

test('Ensure LockTimeoutException throw do correct behaviour on bet action', function () {
    /**
     * @var Member $member ;
     */
    $member = Member::factory()->create();

    $member->deposit(10000, [
        'before_balance' => 0,
        'current_balance' => 10000,
    ]);

    loginAsMember($member->name);

    expect(10000)->toBe($member->balanceInt)
        ->and(config('balance.waiting_time_in_sec'))->toBe('0');

    $firstBetAmount = 5000;
    $request = app()->make(ParallelRequest::class);
    $promises = collect();
    $token = $member->createToken('test')->plainTextToken;

    // one member bet 2 time simultaneously
    // expect 1 success and 1 fail
    // because wallet has been occupying by the first process
    for ($i = 0; $i < 2; $i++) {

        $promise = $request->postJson(route('member.match.betting'), [
            'betAmount' => $firstBetAmount,
            'betOn' => BetOn::MERON,
        ], [
            'Authorization' => 'Bearer ' . $token,
        ]);

        $promises->add($promise);
    }


    /**
     * @var \Illuminate\Support\Collection $responseStatus
     */
    $responseStatus = $promises
        ->map
        ->wait()
        ->map(fn($response) => $response->status());


    expect($responseStatus->max())->toBe(500) // LockTimeoutException
    ->and($responseStatus->min())->toBe(200)
        ->and($member->balanceInt)->toBe(5000)
        ->and((int)$member->wallet->refresh()->getOriginalBalance())->toBe(5000)
        ->and((int)$member->wallet->getAvailableBalance())->toBe(5000)
        ->and(BetRecord::count())->toBe(1)
        ->and(Transaction::count())->toBe(2);

});
