<?php

namespace KravanhEco\Report\Modules\Core\Http\Requests;

use App\Kravanh\Domain\User\Supports\Enums\UserType;
use App\Kravanh\Support\Enums\Period;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;

class WinLoseRequest extends FormRequest
{
    public function rules(): array
    {
        return [];
    }

    public function getUser(): User
    {
        return $this->userId ? User::findOrFail($this->userId) : $this->user();
    }

    public function getPreviousUserId(User $user): int|null
    {
        $userId = $this->user()->id;
        $userType = (string) $user->type;

        if ($this->user()->isSubAccount()) {
            $parent = $this->user()->getParent();

            $userId = $parent['id'];
            $userType = (string) $parent['type'];
        }

        $types = [
            UserType::SENIOR => UserType::SUPER_SENIOR,
            UserType::MASTER_AGENT => UserType::SENIOR,
            UserType::AGENT => UserType::MASTER_AGENT,
        ];

        return Arr::exists($types, $userType) && $user->{$types[$userType]} != $userId 
            ? $user->{$types[$userType]} 
            : null;
    }

    public function getDetailPreviousUserId(User $user): ?int
    {
        return ! $this->user()->isAgent() ? $user->agent : null;
    }

    public function getDate(): string
    {
        $from = $this->get('from');
        $to = $this->get('to');

        if($from && $to) return "{$from},{$to}";

        return  $this->get('date') ?? Period::TODAY;
    }
}