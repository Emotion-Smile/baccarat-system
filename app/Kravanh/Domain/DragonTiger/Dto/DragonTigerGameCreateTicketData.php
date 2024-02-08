<?php

namespace App\Kravanh\Domain\DragonTiger\Dto;

use App\Kravanh\Domain\DragonTiger\Exceptions\DragonTigerGameBetOnInvalidException;
use App\Kravanh\Domain\DragonTiger\Exceptions\DragonTigerGameNoLiveGameException;
use App\Kravanh\Domain\DragonTiger\Requests\DragonTigerGameCreateTicketRequest;
use App\Kravanh\Domain\DragonTiger\Support\DragonTigerCard;
use App\Kravanh\Domain\DragonTiger\Support\DragonTigerGameWinner;
use App\Kravanh\Domain\User\Models\Member;

final class DragonTigerGameCreateTicketData
{
    public DragonTigerGameMemberShareCommissionData $shareCommission;

    public function __construct(
        public readonly Member $member,
        public readonly int $gameTableId,
        public readonly int $dragonTigerGameId,
        public readonly float|int $amount,
        public readonly string $betOn,
        public readonly string $betType,
        public readonly float $payoutRate,
        public readonly string $ip
    ) {
        $this->shareCommission = DragonTigerGameMemberShareCommissionData::make($member);
    }

    public static function make(DragonTigerGameMemberBetData $data): DragonTigerGameCreateTicketData
    {
        return new DragonTigerGameCreateTicketData(
            member: $data->member,
            gameTableId: $data->game->game_table_id,
            dragonTigerGameId: $data->game->id,
            amount: $data->amount,
            betOn: $data->betOn,
            betType: $data->betType,
            payoutRate: self::payoutRate($data->betType),
            ip: $data->ip
        );

    }

    /**
     * @throws DragonTigerGameNoLiveGameException|DragonTigerGameBetOnInvalidException
     */
    public static function fromRequest(DragonTigerGameCreateTicketRequest $request): DragonTigerGameCreateTicketData
    {
        return DragonTigerGameCreateTicketData::make(
            data: DragonTigerGameMemberBetData::fromRequest($request)
        );
    }

    public function payout(): float|int
    {
        return $this->amountKHR() * $this->payoutRate;
    }

    public function share(): array
    {
        return $this->shareCommission->share();
    }

    public function commission(): array
    {
        return $this->shareCommission->commission();
    }

    public function amountKHR(): float|int
    {
        return $this->member->toKHR($this->amount);
    }

    public static function payoutRate(string $betType): float|int
    {
        if ($betType === DragonTigerCard::Red || $betType === DragonTigerCard::Black) {
            return 0.90;
        }

        return $betType === DragonTigerGameWinner::Tie ? 7 : 1;
    }
}
