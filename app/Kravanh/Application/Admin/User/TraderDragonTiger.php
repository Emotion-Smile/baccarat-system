<?php

namespace App\Kravanh\Application\Admin\User;

use App\Kravanh\Application\Admin\Environment\Environment;
use App\Kravanh\Application\Admin\User\Supports\Traits\UserFields;
use App\Kravanh\Domain\Game\App\Nova\GameTableResource;
use App\Kravanh\Domain\User\Models\Trader as TraderModel;
use App\Kravanh\Domain\User\Supports\Enums\UserType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Hidden;
use Laravel\Nova\Http\Requests\NovaRequest;

class TraderDragonTiger extends UserResourceGroup
{
    use UserFields;

    public static $priority = 8;

    public static $model = TraderModel::class;

    public static $title = 'name';

    public static $search = ['name'];


    public static function indexQuery(NovaRequest $request, $query): Builder
    {
        return $query
            ->where('type', UserType::TRADER)
            ->where('two_factor_secret', 'dragon_tiger');
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
                'Table',
                'gameTable',
                GameTableResource::class
            )
                ->required()
                ->showOnUpdating(false),

            Hidden::make('two_factor_secret')->default('dragon_tiger'),
            ...$this->userInfo(
                userType: Hidden::make('type')->default(UserType::TRADER)
            ),

            ...$this->suspend()
        ];
    }
}
