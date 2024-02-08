<?php

use App\Kravanh\Domain\DragonTiger\Actions\DragonTigerGameCreateTicketAction;
use App\Kravanh\Domain\DragonTiger\Actions\DragonTigerGameMemberBettingWithdrawBalanceAction;
use App\Kravanh\Domain\DragonTiger\Dto\DragonTigerGameCreateTicketData;
use App\Kravanh\Domain\DragonTiger\Dto\DragonTigerGameMemberBetData;
use App\Kravanh\Domain\DragonTiger\Dto\TransactionTicketBettingMetaData;
use App\Kravanh\Domain\DragonTiger\Models\DragonTigerGame;
use App\Kravanh\Domain\DragonTiger\Models\DragonTigerTicket;
use App\Kravanh\Domain\DragonTiger\Support\DragonTigerCard;
use App\Kravanh\Domain\DragonTiger\tests\DragonTigerTestHelper;
use App\Kravanh\Domain\Game\Database\Seeders\GameSeeder;
use App\Kravanh\Domain\Game\Supports\GameName;
use App\Kravanh\Support\Enums\Currency;
use Bavix\Wallet\Models\Transaction;
use function Pest\Laravel\seed;

test('it will rollback ticket if cannot withdraw balance from member', function () {

    setupUser(Currency::USD);
    seed(GameSeeder::class);
    $game = DragonTigerGame::factory()->liveGame()->create();

    $member = DragonTigerTestHelper::member(groupId: $game->game_table_id);
    $member->forceWithdraw(9_900_000);
    $member->refresh();
    //10, 10, 20, 10, 50
    DragonTigerTestHelper::setUpConditionForMember($member);

    $data = DragonTigerGameCreateTicketData::make(
        DragonTigerGameMemberBetData::make(
            member: $member,
            amount: 100,
            betOn: DragonTigerCard::Tiger,
            betType: DragonTigerCard::Tiger,
            ip: '127.0.0.1'
        )
    );

    $ticket = DragonTigerGameCreateTicketAction::from($data);
    expect(DragonTigerTicket::count())->toBe(1);

    try {
        DragonTigerGameMemberBettingWithdrawBalanceAction::from($member, $ticket, $game);
    } catch (Exception $exception) {
        expect(DragonTigerTicket::count())->toBe(0)
            ->and($exception->getMessage())->toBe('Insufficient funds');
    }
});

test('it can with member balance correctly', function () {

    setupUser(Currency::USD);
    seed(GameSeeder::class);
    $game = DragonTigerGame::factory()->liveGame()->create();

    $member = DragonTigerTestHelper::member(groupId: $game->game_table_id);

    //10, 10, 20, 10, 50
    DragonTigerTestHelper::setUpConditionForMember($member);

    $data = DragonTigerGameCreateTicketData::make(
        DragonTigerGameMemberBetData::make(
            member: $member,
            amount: 100,
            betOn: DragonTigerCard::Tiger,
            betType: DragonTigerCard::Tiger,
            ip: '127.0.0.1'
        )
    );

    $ticket = DragonTigerGameCreateTicketAction::from($data);
    $beforeBalance = $member->balanceInt;

    $balance = DragonTigerGameMemberBettingWithdrawBalanceAction::from($member, $ticket, $game);

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
