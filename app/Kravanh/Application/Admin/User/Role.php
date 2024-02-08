<?php

namespace App\Kravanh\Application\Admin\User;

use App\Kravanh\Domain\User\Models\Role as RoleModel;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use NovaAttachMany\AttachMany;

class Role extends RoleResourceGroup
{
    public static $priority = 2;

    public static $model = RoleModel::class;

    public static $title = 'label';

    public static $search = ['name', 'label'];

    public static function label()
    {
        return __('Role');
    }

    public static $globallySearchable = false;

    public function subtitle(): ?string
    {
        return $this->label;
    }

    public function fields(Request $request): array
    {
        return [
            ID::make(__('Id'), 'id')->sortable(),
            Text::make(__('Name'), 'name')
                ->rules('required')
                ->creationRules('unique:roles,name')
                ->updateRules('unique:roles,name,{{resourceId}}')
                ->sortable(),

            Text::make(__('Label'), 'label')
                ->rules('required')
                ->sortable(),

            AttachMany::make('Permissions'),
            BelongsToMany::make('Permissions'),
            BelongsToMany::make('Users')
        ];
    }
}
