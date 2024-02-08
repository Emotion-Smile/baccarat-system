<?php

namespace App\Kravanh\Application\Admin\User;

use App\Kravanh\Application\Admin\User\Supports\Traits\UserFields;
use App\Models\User as UserModel;
use Illuminate\Http\Request;
use KABBOUCHI\NovaImpersonate\Impersonate;

class User extends RoleResourceGroup
{
    use UserFields;

    public static $priority = 1;

    public static $model = UserModel::class;

    public static $title = 'name';

    public static $with = ['roles:id,label'];

    public function subtitle(): string
    {
        return $this->phone;
    }

    public static $search = ['name', 'email'];

    public static function label(): string
    {
        return 'Users';
    }

    public function fields(Request $request): array
    {
        return [
            ...$this->userInfo(
                rolesFields: $this->roles()
            ),
            ...$this->downlineCondition(),
            ...$this->accountStatus(),
            Impersonate::make($this)
        ];
    }
}
