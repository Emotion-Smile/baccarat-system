<?php

use App\Kravanh\Domain\Baccarat\Models\BaccaratGame;
use App\Kravanh\Domain\Baccarat\Models\BaccaratTicket;
use App\Kravanh\Domain\Baccarat\tests\BaccaratTestHelper;
use App\Kravanh\Domain\Game\Database\Seeders\GameSeeder;
use App\Kravanh\Domain\User\Models\Member;
use App\Kravanh\Support\Enums\Currency;
use Bavix\Wallet\Models\Transaction;
use Illuminate\Support\Facades\Cache;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\postJson;
use function Pest\Laravel\seed;

test('validates required fields for Dragon Tiger betting', function () {
    actingAs(Member::factory()->create())
        ->postJson(route('dragon-tiger.betting'))
        ->assertJsonValidationErrors(['amount', 'betOn', 'betType']);
});

test('Ensure message type failed is return if validation failed', function () {
    seed(GameSeeder::class);
    $game = BaccaratGame::factory()->liveGame()->create();
    $member = Member::factory(['group_id' => $game->game_table_id])->create();
    Cache::put('lang:'.$member->id, 'en');

    actingAs($member)
        ->postJson(route('dragon-tiger.betting'), [
            'amount' => 100,
            'betOn' => 'tiger',
            'betType' => 'tiger',
        ])->assertJson([
            'type' => 'failed',
            'message' => 'Oop, something went wrong your account not allow',
        ]);
});

test('Ensure message success is return if betting succeed', function () {

    setupUser(Currency::USD);
    seed(GameSeeder::class);

    $game = BaccaratGame::factory()->liveGame()->create();
    $member = BaccaratTestHelper::member(groupId: $game->game_table_id);

    BaccaratTestHelper::setUpConditionForMember($member);

    actingAs($member);

    $response = postJson(route('dragon-tiger.betting'), [
        'amount' => 100,
        'betOn' => 'tiger',
        'betType' => 'tiger',
    ]);

    expect($response->json(['type']))->toBe('ok')
        ->and(
            \Illuminate\Support\Str::replace('&nbsp;', ' ', htmlentities($response->json(['message'])))
        )->toContain('$ 2,400.00')
        ->and(BaccaratTicket::count())->toBe(1)
        ->and(Transaction::where('payable_id', $member->id)->where('type', 'withdraw')->count())->toBe(1);
});
