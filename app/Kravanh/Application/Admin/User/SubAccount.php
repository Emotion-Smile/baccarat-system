<?php

namespace App\Kravanh\Application\Admin\User;

use App\Kravanh\Application\Admin\User\Supports\Traits\UserFields;
use App\Kravanh\Domain\User\Models\SubAccount as SubAccountModel;
use App\Kravanh\Domain\User\Supports\Enums\UserType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Hidden;
use Laravel\Nova\Http\Requests\NovaRequest;

class SubAccount extends UserResourceGroup
{
    use UserFields;


    public static $priority = 7;

    public static $model = SubAccountModel::class;

    public static $title = 'Sub Account';

    public static $search = ['name'];

    public function fields(Request $request): array
    {
        return [
            ...$this->userInfo(
                userType: Hidden::make('type')->default(UserType::SUB_ACCOUNT)
            ),
            Boolean::make(__('Suspend'), 'suspend')
                ->default(false),
        ];
    }

    public static function indexQuery(NovaRequest $request, $query): Builder
    {
        parent::indexQuery($request, $query);

        return $query->where('type', UserType::SUB_ACCOUNT);
    }

    protected function getUserType()
    {
        return UserType::SUB_ACCOUNT;
    }
}
