<?php

namespace App\Kravanh\Domain\Baccarat\Dto;

use App\Kravanh\Domain\Baccarat\Actions\BaccaratGameMemberGetUplineShareCommissionAction;
use App\Kravanh\Domain\Game\Models\GameTableCondition;
use App\Kravanh\Domain\User\Models\Member;
use App\Kravanh\Domain\User\Supports\Enums\UserType;
use Illuminate\Support\Collection;

final class BaccaratGameMemberShareCommissionData
{
    private Collection $uplineShareCommission;

    public ShareCommissionData $company;
    public ShareCommissionData $superSenior;
    public ShareCommissionData $senior;
    public ShareCommissionData $masterAgent;
    public ShareCommissionData $agent;
    public ShareCommissionData $member;

    public function __construct(Member $member)
    {
        $this->uplineShareCommission = (new BaccaratGameMemberGetUplineShareCommissionAction())($member);
        $this->fillShareCommission();

    }

    public static function make(Member $member): BaccaratGameMemberShareCommissionData
    {
        return new BaccaratGameMemberShareCommissionData($member);
    }

    private function fillShareCommission(): void
    {
        $this->uplineShareCommission->each(function (GameTableCondition $condition) {

            $commission = $condition->share_and_commission['commission'];

            match ($condition->user_type) {
                UserType::MEMBER => $this->member = ShareCommissionData::make(
                    share: 0,
                    commission: $commission
                ),
                UserType::AGENT => $this->agent = ShareCommissionData::make(
                    share: $this->takeUplineShareFromDownLine(UserType::MEMBER),
                    commission: $commission
                ),
                UserType::MASTER_AGENT => $this->masterAgent = ShareCommissionData::make(
                    share: $this->takeUplineShareFromDownLine(UserType::AGENT),
                    commission: $commission
                ),
                UserType::SENIOR => $this->senior = ShareCommissionData::make(
                    share: $this->takeUplineShareFromDownLine(UserType::MASTER_AGENT),
                    commission: $commission
                ),
                UserType::SUPER_SENIOR => $this->superSenior = ShareCommissionData::make(
                    share: $this->takeUplineShareFromDownLine(UserType::SENIOR),
                    commission: $commission
                )
            };

            $this->company = ShareCommissionData::make(
                share: $this->takeUplineShareFromDownLine(UserType::SUPER_SENIOR),
                commission: 0
            );

        });
    }

    private function takeUplineShareFromDownLine(string $userDownLineType)
    {
        return $this->uplineShareCommission
            ->where('user_type', $userDownLineType)
            ->first()['share_and_commission']['upline_share'];
    }

    public function share(): array
    {
        return $this->toArray(
            member: $this->member->share,
            agent: $this->agent->share,
            masterAgent: $this->masterAgent->share,
            senior: $this->senior->share,
            superSenior: $this->superSenior->share,
            company: $this->company->share
        );
    }

    public function commission(): array
    {
        return $this->toArray(
            member: $this->member->commission,
            agent: $this->agent->commission,
            masterAgent: $this->masterAgent->commission,
            senior: $this->senior->commission,
            superSenior: $this->superSenior->commission,
            company: $this->company->commission
        );
    }

    private function toArray(
        int|float $member,
        int|float $agent,
        int|float $masterAgent,
        int|float $senior,
        int|float $superSenior,
        int|float $company,

    ): array
    {
        return [
            UserType::MEMBER => $member,
            UserType::AGENT => $agent,
            UserType::MASTER_AGENT => $masterAgent,
            UserType::SENIOR => $senior,
            UserType::SUPER_SENIOR => $superSenior,
            UserType::COMPANY => $company
        ];
    }

}
