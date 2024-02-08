<?php

namespace Database\Seeders;

use App\Kravanh\Domain\Match\Supports\Enums\BetType;
use App\Kravanh\Domain\User\Actions\BalanceTransferAction;
use App\Kravanh\Domain\User\Actions\CompanyDepositToSuperSeniorAction;
use App\Kravanh\Domain\User\Exceptions\BalanceIsBlocked;
use App\Kravanh\Domain\User\Models\Agent;
use App\Kravanh\Domain\User\Models\MasterAgent;
use App\Kravanh\Domain\User\Models\Member;
use App\Kravanh\Domain\User\Models\Role;
use App\Kravanh\Domain\User\Models\Senior;
use App\Kravanh\Domain\User\Models\SuperSenior;
use App\Kravanh\Domain\User\Supports\Enums\UserType;
use App\Kravanh\Support\Enums\Currency;
use App\Models\User;
use Database\Factories\FactoryHelper;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserTestSeeder extends Seeder
{
    use FactoryHelper;

    /**
     * @throws \Throwable
     * @throws BalanceIsBlocked
     */
    public function run(string $currency = Currency::USD, int $deposit = 10_000_000): void
    {
        $environmentId = $this->getEnvId();
        $groupId = $this->getGroupId();

        $balanceAmount = fromKHRtoCurrency($deposit * 2, Currency::fromKey($currency));

        // Company
        $company = User::create([
            'name' => 'company_seeder',
            'password' => Hash::make('password'),
            'type' => UserType::COMPANY,
            'environment_id' => $environmentId,
        ]);

        $company
            ->roles()
            ->attach(Role::whereName('company')->first());

        //Super Senior
        $superSenior1 = $this->createSuperSenior(
            name: 1,
            currency: $currency,
            environmentId: $environmentId
        );

        (new CompanyDepositToSuperSeniorAction())(
            company: $company,
            superSenior: $superSenior1,
            remark: 'seed',
            amount: $balanceAmount
        );

        // Senior
        $senior1 = $this->createSenior(
            name: 1,
            superSenior: $superSenior1
        );

        (new BalanceTransferAction())(
            sender: $superSenior1,
            receiver: $senior1,
            remark: 'seed',
            amount: $balanceAmount,
            mode: 'to_downline'
        );

        // Master Agent
        $masterAgent1 = $this->createDownline(
            parent: $senior1,
            name: 'master_agent_1',
            userType: UserType::MASTER_AGENT
        );


        (new BalanceTransferAction())(
            sender: $senior1,
            receiver: $masterAgent1,
            remark: 'seed',
            amount: $balanceAmount,
            mode: 'to_downline'
        );

        // Agent
        $agent1 = $this->createDownline(
            parent: $masterAgent1,
            name: 'agent_1',
            userType: UserType::AGENT
        );

        (new BalanceTransferAction())(
            sender: $masterAgent1,
            receiver: $agent1,
            remark: 'seed',
            amount: $balanceAmount,
            mode: 'to_downline'
        );


        // Member
        $member_1 = $this->createDownline(
            parent: $agent1,
            name: 'member_1',
            userType: UserType::MEMBER,
            groupId: $groupId
        );
        (new BalanceTransferAction())(
            sender: $agent1,
            receiver: $member_1,
            remark: 'seed',
            amount: $balanceAmount / 2,
            mode: 'to_downline'
        );


        $member_1_1 = $this->createDownline(
            parent: $agent1,
            name: 'member_1_1',
            userType: UserType::MEMBER,
            groupId: $groupId
        );

        (new BalanceTransferAction())(
            sender: $agent1,
            receiver: $member_1_1,
            remark: 'seed',
            amount: $balanceAmount / 2,
            mode: 'to_downline'
        );

        User::create([
            'name' => 'trader',
            'password' => Hash::make('password'),
            'type' => UserType::TRADER,
            'environment_id' => $environmentId,
            'group_id' => $groupId
        ]);

        User::create([
            'name' => 'trader_1',
            'password' => Hash::make('password'),
            'type' => UserType::TRADER,
            'environment_id' => $environmentId,
            'group_id' => $groupId
        ]);
    }

    protected function createSuperSenior(
        int    $name,
        string $currency,
        int    $environmentId = 1,
        int    $myPositionShare = 70,
        int    $downLineShare = 30
    ): SuperSenior
    {
        return tap(SuperSenior::create([
            'name' => 'super_senior_' . $name,
            'password' => Hash::make('password'),
            'currency' => $currency,
            'type' => UserType::SUPER_SENIOR,
            'environment_id' => $environmentId,
            'condition' => [
                'my_position_share' => $myPositionShare,
                'down_line_share' => $downLineShare
            ]
        ]), function (SuperSenior $superSenior) {
            $superSenior->roles()->attach(Role::whereName('super_senior')->first());
        });
    }

    protected function createSenior(
        int         $name,
        SuperSenior $superSenior,
        int         $myPositionShare = 70,
        int         $downLineShare = 30
    ): Senior
    {
        return tap(Senior::create([
            'super_senior' => $superSenior->id,
            'name' => 'senior_' . $name,
            'password' => Hash::make('password'),
            'type' => UserType::SENIOR,
            'currency' => $superSenior->currency->value,
            'environment_id' => $superSenior->environment_id,
            'condition' => [
                'my_position_share' => $myPositionShare,
                'down_line_share' => $downLineShare
            ]
        ]), function (Senior $senior) {
            $senior->roles()->attach(Role::whereName('senior')->first());
        });
    }

    protected function createDownline(
        mixed  $parent,
        string $name,
        string $userType,
        int    $myPositionShare = 70,
        int    $downLineShare = 30,
        int    $minimumBetPerTicket = 4000,
        int    $maximumBetPerTicket = 3_000_000,
        int    $matchLimit = 3_000_000,
        int    $creditLimit = 4_000_000,
        int    $groupId = 1
    ): Member|Agent|MasterAgent
    {

        $minimumBetPerTicket = fromKHRtoCurrency($minimumBetPerTicket, $parent->currency);
        $maximumBetPerTicket = fromKHRtoCurrency($maximumBetPerTicket, $parent->currency);
        $matchLimit = fromKHRtoCurrency($matchLimit, $parent->currency);
        $creditLimit = fromKHRtoCurrency($creditLimit, $parent->currency);

        $payload = [
            'name' => $name,
            'password' => Hash::make('password'),
            'type' => $userType,
            'group_id' => $groupId,
            'environment_id' => $parent->environment_id,
            'currency' => $parent->currency,
            'bet_type' => BetType::AUTO_ACCEPT,
            'condition' => [
                'my_position_share' => $myPositionShare,
                'down_line_share' => $downLineShare,
                'minimum_bet_per_ticket' => $minimumBetPerTicket,
                'maximum_bet_per_ticket' => $maximumBetPerTicket,
                'match_limit' => $matchLimit,
                'credit_limit' => $creditLimit
            ]
        ];

        if ($userType == UserType::MASTER_AGENT) {
            $payload['super_senior'] = $parent->super_senior;
            $payload['senior'] = $parent->id;
        }

        if ($userType == UserType::AGENT) {
            $payload['super_senior'] = $parent->super_senior;
            $payload['senior'] = $parent->senior;
            $payload['master_agent'] = $parent->id;
        }

        if ($userType == UserType::MEMBER) {
            $payload['super_senior'] = $parent->super_senior;
            $payload['senior'] = $parent->senior;
            $payload['master_agent'] = $parent->master_agent;
            $payload['agent'] = $parent->id;
        }

        $user = match ($userType) {
            UserType::MASTER_AGENT => MasterAgent::create($payload),
            UserType::AGENT => Agent::create($payload),
            UserType::MEMBER => Member::create($payload),
            default => User::create($payload)
        };

        $user->roles()->attach(Role::whereName($userType)->first());
        return $user;
    }
}
