<?php

use App\Kravanh\Domain\BetCondition\Actions\BetConditionCreateAction;
use App\Kravanh\Domain\Environment\Models\Group;
use App\Kravanh\Domain\Match\Models\BetRecord;
use App\Kravanh\Domain\Match\Models\Matches;
use App\Kravanh\Domain\Match\Supports\Enums\BetOn;
use App\Kravanh\Domain\Match\Supports\Enums\MatchResult;
use App\Kravanh\Domain\User\Models\Member;
use App\Kravanh\Domain\User\Supports\Enums\Status;
use App\Kravanh\Support\Enums\Currency;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use function Pest\Laravel\postJson;
use function Pest\Laravel\seed;

beforeEach(function () {
    seed(['DatabaseSeeder']);
    setupUser(Currency::USD);
    Matches::factory()->createQuietly();
});

function betVerifyValidation($message = '', $amount = 0, $on = BetOn::MERON, $messageType = 'error'): void
{
    $assert = [
        'type' => $messageType,
        'message' => __($message)
    ];

    if (empty($message)) {
        unset($assert['message']);
    }

    postJson(route('member.match.betting'), [
        'betAmount' => $amount,
        'betOn' => $on
    ])
        ->assertOk()
        ->assertJson($assert);
}


test('it can bet if member allow to bet on disable', function () {
    $match = Matches::first();

    Cache::put("match:$match->id:disable:1", true, now()->addMinutes(10));
    nova_set_setting_value('disable_bet_threshold_amount', 20000);

    $member = loginAsMember();
    $member->can_bet_when_disable = true;
    $member->saveQuietly();


    betVerifyValidation(
        message: 'betting.succeed',
        amount: 10,
        messageType: 'success'
    );

});


test('it can bet if agent allow to bet on disable', function () {
    $match = Matches::first();

    Cache::put("match:$match->id:disable:1", true, now()->addMinutes(10));
    nova_set_setting_value('disable_bet_threshold_amount', 20000);

    $member = loginAsMember();

    $agent = User::find($member->agent);
    $agent->can_bet_when_disable = true;
    $agent->save();
    $agent->refresh()->setCanBetWhenDisable();

    betVerifyValidation(
        message: 'betting.succeed',
        amount: 10,
        messageType: 'success'
    );

});

test('it can bet if mero or wala disabled and amount less then threshold', function () {
    $match = Matches::first();

    Cache::put("match:$match->id:disable:1", true, now()->addMinutes(10));
    Cache::put("match:$match->id:disable:2", true, now()->addMinutes(10));

    nova_set_setting_value('disable_bet_threshold_amount', 20000);

    loginAsMember();

    betVerifyValidation(
        message: 'betting.succeed',
        amount: 3,
        messageType: 'success'
    );

    betVerifyValidation(
        message: 'betting.succeed',
        amount: 3,
        on: BetOn::WALA,
        messageType: 'success'
    );
});

test('it cannot bet if mero or wala disabled and amount over threshold', function () {
    $match = Matches::first();

    Cache::put("match:$match->id:disable:1", true, now()->addMinutes(10));
    Cache::put("match:$match->id:disable:2", true, now()->addMinutes(10));

    nova_set_setting_value('disable_bet_threshold_amount', 20000);

    loginAsMember();

    betVerifyValidation(
        message: 'betting.match_betting_failed',
        amount: 5
    );

    betVerifyValidation(
        message: 'betting.match_betting_failed',
        amount: 5, on: BetOn::WALA
    );
});

test('it cannot bet if transaction bet is locked', function () {
    nova_set_setting_value('disable_member_bet', 1);

    loginAsMember();

    betVerifyValidation(
        message: 'transaction.block_transaction'
    );
});

test('it cannot bet if account is locked', function () {

    $member = Member::whereName('member_1')->first();
    $member->status = Status::LOCK;
    $member->saveQuietly();

    loginAsMember();

    betVerifyValidation(
        message: 'betting.account_not_allow'
    );

});

test('it cannot bet if match not live', function () {

    $match = Matches::first();
    $match->match_end_at = now();
    $match->saveQuietly();

    loginAsMember();

    betVerifyValidation(message: 'betting.no_match_found');
});

test('it cannot bet if match not yet open bet', function () {

    $match = Matches::first();
    $match->bet_started_at = null;
    $match->saveQuietly();

    loginAsMember();

    betVerifyValidation(message: 'betting.match_betting_not_yet_open');

});

test('it cannot bet if match already closed bet', function () {

    $match = Matches::first();
    $match->bet_stopped_at = now();
    $match->saveQuietly();

    loginAsMember();

    betVerifyValidation(message: 'betting.match_betting_closed');
});

test('it cannot bet if bet amount lower than minimum allowed per ticket', function () {

    $member = loginAsMember();

    betVerifyValidation(
        message: 'betting.bet_amount_lower_than_minimum_price_per_ticket_allowed',
        amount: 0.5
    );

//  set minimum bet on group greater than member min bet
//  so threshold min bet should the value from the member
    $group = Group::first();
    $group->meta = ['USD_min_bet' => 2];
    $group->saveQuietly();

    betVerifyValidation(
        message: 'betting.succeed',
        amount: 1,
        messageType: 'success'
    );

    // set member min bet greater than group
    // so threshold min bet should the value from the group
    $member->condition = ['minimum_bet_per_ticket' => 4];
    $member->saveQuietly();

    betVerifyValidation(
        message: 'betting.bet_amount_lower_than_minimum_price_per_ticket_allowed',
        amount: 1
    );

})->skip(message: 'minMaxBetValidator disabled');

test('it cannot bet if bet amount greater than maximum allowed per ticket', function () {

    $member = loginAsMember();

    betVerifyValidation(
        message: 'betting.bet_amount_higher_than_maximum_price_per_ticket_allowed',
        amount: 1000
    );

    //set max bet on group less than max bet member
    //so threshold max bet should from the value of group
    $group = Group::first();
    $group->meta = ['USD_max_bet' => 500];
    $group->saveQuietly();

    betVerifyValidation(
        message: 'betting.bet_amount_higher_than_maximum_price_per_ticket_allowed',
        amount: 501
    );

    //set max bet on member less than max bet group
    //so threshold max bet should from the value of member
    $member->condition = ['maximum_bet_per_ticket' => 300];
    $member->saveQuietly();

    betVerifyValidation(
        message: 'betting.bet_amount_higher_than_maximum_price_per_ticket_allowed',
        amount: 301
    );

})->skip(message: 'minMaxBetValidator disabled');


test('it cannot bet if bet amount lower than minimum allowed per ticket v1', function () {

    $member = loginAsMember();

    betVerifyValidation(
        message: 'betting.bet_amount_lower_than_minimum_price_per_ticket_allowed',
        amount: 0.5
    );

    app(BetConditionCreateAction::class)(
        groupId: $member->group_id,
        userId: $member->id,
        minBetPerTicket: 4,
        maxBetPerTicket: 700,
        matchLimit: 1000,
        winLimitPerDay: 2000
    );

    betVerifyValidation(
        message: 'betting.bet_amount_lower_than_minimum_price_per_ticket_allowed',
        amount: 3
    );
});

test('it cannot bet if bet amount greater than maximum allowed per ticket v1', function () {

    $member = loginAsMember();

    betVerifyValidation(
        message: 'betting.bet_amount_higher_than_maximum_price_per_ticket_allowed',
        amount: 1000
    );

    app(BetConditionCreateAction::class)(
        groupId: $member->group_id,
        userId: $member->id,
        minBetPerTicket: 4,
        maxBetPerTicket: 300,
        matchLimit: 1000,
        winLimitPerDay: 2000
    );

    betVerifyValidation(
        message: 'betting.bet_amount_higher_than_maximum_price_per_ticket_allowed',
        amount: 301
    );
});


test('it cannot bet if balance lower than bet amount', function () {

    Member::whereName('member_1')->first()->withdraw(10_000_000);

    loginAsMember();

    betVerifyValidation(
        message: 'betting.not_enough_credit',
        amount: 100
    );

});


test('it cannot bet if is over win limit per day', function () {

    $match = Matches::first();
    $match->result = BetOn::MERON;
    $match->saveQuietly();

    $member = loginAsMember();

    BetRecord::factory([
        'match_id' => $match->id,
        'user_id' => $member->id,
        'bet_on' => BetOn::MERON,
        'amount' => $member->toKHR(600) //USD
    ])
        ->count(2)
        ->create();

    betVerifyValidation(
        message: 'betting.over_win_limit_per_day',
        amount: 100
    );

    app(BetConditionCreateAction::class)(
        groupId: $member->group_id,
        userId: $member->id,
        minBetPerTicket: 4,
        maxBetPerTicket: 300,
        matchLimit: 1000,
        winLimitPerDay: 0 // no-restrict
    );


    betVerifyValidation(
        message: 'betting.succeed',
        amount: 100,
        messageType: 'success'
    );

});

test('it cannot bet if balance is blocked', function () {

    $member = Member::whereName('member_1')
        ->first();
    $member->blockBalance();

    loginAsMember();

    betVerifyValidation(
        message: 'betting.balance_blocked',
        amount: 100
    );
});

test('it cannot bet if total bet amount is over match limit', function () {

    loginAsMember();

    betVerifyValidation(amount: 500, messageType: 'success');

    betVerifyValidation(
    //message: 'betting.over_match_limit',
        message: 'betting.bet_amount_higher_than_maximum_price_per_ticket_allowed',
        amount: 250,
        on: BetOn::WALA
    );

    Matches::first()->endMatch(MatchResult::WALA);

    //set group match limit less than member
    //so threshold match limit should from the value of group
    $match = Matches::factory()->createQuietly();
    $group = $match->group;
    $group->meta = ['USD_match_limit' => 200];
    $group->save();

    betVerifyValidation(
        message: 'betting.bet_amount_higher_than_maximum_price_per_ticket_allowed',
        amount: 500
    );
    $match->endMatch(MatchResult::WALA);

    //set member match limit less than group
    //so threshold match limit should from the value of member
    Matches::factory()->createQuietly();
    $group->refresh()->meta = ['USD_match_limit' => 1000];
    $group->save();


    betVerifyValidation(
        message: 'betting.bet_amount_higher_than_maximum_price_per_ticket_allowed',
        amount: 751,
    );

    betVerifyValidation(
        message: 'betting.succeed',
        amount: 740,
        messageType: "success"
    );
})->skip('disabled isOverMatchLimitAmount');


test('it cannot bet if total bet amount is over match limit v1', function () {

    $member = loginAsMember();

    betVerifyValidation(amount: 500, messageType: 'success');

    betVerifyValidation(
        message: 'betting.bet_amount_higher_than_maximum_price_per_ticket_allowed',
        amount: 251,
        on: BetOn::WALA
    );

    betVerifyValidation(
        message: 'betting.bet_amount_higher_than_maximum_price_per_ticket_allowed',
        amount: 500
    );

    app(BetConditionCreateAction::class)(
        groupId: $member->group_id,
        userId: $member->id,
        minBetPerTicket: 4,
        maxBetPerTicket: 300,
        matchLimit: 1000, // update from 750 to 1000
        winLimitPerDay: 2000
    );

    betVerifyValidation(
        message: 'betting.succeed',
        amount: 200,
        messageType: 'success'
    );

});
