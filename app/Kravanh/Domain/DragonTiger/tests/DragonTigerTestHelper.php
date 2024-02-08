<?php

namespace App\Kravanh\Domain\DragonTiger\tests;

use App\Kravanh\Domain\DragonTiger\Dto\DragonTigerGameMemberBetData;
use App\Kravanh\Domain\DragonTiger\Models\DragonTigerGame;
use App\Kravanh\Domain\DragonTiger\Models\DragonTigerTicket;
use App\Kravanh\Domain\DragonTiger\Support\DragonTigerCard;
use App\Kravanh\Domain\Game\Actions\GameDragonTigerGetAction;
use App\Kravanh\Domain\Game\Actions\GameTableConditionUpdateOrCreateAction;
use App\Kravanh\Domain\Game\Dto\GameTableConditionData;
use App\Kravanh\Domain\Game\tests\GameTestHelper;
use App\Kravanh\Domain\User\Models\Member;
use App\Kravanh\Domain\User\Supports\Enums\UserType;

class DragonTigerTestHelper
{
    private function makeWinningTickets(): DragonTigerTicket
    {
        $winTicket1 = DragonTigerTicket::first();
        $winTicket1->bet_on = DragonTigerCard::Tiger;
        $winTicket1->bet_type = DragonTigerCard::Tiger;
        $winTicket1->saveQuietly();

        $winTicket2 = DragonTigerTicket::find($winTicket1->id + 1);
        $winTicket2->bet_on = DragonTigerCard::Tiger;
        $winTicket2->bet_type = DragonTigerCard::Red;
        $winTicket2->saveQuietly();

        $winTicket3 = DragonTigerTicket::find($winTicket2->id + 1);
        $winTicket3->bet_on = DragonTigerCard::Dragon;
        $winTicket3->bet_type = DragonTigerCard::Red;
        $winTicket3->saveQuietly();

        $winTicket4 = DragonTigerTicket::find($winTicket3->id + 1);
        $winTicket4->bet_on = DragonTigerCard::Tiger;
        $winTicket4->bet_type = DragonTigerCard::Big;
        $winTicket4->amount = 40_000;
        $winTicket4->payout = 40_000;
        $winTicket4->saveQuietly();

        $winTicket5 = DragonTigerTicket::find($winTicket4->id + 1);
        $winTicket5->bet_on = DragonTigerCard::Dragon;
        $winTicket5->bet_type = DragonTigerCard::Small;
        $winTicket5->user_id = $winTicket4->user_id;
        $winTicket5->amount = 40_000;
        $winTicket5->payout = 40_000;
        $winTicket5->saveQuietly();

        return $winTicket5;
    }

    private function makeLoseTickets(DragonTigerTicket $winTicket5): void
    {
        $loseTicket1 = DragonTigerTicket::find($winTicket5->id + 1);
        $loseTicket1->bet_on = DragonTigerCard::Dragon;
        $loseTicket1->bet_type = DragonTigerCard::Dragon;
        $loseTicket1->saveQuietly();

        $loseTicket2 = DragonTigerTicket::find($loseTicket1->id + 1);
        $loseTicket2->bet_on = DragonTigerCard::Tie;
        $loseTicket2->bet_type = DragonTigerCard::Tie;
        $loseTicket2->saveQuietly();

        $loseTicket3 = DragonTigerTicket::find($loseTicket2->id + 1);
        $loseTicket3->bet_on = DragonTigerCard::Tiger;
        $loseTicket3->bet_type = DragonTigerCard::Black;
        $loseTicket3->saveQuietly();

        $loseTicket4 = DragonTigerTicket::find($loseTicket3->id + 1);
        $loseTicket4->bet_on = DragonTigerCard::Dragon;
        $loseTicket4->bet_type = DragonTigerCard::Black;
        $loseTicket4->saveQuietly();

        $loseTicket5 = DragonTigerTicket::find($loseTicket4->id + 1);
        $loseTicket5->bet_on = DragonTigerCard::Dragon;
        $loseTicket5->bet_type = DragonTigerCard::Big;
        $loseTicket5->saveQuietly();

        $loseTicket6 = DragonTigerTicket::find($loseTicket5->id + 1);
        $loseTicket6->bet_on = DragonTigerCard::Tiger;
        $loseTicket6->bet_type = DragonTigerCard::Small;
        $loseTicket6->saveQuietly();

    }

    public static function createGameIncludeWinAndLoseTickets(): DragonTigerGame
    {
        $game = DragonTigerGame::factory([
            'dragon_result' => 4,
            'dragon_type' => 'heart',
            'dragon_color' => 'red',
            'dragon_range' => 'small',
            'tiger_result' => 11,
            'tiger_type' => 'heart',
            'tiger_color' => 'red',
            'tiger_range' => 'big',
            'winner' => 'tiger',
        ])
            ->has(DragonTigerTicket::factory()->count(11), 'tickets')
            ->create();

        $helper = new self();
        $helper->makeLoseTickets(winTicket5: $helper->makeWinningTickets());

        return $game;
    }

    public static function betTiger(int $amount)
    {
        return DragonTigerTestHelper::betData($amount, DragonTigerCard::Tiger, DragonTigerCard::Tiger);
    }

    public static function betDragonRed(int $amount)
    {
        return DragonTigerTestHelper::betData($amount, DragonTigerCard::Dragon, DragonTigerCard::Red);
    }

    public static function betData(
        int $amount,
        string $betOn,
        string $betType,
    ) {

        return DragonTigerGameMemberBetData::make(
            member: DragonTigerTestHelper::member(),
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
        $game = app(GameDragonTigerGetAction::class)();
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
