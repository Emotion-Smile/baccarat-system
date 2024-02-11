<?php

namespace App\Kravanh\Domain\Game\Dto;

use App\Kravanh\Domain\Game\Models\GameTableCondition;

final class GameTableConditionData
{
    const ORIGINAL_UPLINE_SHARE = 'original_upline_share';

    const SHARE_AND_COMMISSION = 'share_and_commission';

    const SHARE = 'share';

    const UP_LINE_SHARE = 'upline_share';

    const COMMISSION = 'commission';

    const BET_CONDITION = 'bet_condition';

    const MATCH_LIMIT = 'match_limit';

    const WIN_LIMIT_PER_DAY = 'win_limit_per_day';

    const DRAGON_TIGER_MIN_BET_PER_TICKET = 'dragon_tiger_min_bet_per_ticket';

    const DRAGON_TIGER_MAX_BET_PER_TICKET = 'dragon_tiger_max_bet_per_ticket';

    const TIE_MIN_BET_PER_TICKET = 'tie_min_bet_per_ticket';

    const TIE_MAX_BET_PER_TICKET = 'tie_max_bet_per_ticket';

    const RED_BLACK_MIN_BET_PER_TICKET = 'red_black_min_bet_per_ticket';

    const RED_BLACK_MAX_BET_PER_TICKET = 'red_black_max_bet_per_ticket';

    const PLAYER_BANKER_MIN_BET_PER_TICKET = 'player_banker_min_bet_per_ticket';

    const PLAYER_BANKER_MAX_BET_PER_TICKET = 'player_banker_max_bet_per_ticket';

    const ANY_TIE_MIN_BET_PER_TICKET = 'any_tie_min_bet_per_ticket';

    const ANY_TIE_MAX_BET_PER_TICKET = 'any_tie_max_bet_per_ticket';

    const BIG_SMALL_MIN_BET_PER_TICKET = 'big_small_min_bet_per_ticket';

    const BIG_SMALL_MAX_BET_PER_TICKET = 'big_small_max_bet_per_ticket';

    const PLAYER_PAIR_BANKER_PAIR_MIN_BET_PER_TICKET = 'player_pair_banker_pair_min_bet_per_ticket';

    const PLAYER_PAIR_BANKER_PAIR_MAX_BET_PER_TICKET = 'player_pair_banker_pair_max_bet_per_ticket';

    const IS_ALLOWED = 'is_allowed';

    const TIE_PAYOUT_RATE = 8;

    const UNKNOWN = 'unknown';

    const USER_ID = 'user_id';

    private int $share = 0;

    public function __construct(
        public int $gameId,
        public int $gameTableId,
        public int $userId,
        public string $userType,
        public bool $isAllowed,
        public array $shareAndCommission,
        public array $betCondition
    ) {
    }

    public static function fromBuild(
        int $gameId,
        int $gameTableId,
        int $userId,
        string $userType,
        array $build
    ): GameTableConditionData {
        return GameTableConditionData::from(
            gameId: $gameId,
            gameTableId: $gameTableId,
            userId: $userId,
            userType: $userType,
            isAllowed: $build[self::IS_ALLOWED],
            shareAndCommission: $build[self::SHARE_AND_COMMISSION],
            betCondition: $build[self::BET_CONDITION],
        );
    }

    public static function from(
        int $gameId,
        int $gameTableId,
        int $userId,
        string $userType,
        bool $isAllowed,
        array $shareAndCommission,
        array $betCondition
    ): GameTableConditionData {
        return new GameTableConditionData(
            gameId: $gameId,
            gameTableId: $gameTableId,
            userId: $userId,
            userType: $userType,
            isAllowed: $isAllowed,
            shareAndCommission: $shareAndCommission,
            betCondition: $betCondition
        );
    }

    /**
     * @return array{share_and_commission: array,bet_condition: array }
     */
    public static function build(array $data): array
    {
        return [
            self::IS_ALLOWED => (bool) $data[self::IS_ALLOWED],
            self::SHARE_AND_COMMISSION => [
                self::SHARE => (int) $data[self::SHARE],
                self::UP_LINE_SHARE => (int) ($data[self::ORIGINAL_UPLINE_SHARE] - $data[self::SHARE]),
                self::COMMISSION => (float) $data[self::COMMISSION],
            ],
            self::BET_CONDITION => [
                self::MATCH_LIMIT => (int) $data[self::MATCH_LIMIT],
                self::WIN_LIMIT_PER_DAY => (int) $data[self::WIN_LIMIT_PER_DAY],
                self::DRAGON_TIGER_MIN_BET_PER_TICKET => (int) $data[self::DRAGON_TIGER_MIN_BET_PER_TICKET],
                self::DRAGON_TIGER_MAX_BET_PER_TICKET => (int) $data[self::DRAGON_TIGER_MAX_BET_PER_TICKET],
                self::TIE_MIN_BET_PER_TICKET => (int) ($data[self::TIE_MIN_BET_PER_TICKET] ?? $data[self::DRAGON_TIGER_MIN_BET_PER_TICKET]),
                self::TIE_MAX_BET_PER_TICKET => (int) ($data[self::TIE_MAX_BET_PER_TICKET] ?? floor($data[self::DRAGON_TIGER_MAX_BET_PER_TICKET] / self::TIE_PAYOUT_RATE)),
                self::RED_BLACK_MIN_BET_PER_TICKET => (int) $data[self::RED_BLACK_MIN_BET_PER_TICKET],
                self::RED_BLACK_MAX_BET_PER_TICKET => (int) $data[self::RED_BLACK_MAX_BET_PER_TICKET],
                self::PLAYER_BANKER_MIN_BET_PER_TICKET => (int) $data[self::PLAYER_BANKER_MIN_BET_PER_TICKET],
                self::PLAYER_BANKER_MAX_BET_PER_TICKET => (int) $data[self::PLAYER_BANKER_MAX_BET_PER_TICKET],
                self::ANY_TIE_MIN_BET_PER_TICKET => (int) ($data[self::ANY_TIE_MIN_BET_PER_TICKET] ?? $data[self::PLAYER_BANKER_MIN_BET_PER_TICKET]),
                self::ANY_TIE_MAX_BET_PER_TICKET => (int) ($data[self::ANY_TIE_MAX_BET_PER_TICKET] ?? floor($data[self::PLAYER_BANKER_MAX_BET_PER_TICKET] / self::TIE_PAYOUT_RATE)),
                self::BIG_SMALL_MIN_BET_PER_TICKET => (int) $data[self::BIG_SMALL_MIN_BET_PER_TICKET],
                self::BIG_SMALL_MAX_BET_PER_TICKET => (int) $data[self::BIG_SMALL_MAX_BET_PER_TICKET],
                self::PLAYER_PAIR_BANKER_PAIR_MIN_BET_PER_TICKET => (int) $data[self::PLAYER_PAIR_BANKER_PAIR_MIN_BET_PER_TICKET],
                self::PLAYER_PAIR_BANKER_PAIR_MAX_BET_PER_TICKET => (int) $data[self::PLAYER_PAIR_BANKER_PAIR_MAX_BET_PER_TICKET],
            ],
        ];
    }

    public static function fromDatabase(GameTableCondition $gameTableCondition): GameTableConditionData
    {
        return new GameTableConditionData(
            gameId: $gameTableCondition->game_id,
            gameTableId: $gameTableCondition->game_table_id,
            userId: $gameTableCondition->user_id,
            userType: $gameTableCondition->user_type,
            isAllowed: $gameTableCondition->is_allowed,
            shareAndCommission: $gameTableCondition->share_and_commission,
            betCondition: $gameTableCondition->bet_condition
        );
    }

    public static function default(
        int $gameId,
        int $gameTableId,
        int $userId
    ): GameTableConditionData {
        return new GameTableConditionData(
            gameId: $gameId,
            gameTableId: $gameTableId,
            userId: $userId,
            userType: self::UNKNOWN,
            isAllowed: false,
            shareAndCommission: [],
            betCondition: []
        );
    }

    public function setShare(int $share): GameTableConditionData
    {
        $this->share = $share;

        return $this;
    }

    public function getDragonTigerMinBetPerTicket(): int
    {
        return $this->betCondition[self::DRAGON_TIGER_MIN_BET_PER_TICKET] ?? 0;
    }

    public function getDragonTigerMaxBetPerTicket(): int
    {
        return $this->betCondition[self::DRAGON_TIGER_MAX_BET_PER_TICKET] ?? 0;
    }

    public function getTieMinBetPerTicket(): int
    {
        return $this->betCondition[self::TIE_MIN_BET_PER_TICKET] ?? 0;
    }

    public function getTieMaxBetPerTicket(): int
    {
        return $this->betCondition[self::TIE_MAX_BET_PER_TICKET] ?? 0;
    }

    public function getRedBlackMinBetPerTicket(): int
    {
        return $this->betCondition[self::RED_BLACK_MIN_BET_PER_TICKET] ?? 0;
    }

    public function getRedBlackMaxBetPerTicket(): int
    {
        return $this->betCondition[self::RED_BLACK_MAX_BET_PER_TICKET] ?? 0;
    }

    public function getPlayerBankerMinBetPerTicket(): int
    {
        return $this->betCondition[self::PLAYER_BANKER_MIN_BET_PER_TICKET] ?? 0;
    }

    public function getPlayerBankerMaxBetPerTicket(): int
    {
        return $this->betCondition[self::PLAYER_BANKER_MAX_BET_PER_TICKET] ?? 0;
    }

    public function getAnyTieMinBetPerTicket(): int
    {
        return $this->betCondition[self::ANY_TIE_MIN_BET_PER_TICKET] ?? 0;
    }

    public function getAnyTieMaxBetPerTicket(): int
    {
        return $this->betCondition[self::ANY_TIE_MAX_BET_PER_TICKET] ?? 0;
    }

    public function getBigSmallMinBetPerTicket(): int
    {
        return $this->betCondition[self::BIG_SMALL_MIN_BET_PER_TICKET] ?? 0;
    }

    public function getBigSmallMaxBetPerTicket(): int
    {
        return $this->betCondition[self::BIG_SMALL_MAX_BET_PER_TICKET] ?? 0;
    }

    public function getPlayerPairBankerPairMinBetPerTicket(): int
    {
        return $this->betCondition[self::PLAYER_PAIR_BANKER_PAIR_MIN_BET_PER_TICKET] ?? 0;
    }

    public function getPlayerPairBankerPairMaxBetPerTicket(): int
    {
        return $this->betCondition[self::PLAYER_PAIR_BANKER_PAIR_MAX_BET_PER_TICKET] ?? 0;
    }

    public function getWinLimitPerDay(): int
    {
        return $this->betCondition[self::WIN_LIMIT_PER_DAY] ?? 0;
    }

    public function getGameLimit(): int
    {
        return $this->betCondition[self::MATCH_LIMIT] ?? 0;
    }

    public function getShare(): int
    {
        return $this->shareAndCommission[self::SHARE] ?? $this->share;
    }

    public function getUplineShare(): int
    {
        return $this->shareAndCommission[self::UP_LINE_SHARE] ?? 0;
    }

    public function getCommission(): float|int
    {
        return $this->shareAndCommission[self::COMMISSION] ?? 0;
    }

    public function isUpdate(): bool
    {
        return $this->userType !== self::UNKNOWN;
    }

    public function isDefault(): bool
    {
        return ! $this->isUpdate();
    }

    public function isNotYetSetCondition(): bool
    {
        return $this->isDefault();
    }

    public function getByField(string $field): float|int
    {
        return match ($field) {
            self::SHARE => $this->getShare(),
            self::UP_LINE_SHARE => $this->getUplineShare(),
            self::COMMISSION => $this->getCommission(),
            self::MATCH_LIMIT => $this->getGameLimit(),
            self::WIN_LIMIT_PER_DAY => $this->getWinLimitPerDay(),
            self::DRAGON_TIGER_MIN_BET_PER_TICKET => $this->getDragonTigerMinBetPerTicket(),
            self::DRAGON_TIGER_MAX_BET_PER_TICKET => $this->getDragonTigerMaxBetPerTicket(),
            self::TIE_MIN_BET_PER_TICKET => $this->getTieMinBetPerTicket(),
            self::TIE_MAX_BET_PER_TICKET => $this->getTieMaxBetPerTicket(),
            self::RED_BLACK_MIN_BET_PER_TICKET => $this->getRedBlackMinBetPerTicket(),
            self::RED_BLACK_MAX_BET_PER_TICKET => $this->getRedBlackMaxBetPerTicket(),
            self::PLAYER_BANKER_MIN_BET_PER_TICKET => $this->getPlayerBankerMinBetPerTicket(),
            self::PLAYER_BANKER_MAX_BET_PER_TICKET => $this->getPlayerBankerMaxBetPerTicket(),
            self::ANY_TIE_MIN_BET_PER_TICKET => $this->getAnyTieMinBetPerTicket(),
            self::ANY_TIE_MAX_BET_PER_TICKET => $this->getAnyTieMaxBetPerTicket(),
            self::BIG_SMALL_MIN_BET_PER_TICKET => $this->getBigSmallMinBetPerTicket(),
            self::BIG_SMALL_MAX_BET_PER_TICKET => $this->getBigSmallMaxBetPerTicket(),
            self::PLAYER_PAIR_BANKER_PAIR_MIN_BET_PER_TICKET => $this->getPlayerPairBankerPairMinBetPerTicket(),
            self::PLAYER_PAIR_BANKER_PAIR_MAX_BET_PER_TICKET => $this->getPlayerPairBankerPairMaxBetPerTicket(),
            default => 0
        };
    }
}
