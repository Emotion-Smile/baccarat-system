<?php

namespace KravanhEco\Report\Http\Requests;

use App\Kravanh\Domain\User\Supports\Enums\UserType;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class T88Request extends FormRequest
{
    public function rules(): array
    {
        return [];
    }

    public function getPerformUser(): User
    {
        $user = User::whereName($this->name)->first() ?? $this->user();

        if($user->isCompanySubAccount()) {
            return User::whereType(UserType::COMPANY)->first();
        }

        if($user->isSubAccount()) {
            return User::find($user->getEnsureId());   
        }

        return $user;
    }

    public function getPreviousUser(?User $currentUser = null): ?User
    {
        $loginUser = $this->user();
        $currentUser = $currentUser ?? $this->getPerformUser(); 

        $upLineType = $currentUser->getUpLineType();

        $loginUserType = $loginUser->type->value;

        if($loginUser->isSubAccount()) {
            $loginUserType = $loginUser->getEnsureType();
        }
        
        return $loginUserType !== $upLineType   
            ? User::find($currentUser->{$upLineType})
            : null;
    }

    public function getDetailPreviousUser(?User $member = null): ?User
    {
        $member = $member ?? User::query()->whereName($this->name)->firstOrFail();

        $userType = $this->user()->type->value;

        if($this->user()->isSubAccount()) {
            $userType = $this->user()->getEnsureType(); 
        }
    
        return $userType !== UserType::AGENT
            ? User::find($member->agent)
            : null;
    }
}