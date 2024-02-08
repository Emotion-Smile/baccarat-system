<?php

namespace App\Kravanh\Domain\User\Observers;

use App\Kravanh\Domain\User\Supports\Enums\UserType;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class UserObserver
{
    public function creating(User $user): void
    {

        if (!app()->runningInConsole() && !app()->environment('cypress')) {

            // $this->ensureValidShareValue($user);
            $user = $this->myPositionShare($user);
            $user = $this->makeUserProperties($user);

        }

    }

    public function created(User $user): void
    {
        if (!app()->runningInConsole() && !app()->environment('cypress')) {

            $this->autoAssignRoleSpecificUserType($user);

        }


    }

    public function updating(User $user): void
    {

        // $this->ensureValidShareValue($user);
        if (!user()?->isCompany()) {
            $user = $this->myPositionShare($user);
        }
    }

    public function updated(User $user): void
    {

        if ($user->type->is(UserType::AGENT) || $user->type->is(UserType::MEMBER)) {
            $user->setCanBetWhenDisable();
        }

        if (!user()?->type->is(UserType::MEMBER) && !user()?->type->is(UserType::TRADER) && !user()?->type->is(UserType::COMPANY)) {


            $this->updateSuspendAllSubAccountsDependOnParent($user);
        }
    }

    protected function autoAssignRoleSpecificUserType(User $user): void
    {
        $roleName = $user->type->value;

        $userTypesToAssignAuto = [
            UserType::COMPANY,
            UserType::SUPER_SENIOR,
            UserType::SENIOR,
            UserType::MASTER_AGENT,
            UserType::AGENT,
            UserType::SUB_ACCOUNT
        ];

        if (in_array($roleName, $userTypesToAssignAuto)) {
            $user->assignRole($roleName);
        }
    }

    protected function makeUserProperties($user)
    {
        $newUser = match ($user->type->value) {
            UserType::SUPER_SENIOR => $this->createSuperSeniorHandler($user),
            UserType::SENIOR => $this->createSeniorHandler($user),
            UserType::MASTER_AGENT => $this->createMasterAgentHandler($user),
            UserType::AGENT => $this->createAgentHandler($user),
            UserType::MEMBER => $this->createMemberHandler($user),
            UserType::TRADER => $this->createTraderHandler($user),
            UserType::SUB_ACCOUNT => $this->createSubAccountHandler($user),
            default => $user
        };

        $noneEnvironmentBelongTo = [UserType::SUPER_SENIOR, UserType::TRADER];

        if (!in_array($user->type, $noneEnvironmentBelongTo)) {
            $newUser->environment_id = user()?->environment_id;
        }

        return $newUser;
    }

    protected function createSuperSeniorHandler($user)
    {
        if (!user()->type->is(UserType::COMPANY)) {
            throw ValidationException::withMessages(
                [
                    'name' => 'Oop, only company can create super senior account.'
                ]
            );
        }

        return $user;
    }


    protected function createSeniorHandler($user)
    {
        if (!user()->type->is(UserType::SUPER_SENIOR)) {
            throw ValidationException::withMessages(
                [
                    'name' => 'Oop, only super senior can create senior account.'
                ]
            );
        }

        $user[UserType::SUPER_SENIOR] = user()->id;
        $user['currency'] = user()->currency;
        return $user;
    }

    protected function createMasterAgentHandler($user)
    {
        if (!user()->type->is(UserType::SENIOR)) {
            throw ValidationException::withMessages(
                [
                    'name' => 'Oop, only senior can create master agent account.'
                ]
            );
        }

        $user[UserType::SUPER_SENIOR] = user()->{UserType::SUPER_SENIOR};
        $user[UserType::SENIOR] = user()->id;
        $user['currency'] = user()->currency;

        return $user;
    }

    protected function createAgentHandler($user)
    {
        if (!user()->type->is(UserType::MASTER_AGENT)) {
            throw ValidationException::withMessages(
                [
                    'name' => 'Oop, only master agent can create agent account.'
                ]
            );
        }

        $user[UserType::SUPER_SENIOR] = user()->{UserType::SUPER_SENIOR};
        $user[UserType::SENIOR] = user()->{UserType::SENIOR};
        $user[UserType::MASTER_AGENT] = user()->id;
        $user['currency'] = user()->currency;

        return $user;
    }

    protected function createMemberHandler($user)
    {
        if (!user()->type->is(UserType::AGENT)) {
            throw ValidationException::withMessages(
                [
                    'name' => 'Oop, only agent can create member account.'
                ]
            );
        }

        $user[UserType::SUPER_SENIOR] = user()->{UserType::SUPER_SENIOR};
        $user[UserType::SENIOR] = user()->{UserType::SENIOR};
        $user[UserType::MASTER_AGENT] = user()->{UserType::MASTER_AGENT};
        $user[UserType::AGENT] = user()->id;
        $user['currency'] = user()->currency;

        return $user;
    }

    protected function createSubAccountHandler($user)
    {
        if (user()->type->is(UserType::COMPANY)) {
            return $user;
        }

        $user[user()->type->value] = user()->id;
        $user['currency'] = user()->currency;
        $user['domain_id'] = getParentDomain(user())->domain_id;

        return $user;
    }

    protected function createTraderHandler($user)
    {
        if (!user()->type->is(UserType::COMPANY)) {
            throw ValidationException::withMessages(
                [
                    'name' => 'Oop, only company can create trader account.'
                ]
            );
        }

        return $user;
    }

    protected function ensureValidShareValue($user): void
    {
        if ($user->condition) {

            $per = $user->condition['my_position_share'] + $user->condition['down_line_share'];

            if ($per > 100) {
                throw ValidationException::withMessages([
                    'condition->my_position_share' => 'My position share + Down line share cannot greater than 100%'
                ]);
            }
        }

    }

    protected function myPositionShare($user)
    {
        if (!$user->condition) {
            return $user;
        }

        if (array_key_exists('down_line_share', $user->condition)) {
            $userDownLineShare = user()->condition['down_line_share'] ?? 100;
            $myPositionShare = $userDownLineShare - $user->condition['down_line_share'];

            $user->condition = array_merge($user->condition, [
                'my_position_share' => $myPositionShare
            ]);
        }

        return $user;
    }

    protected function updateSuspendAllSubAccountsDependOnParent(User $user): void
    {

        $exceptUserType = [
            UserType::COMPANY,
            UserType::TRADER,
            UserType::DEVELOPER,
            UserType::SUB_ACCOUNT,
            UserType::REPORTER,
            UserType::MEMBER
        ];


        if (!in_array($user->type, $exceptUserType)) {

            if ((bool)$user->suspend === (bool)$user->getOriginal('suspend')) {
                return;
            }

            User::where($user->type, $user->id)
                ->update([
                    'suspend' => $user->isSuspend()
                ]);
        }

    }

}
