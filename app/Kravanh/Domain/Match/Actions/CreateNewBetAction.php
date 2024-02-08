<?php

namespace App\Kravanh\Domain\Match\Actions;

use App\Kravanh\Domain\Environment\Models\Group;
use App\Kravanh\Domain\Match\DataTransferObject\NewBetData;
use App\Kravanh\Domain\Match\Exceptions\AccountNotAllow;
use App\Kravanh\Domain\Match\Exceptions\AlreadyBetOnOtherTable;
use App\Kravanh\Domain\Match\Exceptions\BalanceBlocked;
use App\Kravanh\Domain\Match\Exceptions\BetAmountHigherThanMaximumPricePerTicketAllowed;
use App\Kravanh\Domain\Match\Exceptions\BetAmountLowerThanMinimumPricePerTicketAllowed;
use App\Kravanh\Domain\Match\Exceptions\MatchBettingClosed;
use App\Kravanh\Domain\Match\Exceptions\MatchBettingFailed;
use App\Kravanh\Domain\Match\Exceptions\MatchBettingNotYetOpen;
use App\Kravanh\Domain\Match\Exceptions\MatchResultInvalid;
use App\Kravanh\Domain\Match\Exceptions\NoMatchFound;
use App\Kravanh\Domain\Match\Exceptions\NotEnoughCredit;
use App\Kravanh\Domain\Match\Exceptions\NotMemberAccount;
use App\Kravanh\Domain\Match\Exceptions\OverMatchLimit;
use App\Kravanh\Domain\Match\Exceptions\OverWinLimitPerDay;
use App\Kravanh\Domain\Match\Exceptions\TransactionNotAllowed;
use App\Kravanh\Domain\Match\Models\BetRecord;
use App\Kravanh\Domain\Match\Models\Matches;
use App\Kravanh\Domain\Match\Supports\Enums\BetStatus;
use App\Kravanh\Domain\Match\Supports\MemberTypeHelper;
use App\Kravanh\Domain\User\Supports\Enums\Status;
use App\Kravanh\Domain\User\Supports\Enums\UserType;
use App\Kravanh\Support\TransactionSetting;
use App\Models\User;
use Illuminate\Support\Facades\Date;

class CreateNewBetAction
{

    /**
     * @throws AccountNotAllow
     * @throws OverWinLimitPerDay
     * @throws MatchBettingClosed
     * @throws BetAmountLowerThanMinimumPricePerTicketAllowed
     * @throws NotEnoughCredit
     * @throws BetAmountHigherThanMaximumPricePerTicketAllowed
     * @throws MatchBettingNotYetOpen
     * @throws NotMemberAccount
     * @throws BalanceBlocked
     * @throws MatchResultInvalid
     * @throws OverMatchLimit
     * @throws AlreadyBetOnOtherTable
     * @throws NoMatchFound
     * @throws TransactionNotAllowed
     * @throws MatchBettingFailed
     */
    public function __invoke(NewBetData $bet): BetRecord
    {
        $member = $bet->user;
        $match = $bet->match;

        if (TransactionSetting::isDisableMemberBet()) {
            throw TransactionNotAllowed::disableMemberBet();
        }

        if (!$member->type->is(UserType::MEMBER)) {
            throw new NotMemberAccount();
        }

        if (!$member->status->is(Status::OPEN)) {
            throw new AccountNotAllow();
        }

        if (!$match) {
            throw new NoMatchFound();
        }

        if (!$match->isBettingOpened()) {
            throw new MatchBettingNotYetOpen();
        }

        if (!$member->isNormalMember()) {

            $canBet = MemberTypeHelper::from(
                $match->id,
                $member->getMemberTypeId()
            )->canBet();

            if (!$canBet) {
                throw new MatchBettingClosed();
            }
        }

        if ($match->isBettingClosed()) {
            throw new MatchBettingClosed();
        }

        $this->validateBetAmount($member, $bet);
//      $this->minMaxBetValidator($match, $member, $bet);

        if ($member->getCurrentBalance() < $bet->amount) {
            throw new NotEnoughCredit();
        }

        if ($member->balanceIsBlocked()) {
            throw new BalanceBlocked();
        }

//        if ($member->isOverMatchLimitAmount($bet->amount, $match->id, $match->group_id, $member->currency)) {
//            //throw new OverMatchLimit();
//            throw new BetAmountHigherThanMaximumPricePerTicketAllowed();
//        }

//        if ($member->isOverWinLimitPerDay()) {
//            throw new OverWinLimitPerDay();
//        }

//        if (!$member->canBetThisTable($match->id)) {
//            throw new AlreadyBetOnOtherTable();
//        }

        $bet->amount = $member->toKHR($bet->amount);

        if ($match->isNotAllowToBet($bet->betOn, $bet->amount, $member)) {
            throw new MatchBettingFailed();
        }


        $member->updateLastBetAt($match->fight_number, $match->group_id);
        $member->todayBetAmountIncrement($bet->amount);
        $member->incrementBetAmountPerMatch($bet->amount, $match->id);

        $rate = $member->computePayoutDeduction($match->payoutRate($bet->betOn));

        return BetRecord::create([
            'user_id' => $member->id,
            'environment_id' => $member->environment_id,
            'group_id' => $member->group_id,
            'member_type_id' => $member->getMemberTypeId(),
            'super_senior' => $member->super_senior,
            'senior' => $member->senior,
            'master_agent' => $member->master_agent,
            'agent' => $member->agent,
            'match_id' => $match->id,
            'fight_number' => $match->fight_number,
            'amount' => $bet->amount,
            'payout_rate' => $rate,
            'payout' => $payout = (int)($bet->amount * $rate),
            'benefit' => ($bet->amount - $payout),
            'type' => $member->bet_type,//BetType::AUTO_ACCEPT, //@TODO need check with user
            'status' => BetStatus::ACCEPTED,//$member->bet_type->is(BetType::AUTO_ACCEPT) ? BetStatus::ACCEPTED : BetStatus::PENDING, //@TODO  need check with user
            'bet_on' => $bet->betOn,
            'bet_date' => Date::today()->format('Y-m-d'),
            'bet_time' => Date::now(),
            'currency' => $member->currency,
            'ip' => $bet->ip
        ]);

    }


    /**
     * @throws BetAmountHigherThanMaximumPricePerTicketAllowed
     * @throws BetAmountLowerThanMinimumPricePerTicketAllowed
     * @throws OverWinLimitPerDay
     */
    private function validateBetAmount(User $member, NewBetData $bet): void
    {
        $condition = $member->betCondition();

        if ($bet->amount < $condition->minBetPerTicket) {
            throw new BetAmountLowerThanMinimumPricePerTicketAllowed();
        }

        if ($bet->amount > $condition->maxBetPerTicket) {
            throw new BetAmountHigherThanMaximumPricePerTicketAllowed();
        }

        $totalBet = $member->getTotalBetAmountOfMatch($bet->match->id) + $member->toKHR($bet->amount);

        if ($totalBet > $member->toKHR($condition->matchLimit)) {
            throw new BetAmountHigherThanMaximumPricePerTicketAllowed();
        }

        if ($condition->winLimitPerDay > 0) {
            if ($member->totalWinToday() >= $member->toKHR($condition->winLimitPerDay)) {
                throw new OverWinLimitPerDay();
            }
        }

    }

    /**
     * @throws BetAmountLowerThanMinimumPricePerTicketAllowed
     * @throws BetAmountHigherThanMaximumPricePerTicketAllowed
     */
    protected function minMaxBetValidator(Matches $match, User $member, NewBetData $bet): void
    {
        $group = Group::select('meta')
            ->find($match->group_id);

        $groupMinBet = $group->meta[$member->currency . '_min_bet'] ?? 0;
        $groupMaxBet = $group->meta[$member->currency . '_max_bet'] ?? 0;

        $memberMinBetLimit = $member->condition['minimum_bet_per_ticket'] ?? 0;
        $memberMaxBetLimit = $member->condition['maximum_bet_per_ticket'] ?? 0;

        if (($groupMinBet > 0) && ($memberMinBetLimit > $groupMinBet)) {
            $memberMinBetLimit = $groupMinBet;
        }

        if (($groupMaxBet > 0) && ($memberMaxBetLimit > $groupMaxBet)) {
            $memberMaxBetLimit = $groupMaxBet;
        }

        if ($bet->amount < $memberMinBetLimit) {
            throw new BetAmountLowerThanMinimumPricePerTicketAllowed();
        }

        if ($bet->amount > $memberMaxBetLimit) {
            throw new BetAmountHigherThanMaximumPricePerTicketAllowed();
        }


    }
}
