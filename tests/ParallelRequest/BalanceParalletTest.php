<?php

use App\Kravanh\Domain\User\Models\Member;
use Bavix\Wallet\Models\Transaction;
use Illuminate\Contracts\Cache\LockTimeoutException;
use Illuminate\Http\Response;
use Recca0120\LaravelParallel\ParallelRequest;
use function Pest\Laravel\postJson;

function buildBalanceVerify(): array
{
    return [
        [
            'before_balance' => 1000,
            'current_balance' => 900,
        ],
        [
            'before_balance' => 900,
            'current_balance' => 800,
        ],
        [
            'before_balance' => 800,
            'current_balance' => 700,
        ],
        [
            'before_balance' => 700,
            'current_balance' => 600,
        ],
        [
            'before_balance' => 600,
            'current_balance' => 500,
        ],
        [
            'before_balance' => 500,
            'current_balance' => 400,
        ],
        [
            'before_balance' => 400,
            'current_balance' => 300,
        ],
        [
            'before_balance' => 300,
            'current_balance' => 200,
        ],
        [
            'before_balance' => 200,
            'current_balance' => 100,
        ],
        [
            'before_balance' => 100,
            'current_balance' => 0,
        ],
    ];
}

test('it can normal withdraw', function () {
    $member = Member::factory()->create();

    $member->deposit(1000, ['current_balance' => 1000]);

    $response = postJson(route('test.withdraw'), [
        'amount' => 100,
        'id' => $member->id,
    ]);

    expect($response->json(['cacheBalance']))
        ->toBe(900)
        ->and($response->json(['balance']))
        ->toBe('900');
});

test('it can handle 10 parallel withdraw without error balance', function () {
    $member = Member::factory()->create();

    $member->deposit(1000, [
        'before_balance' => 0,
        'current_balance' => 1000,
    ]);

    $request = app()->make(ParallelRequest::class);

    $promises = collect($request->times(10)->postJson(route('test.withdraw'), [
        'amount' => 100,
        'id' => $member->id,
    ]));

    $promises->map->wait()->each(fn(Response $res) => expect($res->status())->toBe(200));

    $transactions = Transaction::query()
        ->where('payable_id', $member->id)
        ->where('payable_type', get_class($member))
        ->where('type', Transaction::TYPE_WITHDRAW)
        ->get();

    $balanceVerify = buildBalanceVerify();
    foreach ($transactions as $index => $transaction) {
        expect($transaction->meta['before_balance'])->toBe($balanceVerify[$index]['before_balance']);
    }

    expect($transactions->count())->toBe(10);

    $availableAmount = Transaction::query()
        ->where('payable_id', $member->id)
        ->where('payable_type', get_class($member))
        ->sum('amount');

    expect($availableAmount)->toBe(0);
});

test('it can 3 parallel withdraw success 1 failed 2 and throw lock time out exception', function () {
    $member = Member::factory()->create();

    $member->deposit(1000, [
        'before_balance' => 0,
        'current_balance' => 1000,
    ]);

    $request = app()->make(ParallelRequest::class);

    $promises = collect();

    $balanceVerify = [
        [
            'before_balance' => 1000,
            'current_balance' => 900,
        ],
        [
            'before_balance' => 900,
            'current_balance' => 800,
        ],
        [
            'before_balance' => 800,
            'current_balance' => 700,
        ],
    ];

    for ($req = 0; $req < 3; $req++) {
        $promise = $request->postJson(
            route('test.withdraw-lock-timeout-exception'),
            [
                'amount' => 100,
                'id' => $member->id,
                'req' => $req,
            ]
        );
        $promises->add($promise);
    }

    $promises->map->wait();

    $transactions = Transaction::query()
        ->where('payable_id', $member->id)
        ->where('payable_type', get_class($member))
        ->where('type', Transaction::TYPE_WITHDRAW)
        ->get();

    foreach ($transactions as $index => $transaction) {
        expect($transaction->meta['before_balance'])->toBe($balanceVerify[$index]['before_balance']);
    }

    expect($transactions->count())->toBe(1);

    $availableAmount = Transaction::query()
        ->where('payable_id', $member->id)
        ->where('payable_type', get_class($member))
        ->sum('amount');

    expect((int)$availableAmount)->toBe(900);
})->expect(LockTimeoutException::class);

test('it can handle 10 parallel deposit without error balance', function () {
    $member = Member::factory()->create();

    $request = app()->make(ParallelRequest::class);

    $promises = collect();

    $balanceVerify = [
        [
            'before_balance' => 0,
            'current_balance' => 100,
        ],
        [
            'before_balance' => 100,
            'current_balance' => 200,
        ],
        [
            'before_balance' => 200,
            'current_balance' => 300,
        ],
        [
            'before_balance' => 300,
            'current_balance' => 400,
        ],
        [
            'before_balance' => 400,
            'current_balance' => 500,
        ],
        [
            'before_balance' => 500,
            'current_balance' => 600,
        ],
        [
            'before_balance' => 600,
            'current_balance' => 700,
        ],
        [
            'before_balance' => 700,
            'current_balance' => 800,
        ],
        [
            'before_balance' => 800,
            'current_balance' => 900,
        ],
        [
            'before_balance' => 900,
            'current_balance' => 1000,
        ],
    ];

    for ($req = 0; $req < 10; $req++) {
        $promise = $request->postJson(
            route('test.deposit'),
            [
                'amount' => 100,
                'id' => $member->id,
                'req' => $req,
            ]
        );
        $promises->add($promise);
    }

    $promises->map->wait()->each(fn(Response $res) => expect($res->status())->toBe(200));

    $transactions = Transaction::query()
        ->where('payable_id', $member->id)
        ->where('payable_type', get_class($member))
        ->where('type', Transaction::TYPE_DEPOSIT)
        ->get();

    foreach ($transactions as $index => $transaction) {
        expect($transaction->meta['before_balance'])->toBe($balanceVerify[$index]['before_balance']);
    }

    expect($transactions->count())->toBe(10);

    $availableAmount = Transaction::query()
        ->where('payable_id', $member->id)
        ->where('payable_type', get_class($member))
        ->sum('amount');

    expect((int)$availableAmount)->toBe(1000);
});
