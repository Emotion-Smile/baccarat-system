<?php

use App\Kravanh\Domain\User\Models\Member;
use App\Kravanh\Support\LockHelper;
use App\Models\User;
use Bavix\Wallet\Interfaces\Storable;
use Illuminate\Support\Facades\DB;
use Spatie\Fork\Fork;

test('it can handle parallel deposit and withdraw', function () {
    $user1 = User::factory()->create();
    $isError = false;

    Config::set('balance.waiting_time_in_sec', 10);

    try {

        Fork::new()->run(
            function () use ($user1) {
                $block = LockHelper::lockWallet($user1->id);
                $block->block(config('balance.waiting_time_in_sec'));
                $user1->deposit(100);
                $block->release();
            },
            function () use ($user1) {
                $block = LockHelper::lockWallet($user1->id);
                $block->block(config('balance.waiting_time_in_sec'));
                $user1->deposit(100);
                $block->release();
            },
            function () use ($user1) {
                $block = LockHelper::lockWallet($user1->id);
                $block->block(config('balance.waiting_time_in_sec'));
                $user1->deposit(100);
                $block->release();
            },
            function () use ($user1) {
                $block = LockHelper::lockWallet($user1->id);
                $block->block(config('balance.waiting_time_in_sec'));
                $user1->withdraw(100);
                $block->release();
            }
        );

    } catch (Exception $e) {
        //SQLSTATE[HY000]: General error: 2006 MySQL server has gone away
        //SQLSTATE[HY093]:
        //There is no active transaction
        //$isCode = in_array($e->getCode(), [0, 'HY000', 'HY093', '23000', '42000']);
        $isError = true;

    }

    expect($isError)->toBeFalse()
        ->and($user1->balanceInt)
        ->toBe(200);
});

test('it can handle parallel withdraw', function () {

    app(Storable::class)->fresh();

    $member1 = Member::factory()->create();
    $member2 = Member::factory()->create();

    $member1->deposit(1000);
    $member2->deposit(1000);

    $isError = false;

    try {

        Fork::new()
            ->before(fn () => DB::connection('mysql')->reconnect())
            ->concurrent(2)
            ->run(
                function () use ($member1) {
                    $member1->withdraw(100);
                },
                function () use ($member2) {
                    $member2->withdraw(100);
                }
            );

    } catch (Exception $e) {
        ray($e->getMessage());
        $isError = true;
    }

    expect($member1->balanceInt)->toBe(900)
        ->and($member2->balanceInt)->toBe(900);

})->skip();

test('it can handle parallel deposit different account', function () {

    //    Config::set('database.connections.mysql.options', [PDO::ATTR_EMULATE_PREPARES => true]);

    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

    $user1->deposit(100);
    $user2->deposit(100);

    Config::set('balance.waiting_time_in_sec', 10);

    Fork::new()->run(
        function () use ($user1) {
            $block = LockHelper::lockWallet($user1->id);
            $block->block(config('balance.waiting_time_in_sec'));
            $user1->deposit(100);
            $block->release();
        },
        function () use ($user2) {
            $block = LockHelper::lockWallet($user2->id);
            $block->block(config('balance.waiting_time_in_sec'));
            $user2->deposit(100);
            $block->release();
        }
    );

    expect($user1->balanceInt)
        ->toBe(100);
    //->and($user2->balanceInt)->toBe(100);

})->skip();

it('simulates a database error (There is no active transaction)', function () {
    $pdo = DB::connection()->getPdo();
    try {
        // Perform some database operation that will fail.
        // For example, try to insert a duplicate entry into a column with a unique index.
        DB::table('users')->insert([
            'name' => 'hello',
            'password' => '123456',
            'email' => 'duplicate@email.com',
        ]);
        DB::table('users')->insert([
            'name' => 'hello1',
            'password' => '123456',
            'email' => 'duplicate@email.com',
        ]);

        $pdo->commit();
    } catch (\Exception $e) {

        $pdo->rollBack();

        // Re-throw the exception to be caught by the test.
        throw $e;
    }

})->expectExceptionMessage('There is no active transaction');
