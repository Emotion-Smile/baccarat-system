<?php

namespace KravanhEco\Report\Modules\Mixed\Http\Requests;

use App\Kravanh\Domain\User\Supports\Enums\UserType;
use App\Kravanh\Support\Enums\Period;
use App\Models\User;
use Arr;
use Illuminate\Foundation\Http\FormRequest;

class WinLoseRequest extends FormRequest
{
    public function rules(): array
    {
        return [];
    }

    public function getUser(): User
    {
        $user = $this->userId ? User::find($this->userId) : $this->user();

        if($user->isCompanySubAccount()) {
            return User::whereType(UserType::COMPANY)->first();
        }

        if($user->isSubAccount()) {
            return User::find($user->getEnsureId());   
        }

        return $user;
    }

    public function getDate(): string
    {
        $from = $this->get('from');
        $to = $this->get('to');

        if($from && $to) return "{$from},{$to}";

        return  $this->get('date') ?? Period::TODAY;
    }

    public function getPreviousUserId(?User $currentUser = null): int|null
    {
        $loginUser = $this->user();
        $currentUser = $currentUser ?? $this->getPerformUser(); 

        $upLineType = $currentUser->getUpLineType();

        $loginUserType = $loginUser->type->value;

        if($loginUser->isSubAccount()) {
            $loginUserType = $loginUser->getEnsureType();
        }
        
        return $loginUserType !== $upLineType   
            ? User::find($currentUser->{$upLineType})?->id
            : null;
    }
}