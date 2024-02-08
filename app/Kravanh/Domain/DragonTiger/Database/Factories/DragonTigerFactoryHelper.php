<?php

namespace App\Kravanh\Domain\DragonTiger\Database\Factories;

use App\Kravanh\Domain\DragonTiger\Models\DragonTigerGame;
use App\Kravanh\Domain\DragonTiger\tests\DragonTigerTestHelper;
use App\Kravanh\Domain\Game\Models\GameTable;
use App\Kravanh\Domain\User\Models\Member;
use App\Kravanh\Domain\User\Models\Trader;

final class DragonTigerFactoryHelper
{
    public static function make(): DragonTigerFactoryHelper
    {
        return (new DragonTigerFactoryHelper());
    }

    public function gameTableId(): int
    {
        return $this->gameTable()->id;
    }

    public function dragonTigerGameId(): int
    {
        return $this->dragonTigerGame()->id;
    }

    public function traderId(): int
    {
        return $this->trader()->id;
    }

    public function trader()
    {
        return Trader::firstOr(fn() => Trader::factory()->dragonTigerTrader()->create());
    }

    public function gameTable(): GameTable
    {
        return GameTable::firstOr(fn() => GameTable::factory()->create());
    }

    public function dragonTigerGame(): DragonTigerGame
    {
        return DragonTigerGame::firstOr(fn() => DragonTigerGame::factory()->create());
    }


    public function member(): Member
    {
        //seed user first
        $member = Member::whereName('member_1')->whereType('member')->firstOr(fn() => Member::factory()->create());
        $member->group_id = $this->gameTableId();
        $member->saveQuietly();

        try {
            DragonTigerTestHelper::setUpConditionForMember($member);

        } catch (\Exception $e) {
        }

        return $member;
    }
}
