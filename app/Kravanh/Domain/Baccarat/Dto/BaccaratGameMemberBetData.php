<?php

namespace App\Kravanh\Domain\Baccarat\Dto;

use App\Kravanh\Domain\Baccarat\Actions\BaccaratGameGetLiveGameByTableAction;
use App\Kravanh\Domain\Baccarat\Exceptions\BaccaratGameBetOnInvalidException;
use App\Kravanh\Domain\Baccarat\Exceptions\BaccaratGameNoLiveGameException;
use App\Kravanh\Domain\Baccarat\Models\BaccaratGame;
use App\Kravanh\Domain\Baccarat\Requests\BaccaratGameCreateTicketRequest;
use App\Kravanh\Domain\Baccarat\Support\BaccaratBetOn;
use App\Kravanh\Domain\User\Models\Member;
use App\Kravanh\Domain\User\Supports\Enums\Status;

final class BaccaratGameMemberBetData
{
    public BaccaratGame $game;

    public BaccaratBetOn $bet;

    /**
     * @throws BaccaratGameNoLiveGameException
     * @throws BaccaratGameBetOnInvalidException
     */
    public function __construct(
        public Member $member,
        public float|int $amount,
        public string $betOn,
        public string $betType,
        public string $ip
    ) {
        $this->bet = BaccaratBetOn::make($this->betOn, $this->betType);
        $this->bet->validate();

        $this->game = (new BaccaratGameGetLiveGameByTableAction())($member->getGameTableId());
    }

    /**
     * @throws BaccaratGameNoLiveGameException
     * @throws BaccaratGameBetOnInvalidException
     */
    public static function make(
        Member $member,
        float|int $amount,
        string $betOn,
        string $betType,
        string $ip): BaccaratGameMemberBetData
    {

        return new BaccaratGameMemberBetData(
            member: $member,
            amount: $amount,
            betOn: $betOn,
            betType: $betType,
            ip: $ip
        );
    }

    /**
     * @throws BaccaratGameBetOnInvalidException
     * @throws BaccaratGameNoLiveGameException
     */
    public static function fromRequest(
        BaccaratGameCreateTicketRequest $request
    ): BaccaratGameMemberBetData {
        return BaccaratGameMemberBetData::make(
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
