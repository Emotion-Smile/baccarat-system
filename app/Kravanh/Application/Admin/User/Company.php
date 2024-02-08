<?php

namespace App\Kravanh\Application\Admin\User;

use App\Kravanh\Application\Admin\User\Supports\Traits\UserFields;
use App\Kravanh\Domain\User\Supports\Enums\UserType;
use App\Kravanh\Domain\User\Models\Company as CompanyModel;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Hidden;
use Laravel\Nova\Http\Requests\NovaRequest;

class Company extends UserResourceGroup
{
    use UserFields;

    public static $priority = 1;

    public static $model = CompanyModel::class;

    public static $title = 'name';

    public static $search = ['name', 'phone'];

    public function fields(Request $request): array
    {
        return [
            ...$this->userInfo(
                userType: Hidden::make('type')->default(UserType::COMPANY)
            ),
            ...$this->suspend()
        ];
    }
    public static function indexQuery(NovaRequest $request, $query)
    {
        return $query->where('type', UserType::COMPANY);
    }

    protected function getUserType()
    {
        return UserType::COMPANY;
    }
}
