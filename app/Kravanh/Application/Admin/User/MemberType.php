<?php

namespace App\Kravanh\Application\Admin\User;

use App\Kravanh\Domain\User\Models\MemberType as MemberTypeModel;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;

class MemberType extends RoleResourceGroup
{
    public static $globallySearchable = false;

    public static $model = MemberTypeModel::class;

    public static function label(): string
    {
        return 'Member Type';
    }

    public function title(): string
    {
        return "{$this->name}";
    }


    public function fields(Request $request): array
    {
        return [
            ID::make('Id'),
            Text::make('name')->rules(['required']),
            Number::make("User", function () {
                return $this->users->count();
            }),
            Boolean::make('Active')->default(true),
            HasMany::make("Members"),
            HasMany::make('Agents')
        ];
    }

    public function authorizedToDelete(Request $request): bool
    {
        return false;
    }

}
