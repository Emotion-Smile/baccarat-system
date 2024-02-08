<?php

namespace App\Kravanh\Application\Admin\User;

use App\Kravanh\Application\Admin\User\Lenses\MemberHasTheSamePassword;
use App\Kravanh\Domain\User\Models\MemberPassword;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Titasgailius\SearchRelations\SearchesRelations;

class UserTracking extends RoleResourceGroup
{
    use SearchesRelations;

    public static $model = MemberPassword::class;

    public static $with = ['user:id,name'];

    public static function label(): string
    {
        return 'Member Tracking';
    }

    public static function searchableRelations(): array
    {
        return [
            'user' => ['name'],
        ];
    }

    public function fields(Request $request): array
    {
        return [
            Text::make('User', function () {
                return <<<HTML
                    <a href="/cbs-admin/resources/members/{$this->user->id}">{$this->user->name}</a>
                HTML;
            })->asHtml()->sortable(),
            Text::make('Type')->sortable(),
            Text::make('Device')->sortable(),
            Text::make('Browser')->sortable(),
            Text::make('Robot')->sortable(),
            DateTime::make('Login At', 'updated_at')->sortable()
        ];
    }


    public function filters(Request $request)
    {
        return [
            //PasswordFilter::make()
        ];
    }

    public function lenses(Request $request): array
    {
        return [
            new MemberHasTheSamePassword()
        ];
    }

    public static function authorizedToCreate(Request $request)
    {
        return false;
    }

    public static function indexQuery(NovaRequest $request, $query): Builder
    {
        return $query->orderBy('updated_at');
    }

    public function authorizedTo(Request $request, $ability)
    {
        return false;
    }

    public static function availableForNavigation(Request $request): bool
    {
        if ($request->user()->isCompany()) {
            return true;
        }

        return false;
    }

}
