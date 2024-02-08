<?php

namespace App\Kravanh\Domain\Baccarat\Dto;

use App\Kravanh\Domain\Baccarat\Exceptions\BaccaratGameBetOnInvalidException;
use App\Kravanh\Domain\Baccarat\Exceptions\BaccaratGameNoLiveGameException;
use App\Kravanh\Domain\Baccarat\Requests\BaccaratGameCreateTicketRequest;
use App\Kravanh\Domain\Baccarat\Support\BaccaratCard;
use App\Kravanh\Domain\Baccarat\Support\BaccaratGameWinner;
use App\Kravanh\Domain\User\Models\Member;

final class BaccaratGameCreateTicketData
{
    public BaccaratGameMemberShareCommissionData $shareCommission;

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
        $this->shareCommission = BaccaratGameMemberShareCommissionData::make($member);
    }

    public static function make(BaccaratGameMemberBetData $data): BaccaratGameCreateTicketData
    {
        return new BaccaratGameCreateTicketData(
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
     * @throws BaccaratGameNoLiveGameException|BaccaratGameBetOnInvalidException
     */
    public static function fromRequest(BaccaratGameCreateTicketRequest $request): BaccaratGameCreateTicketData
    {
        return BaccaratGameCreateTicketData::make(
            data: BaccaratGameMemberBetData::fromRequest($request)
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
        if ($betType === BaccaratCard::Red || $betType === BaccaratCard::Black) {
            return 0.90;
        }

        return $betType === BaccaratGameWinner::Tie ? 7 : 1;
    }
}
