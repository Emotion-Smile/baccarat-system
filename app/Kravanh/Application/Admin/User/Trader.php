<?php

namespace App\Kravanh\Application\Admin\User;

use App\Kravanh\Application\Admin\Environment\Environment;
use App\Kravanh\Application\Admin\Environment\Group;
use App\Kravanh\Application\Admin\User\Supports\Traits\UserFields;
use App\Kravanh\Domain\User\Models\Trader as TraderModel;
use App\Kravanh\Domain\User\Supports\Enums\UserType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Hidden;
use Laravel\Nova\Http\Requests\NovaRequest;

class Trader extends UserResourceGroup
{
    use UserFields;

    public static $priority = 6;

    public static $model = TraderModel::class;

    public static $title = 'name';

    public static $search = ['name'];


    public static function indexQuery(NovaRequest $request, $query): Builder
    {
        return $query->where('type', UserType::TRADER)
            ->whereNull('two_factor_secret');
    }

    public function fields(Request $request): array
    {
        return $this->trader();
    }

    protected function trader(): array
    {
        return [
            BelongsTo::make(
                'Environment',
                'environment',
                Environment::class
            )
                ->default(1)
                ->showOnUpdating(false),
            BelongsTo::make(
                'Group',
                'group',
                Group::class
            )
                ->default(1)
                ->showOnUpdating(false),
            ...$this->userInfo(
                userType: Hidden::make('type')->default(UserType::TRADER)
            ),
            ...$this->suspend()
        ];
    }
}
