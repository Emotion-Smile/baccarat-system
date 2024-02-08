<?php

namespace App\Kravanh\Application\Admin\User;

use App\Kravanh\Domain\User\Models\Permission as PermissionModel;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;

class Permission extends RoleResourceGroup
{
    public static $priority = 3;

    public static $model = PermissionModel::class;

    public static $title = 'label';

    public static $search = ['name', 'label'];

    public static $globallySearchable = false;

    public function subtitle(): ?string
    {
        return $this->label;
    }

    public static function label(): string
    {
        return __('Permission');
    }

    public function fields(Request $request): array
    {
        return [
            ID::make(__('Id'), 'id')->sortable(),

            Text::make(__('Name'), 'name')
                ->rules('required')
                ->creationRules('unique:permissions,name')
                ->updateRules('unique:permissions,name,{{resourceId}}')
                ->sortable(),

            Text::make(__('Label'), 'label')
                ->rules('required')
                ->sortable(),

            BelongsToMany::make('Roles')
        ];
    }
}
