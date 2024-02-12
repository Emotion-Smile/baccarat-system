<?php

namespace App\Kravanh\Domain\Game\tests;

use App\Kravanh\Domain\Game\Dto\GameTableConditionData as Condition;
use App\Kravanh\Domain\Game\Models\Game;
use App\Kravanh\Domain\Game\Models\GameTable;
use App\Kravanh\Domain\Game\Supports\GameName;
use App\Kravanh\Domain\Game\Supports\GameType;

final class  GameTestHelper
{

    public static function createDragonTigerGame()
    {
        return Game::create([
            'name' => GameName::DragonTiger,
            'label' => 'Dragon & Tiger',
            'type' => GameType::Casino,
        ]);

    }

    public static function createGameTable(int $gameId)
    {
        return GameTable::create([
            'game_id' => $gameId,
            'label' => 'table1',
            'stream_url' => 'https://never-stream.live'
        ]);
    }

    public static function createDragonTigerGameWithDefaultTable()
    {
        return GameTestHelper::createGameTable(
            gameId: GameTestHelper::createDragonTigerGame()->id
        );
    }


    public static function fakeConditionArray(
        int   $originalUplineShare = 100,
        bool  $isAllowed = true,
        int   $share = 95,
        int   $uplineShare = 5,
        float $commission = 0.001,
        int   $matchLimit = 1000,
        int   $winLimitPerDay = 2000,
        int   $dragonTigerMinBetPerTicker = 1,
        int   $dragonTigerMaxBetPerTicker = 500,
        int   $redBlackMinBetPerTicker = 1,
        int   $redBlackMaxBetPerTicker = 400,
        int   $playerBankerMinBetPerTicker = 1,
        int   $playerBankerMaxBetPerTicker = 500,
        int   $playerPairBankerPairMinBetPerTicker = 1,
        int   $playerPairBankerPairMaxBetPerTicker = 400,
        int   $bigSmallMinBetPerTicker = 1,
        int   $bigSmallMaxBetPerTicker = 400,
    ): array
    {
        return [
            Condition::ORIGINAL_UPLINE_SHARE => $originalUplineShare,
            Condition::IS_ALLOWED => $isAllowed,
            Condition::SHARE => $share,
            Condition::UP_LINE_SHARE => $uplineShare,
            Condition::COMMISSION => $commission,
            Condition::MATCH_LIMIT => $matchLimit,
            Condition::WIN_LIMIT_PER_DAY => $winLimitPerDay,
            Condition::DRAGON_TIGER_MIN_BET_PER_TICKET => $dragonTigerMinBetPerTicker,
            Condition::DRAGON_TIGER_MAX_BET_PER_TICKET => $dragonTigerMaxBetPerTicker,
//            Condition::TIE_MIN_BET_PER_TICKET => 2,
//            Condition::TIE_MAX_BET_PER_TICKET => 200,
            Condition::RED_BLACK_MIN_BET_PER_TICKET => $redBlackMinBetPerTicker,
            Condition::RED_BLACK_MAX_BET_PER_TICKET => $redBlackMaxBetPerTicker,
            Condition::PLAYER_BANKER_MIN_BET_PER_TICKET => $playerBankerMinBetPerTicker,
            Condition::PLAYER_BANKER_MAX_BET_PER_TICKET => $playerBankerMaxBetPerTicker,
            Condition::PLAYER_PAIR_BANKER_PAIR_MIN_BET_PER_TICKET => $playerPairBankerPairMinBetPerTicker,
            Condition::PLAYER_PAIR_BANKER_PAIR_MAX_BET_PER_TICKET => $playerPairBankerPairMaxBetPerTicker,
            Condition::BIG_SMALL_MIN_BET_PER_TICKET => $bigSmallMinBetPerTicker,
            Condition::BIG_SMALL_MAX_BET_PER_TICKET => $bigSmallMaxBetPerTicker,
        ];

    }

    public static function fakeConditionData(
        int    $gameId = 1,
        int    $gameTableId = 2,
        int    $userId = 3,
        string $userType = 'super_senior',
        bool   $isAllowed = true,
        array  $shareComAndCondition = null,
    ): Condition
    {
        $fakeShareCom = GameTestHelper::fakeConditionArray();
        $shareCommissionAndCondition = $shareComAndCondition ?? Condition::build($fakeShareCom);

        return Condition::from(
            gameId: $gameId,
            gameTableId: $gameTableId,
            userId: $userId,
            userType: $userType,
            isAllowed: $isAllowed,
            shareAndCommission: $shareCommissionAndCondition[Condition::SHARE_AND_COMMISSION],
            betCondition: $shareCommissionAndCondition[Condition::BET_CONDITION]
        );

    }

}
