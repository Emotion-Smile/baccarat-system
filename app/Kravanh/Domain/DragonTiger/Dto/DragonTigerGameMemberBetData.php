<?php

namespace App\Kravanh\Domain\DragonTiger\Dto;

use App\Kravanh\Domain\DragonTiger\Actions\DragonTigerGameGetLiveGameByTableAction;
use App\Kravanh\Domain\DragonTiger\Exceptions\DragonTigerGameBetOnInvalidException;
use App\Kravanh\Domain\DragonTiger\Exceptions\DragonTigerGameNoLiveGameException;
use App\Kravanh\Domain\DragonTiger\Models\DragonTigerGame;
use App\Kravanh\Domain\DragonTiger\Requests\DragonTigerGameCreateTicketRequest;
use App\Kravanh\Domain\DragonTiger\Support\DragonTigerBetOn;
use App\Kravanh\Domain\User\Models\Member;
use App\Kravanh\Domain\User\Supports\Enums\Status;

final class DragonTigerGameMemberBetData
{
    public DragonTigerGame $game;

    public DragonTigerBetOn $bet;

    /**
     * @throws DragonTigerGameNoLiveGameException
     * @throws DragonTigerGameBetOnInvalidException
     */
    public function __construct(
        public Member $member,
        public float|int $amount,
        public string $betOn,
        public string $betType,
        public string $ip
    ) {
        $this->bet = DragonTigerBetOn::make($this->betOn, $this->betType);
        $this->bet->validate();

        $this->game = (new DragonTigerGameGetLiveGameByTableAction())($member->getGameTableId());
    }

    /**
     * @throws DragonTigerGameNoLiveGameException
     * @throws DragonTigerGameBetOnInvalidException
     */
    public static function make(
        Member $member,
        float|int $amount,
        string $betOn,
        string $betType,
        string $ip): DragonTigerGameMemberBetData
    {

        return new DragonTigerGameMemberBetData(
            member: $member,
            amount: $amount,
            betOn: $betOn,
            betType: $betType,
            ip: $ip
        );
    }

    /**
     * @throws DragonTigerGameBetOnInvalidException
     * @throws DragonTigerGameNoLiveGameException
     */
    public static function fromRequest(
        DragonTigerGameCreateTicketRequest $request
    ): DragonTigerGameMemberBetData {
        return DragonTigerGameMemberBetData::make(
            member: $request->user(),
            amount: $request->get('amount'),
            betOn: $request->get('betOn'),
            betType: $request->get('betType'),
            ip: $request->ip());
    }

    public function isAccountEnabled(): bool
    {
        return $this->member->status->is(Status::OPEN);
    }
}
