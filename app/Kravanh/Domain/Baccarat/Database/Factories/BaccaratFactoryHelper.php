<?php

namespace App\Kravanh\Domain\Baccarat\Database\Factories;

use App\Kravanh\Domain\Baccarat\Models\BaccaratGame;
use App\Kravanh\Domain\Baccarat\tests\BaccaratTestHelper;
use App\Kravanh\Domain\Game\Models\GameTable;
use App\Kravanh\Domain\User\Models\Member;
use App\Kravanh\Domain\User\Models\Trader;

final class BaccaratFactoryHelper
{
    public static function make(): BaccaratFactoryHelper
    {
        return (new BaccaratFactoryHelper());
    }

    public function gameTableId(): int
    {
        return $this->gameTable()->id;
    }

    public function baccaratGameId(): int
    {
        return $this->baccaratGame()->id;
    }

    public function traderId(): int
    {
        return $this->trader()->id;
    }

    public function trader()
    {
        return Trader::firstOr(fn() => Trader::factory()->baccaratTrader()->create());
    }

    public function gameTable(): GameTable
    {
        return GameTable::firstOr(fn() => GameTable::factory()->create());
    }

    public function baccaratGame(): BaccaratGame
    {
        return BaccaratGame::firstOr(fn() => BaccaratGame::factory()->create());
    }


    public function member(): Member
    {
        //seed user first
        $member = Member::whereName('member_1')->whereType('member')->firstOr(fn() => Member::factory()->create());
        $member->group_id = $this->gameTableId();
        $member->saveQuietly();

        try {
            BaccaratTestHelper::setUpConditionForMember($member);

        } catch (\Exception $e) {
        }

        return $member;
    }
}
