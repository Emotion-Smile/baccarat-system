<?php

namespace App\Kravanh\Domain\Baccarat\tests;

use App\Kravanh\Domain\Baccarat\Models\BaccaratTicket;
use App\Kravanh\Domain\Baccarat\Dto\BaccaratGameMemberBetData;
use App\Kravanh\Domain\Baccarat\Models\BaccaratGame;
use App\Kravanh\Domain\Baccarat\Support\BaccaratCard;
use App\Kravanh\Domain\Game\Actions\GameBaccaratGetAction;
use App\Kravanh\Domain\Game\Actions\GameTableConditionUpdateOrCreateAction;
use App\Kravanh\Domain\Game\Dto\GameTableConditionData;
use App\Kravanh\Domain\Game\tests\GameTestHelper;
use App\Kravanh\Domain\User\Models\Member;
use App\Kravanh\Domain\User\Supports\Enums\UserType;

class BaccaratTestHelper
{
    private function makeWinningTickets(): BaccaratTicket
    {
        $winTicket1 = BaccaratTicket::first();
        $winTicket1->bet_on = BaccaratCard::Banker;
//        $winTicket1->bet_type = BaccaratCard::Banker;
        $winTicket1->saveQuietly();

        $winTicket2 = BaccaratTicket::find($winTicket1->id + 1);
        $winTicket2->bet_on = BaccaratCard::Banker;
//        $winTicket2->bet_type = BaccaratCard::Big;
        $winTicket2->saveQuietly();

        $winTicket3 = BaccaratTicket::find($winTicket2->id + 1);
        $winTicket3->bet_on = BaccaratCard::Player;
//        $winTicket3->bet_type = BaccaratCard::Big;
        $winTicket3->saveQuietly();

        $winTicket4 = BaccaratTicket::find($winTicket3->id + 1);
        $winTicket4->bet_on = BaccaratCard::Banker;
//        $winTicket4->bet_type = BaccaratCard::Big;
        $winTicket4->amount = 40_000;
        $winTicket4->payout = 40_000;
        $winTicket4->saveQuietly();

        $winTicket5 = BaccaratTicket::find($winTicket4->id + 1);
        $winTicket5->bet_on = BaccaratCard::Player;
//        $winTicket5->bet_type = BaccaratCard::Small;
        $winTicket5->user_id = $winTicket4->user_id;
        $winTicket5->amount = 40_000;
        $winTicket5->payout = 40_000;
        $winTicket5->saveQuietly();

        return $winTicket5;
    }

    private function makeLoseTickets(BaccaratTicket $winTicket5): void
    {
        $loseTicket1 = BaccaratTicket::find($winTicket5->id + 1);
        $loseTicket1->bet_on = BaccaratCard::Player;
//        $loseTicket1->bet_type = BaccaratCard::Player;
        $loseTicket1->saveQuietly();

        $loseTicket2 = BaccaratTicket::find($loseTicket1->id + 1);
        $loseTicket2->bet_on = BaccaratCard::Tie;
//        $loseTicket2->bet_type = BaccaratCard::Tie;
        $loseTicket2->saveQuietly();

        $loseTicket3 = BaccaratTicket::find($loseTicket2->id + 1);
        $loseTicket3->bet_on = BaccaratCard::Banker;
//        $loseTicket3->bet_type = BaccaratCard::Big;
        $loseTicket3->saveQuietly();

        $loseTicket4 = BaccaratTicket::find($loseTicket3->id + 1);
        $loseTicket4->bet_on = BaccaratCard::Player;
//        $loseTicket4->bet_type = BaccaratCard::Big;
        $loseTicket4->saveQuietly();

        $loseTicket5 = BaccaratTicket::find($loseTicket4->id + 1);
        $loseTicket5->bet_on = BaccaratCard::Player;
//        $loseTicket5->bet_type = BaccaratCard::Small;
        $loseTicket5->saveQuietly();

        $loseTicket6 = BaccaratTicket::find($loseTicket5->id + 1);
        $loseTicket6->bet_on = BaccaratCard::Banker;
//        $loseTicket6->bet_type = BaccaratCard::Small;
        $loseTicket6->saveQuietly();

    }

    public static function createGameIncludeWinAndLoseTickets(): BaccaratGame
    {
        $game = BaccaratGame::factory([
//            'dragon_result' => 4,
//            'dragon_type' => 'heart',
//            'dragon_color' => 'red',
//            'dragon_range' => 'small',
//            'tiger_result' => 11,
//            'tiger_type' => 'heart',
//            'tiger_color' => 'red',
//            'tiger_range' => 'big',
//            'winner' => 'tiger',
            'player_first_card_value' => 1,
            'player_first_card_type' => 'heart',
            'player_first_card_color' => 'red',
            'player_first_card_points' => 1,
            'player_second_card_value' => 5,
            'player_second_card_type' => 'diamond',
            'player_second_card_color' => 'red',
            'player_second_card_points' => 5,
            'player_third_card_value' => 3,
            'player_third_card_type' => 'diamond',
            'player_third_card_color' => 'red',
            'player_third_card_points' => 3,
            'player_total_points' => 9,
            'player_points' => 9,
            'banker_first_card_value' => 5,
            'banker_first_card_type' => 'spade',
            'banker_first_card_color' => 'black',
            'banker_first_card_points' => 5,
            'banker_second_card_value' => 5,
            'banker_second_card_type' => 'club',
            'banker_second_card_color' => 'black',
            'banker_second_card_points' => 5,
            'banker_third_card_value' => 7,
            'banker_third_card_type' => 'heart',
            'banker_third_card_color' => 'red',
            'banker_third_card_points' => 7,
            'banker_total_points' => 7,
            'banker_points' => 7,
            'winner' => ['player', 'big', 'banker_pair'],
        ])
            ->has(BaccaratTicket::factory()->count(11), 'tickets')
            ->create();

        $helper = new self();
        $helper->makeLoseTickets(winTicket5: $helper->makeWinningTickets());

        return $game;
    }

    public static function betBanker(int $amount)
    {
        return BaccaratTestHelper::betData($amount, BaccaratCard::Banker, BaccaratCard::Banker);
    }

    public static function betPlayerPair(int $amount)
    {
        return BaccaratTestHelper::betData($amount, BaccaratCard::Player, BaccaratCard::PlayerPair);
    }

    public static function betData(
        int $amount,
        string $betOn,
        string $betType,
    ) {

        return BaccaratGameMemberBetData::make(
            member: BaccaratTestHelper::member(),
            amount: $amount,
            betOn: $betOn,
            betType: $betType,
            ip: '127.0.0.1');

    }

    public static function member($name = 'member_1', int $groupId = 0): Member
    {
        $member = Member::whereName($name)->whereType('member')->first();

        if ($groupId > 0) {
            $member->group_id = $groupId;
        }
        $member->saveQuietly();

        return $member;
    }

    public static function setUpConditionForMember(
        Member $member,
    ): void {
        $game = app(GameBaccaratGetAction::class)();
        $shares = [
            $member->super_senior => [
                'upline_share' => 10,
                'commission' => 0.001,
                'share' => 90,
                'user_type' => UserType::SUPER_SENIOR,
            ],
            $member->senior => [
                'upline_share' => 10,
                'commission' => 0.001,
                'share' => 80,
                'user_type' => UserType::SENIOR,
            ],
            $member->master_agent => [
                'upline_share' => 20,
                'commission' => 0.001,
                'share' => 60,
                'user_type' => UserType::MASTER_AGENT,
            ],
            $member->agent => [
                'upline_share' => 10,
                'commission' => 0.001,
                'share' => 50,
                'user_type' => UserType::AGENT,
            ],
            $member->id => [
                'upline_share' => 50,
                'commission' => 0.001,
                'share' => 0,
                'user_type' => UserType::MEMBER,
            ],
        ];

        foreach ($shares as $id => $share) {

            (new GameTableConditionUpdateOrCreateAction())(
                GameTestHelper::fakeConditionData(
                    gameId: $game->id,
                    gameTableId: $member->group_id,
                    userId: $id,
                    userType: $share['user_type'],
                    shareComAndCondition: GameTableConditionData::build(
                        GameTestHelper::fakeConditionArray(
                            originalUplineShare: $share['share'] + $share['upline_share'],
                            share: $share['share'],
                            uplineShare: $share['upline_share'],
                            commission: $share['commission']
                        )
                    )
                )
            );
        }
    }
}
