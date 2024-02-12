<?php

use App\Kravanh\Domain\Baccarat\Actions\BaccaratGameCreateTicketAction;
use App\Kravanh\Domain\Baccarat\Actions\BaccaratGameMemberBettingWithdrawBalanceAction;
use App\Kravanh\Domain\Baccarat\Dto\BaccaratGameCreateTicketData;
use App\Kravanh\Domain\Baccarat\Dto\BaccaratGameMemberBetData;
use App\Kravanh\Domain\Baccarat\Dto\TransactionTicketBettingMetaData;
use App\Kravanh\Domain\Baccarat\Models\BaccaratGame;
use App\Kravanh\Domain\Baccarat\Models\BaccaratTicket;
use App\Kravanh\Domain\Baccarat\Support\BaccaratCard;
use App\Kravanh\Domain\Baccarat\tests\BaccaratTestHelper;
use App\Kravanh\Domain\Game\Database\Seeders\GameSeeder;
use App\Kravanh\Domain\Game\Supports\GameName;
use App\Kravanh\Support\Enums\Currency;
use Bavix\Wallet\Models\Transaction;
use function Pest\Laravel\seed;

test('it will rollback ticket if cannot withdraw balance from member', function () {

    setupUser(Currency::USD);
    seed(GameSeeder::class);
    $game = BaccaratGame::factory()->liveGame()->create();

    $member = BaccaratTestHelper::member(groupId: $game->game_table_id);
    $member->forceWithdraw(9_900_000);
    $member->refresh();
    //10, 10, 20, 10, 50
    BaccaratTestHelper::setUpConditionForMember($member);

    $data = BaccaratGameCreateTicketData::make(
        BaccaratGameMemberBetData::make(
            member: $member,
            amount: 100,
            betOn: BaccaratCard::Tiger,
            betType: BaccaratCard::Tiger,
            ip: '127.0.0.1'
        )
    );

    $ticket = BaccaratGameCreateTicketAction::from($data);
    expect(BaccaratTicket::count())->toBe(1);

    try {
        BaccaratGameMemberBettingWithdrawBalanceAction::from($member, $ticket, $game);
    } catch (Exception $exception) {
        expect(BaccaratTicket::count())->toBe(0)
            ->and($exception->getMessage())->toBe('Insufficient funds');
    }
});

test('it can with member balance correctly', function () {

    setupUser(Currency::USD);
    seed(GameSeeder::class);
    $game = BaccaratGame::factory()->liveGame()->create();

    $member = BaccaratTestHelper::member(groupId: $game->game_table_id);

    //10, 10, 20, 10, 50
    BaccaratTestHelper::setUpConditionForMember($member);

    $data = BaccaratGameCreateTicketData::make(
        BaccaratGameMemberBetData::make(
            member: $member,
            amount: 100,
            betOn: BaccaratCard::Tiger,
            betType: BaccaratCard::Tiger,
            ip: '127.0.0.1'
        )
    );

    $ticket = BaccaratGameCreateTicketAction::from($data);
    $beforeBalance = $member->balanceInt;

    $balance = BaccaratGameMemberBettingWithdrawBalanceAction::from($member, $ticket, $game);

    $betMeta = TransactionTicketBettingMetaData::fromMeta(
        meta: Transaction::where('payable_id', $member->id)->where('type', 'withdraw')->first()->meta
    );

    expect($beforeBalance)->toBe($balance + 400_000)
        ->and($betMeta->game)->toBe(GameName::DragonTiger->value)
        ->and($betMeta->type)->toBe('bet')
        ->and($betMeta->amount)->toBe(400_000)
        ->and($betMeta->betOn)->toBe($ticket->betOn())
        ->and($betMeta->payout)->toBe((int)$ticket->payout)
        ->and($betMeta->payoutRate)->toBe((int)$ticket->payout_rate)
        ->and($betMeta->ticketId)->toBe($ticket->id)
        ->and($betMeta->gameId)->toBe($ticket->dragon_tiger_game_id)
        ->and($betMeta->gameNumber)->toBe($game->gameNumber())
        ->and($beforeBalance)->toBe($beforeBalance)
        ->and($betMeta->currentBalance)->toBe($member->balanceInt)
        ->and($betMeta->currency)->toBe($member->currency->value);

});
