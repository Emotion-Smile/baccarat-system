<?php

namespace App\Kravanh\Application\Admin\User;

use App\Kravanh\Application\Admin\User\Actions\BlockVideoStreamingAction;
use App\Kravanh\Application\Admin\User\Actions\ForceUserLogoutAction;
use App\Kravanh\Application\Admin\User\Actions\UnblockVideoStreamingAction;
use App\Kravanh\Domain\User\Supports\Enums\UserType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class MemberOnline extends RoleResourceGroup
{
    public static $model = \App\Models\User::class;

    public static $title = 'name';

    public static $search = [
        'id',
        'name',
        'ip'
    ];

    public static function label(): string
    {
        return "Member Online";
    }

    public function fields(Request $request): array
    {
        return [
            ID::make(__('ID'), 'id'),
            Text::make('Username', 'name')
                ->sortable(),

            Text::make('Ip Address', 'ip')
                ->resolveUsing(function () {
                    return '<a href="https://ip-api.com/#' . $this->ip . '" target="_blank">' . $this->ip . '</a>';
                })->asHtml()->sortable(),

            Text::make('Login At', 'last_login_at')
                ->resolveUsing(function () {
                    return $this->last_login_at?->diffForHumans();
                })->sortable(),

            Text::make('Last Activity', function () {
                return $this->getLastActivity(true);
            }),

            Text::make('Last Bet At', function () {
                return $this->getLastBetAt(formatTime: true)['at'];
            }),

            Text::make('Fight Number', function () {
                return $this->getLastBetAt(formatTime: true)['fight_number'];
            }),

//            Code::make('Ip Info', function () {
//                if (!$this->ip) {
//                    return json_encode([]);
//                }
//                $position = Location::get($this->ip);
//                return $position ? json_encode($position->toArray()) : json_encode([]);
//            })->onlyOnDetail()->json()
        ];
    }

    public static function indexQuery(NovaRequest $request, $query): Builder
    {
        return $query->whereOnline(true)
            ->whereType(UserType::MEMBER)
            ->when(!in_array(user()->type, exceptUserType()), 
                function(Builder $query) {
                    $query->where(user()->type, user()->id);
                }
            );
    }


    public static function authorizable()
    {
        return false;
    }

    public static function availableForNavigation(Request $request): bool
    {
        if ($request->user()->isRoot()) {
            return true;
        }

        return $request->user()->type->isNot(UserType::SUB_ACCOUNT);
    }

    public static function authorizedToCreate(Request $request)
    {
        return false;
    }

    public function authorizedToDelete(Request $request)
    {
        return false;
    }

    public function authorizedToUpdate(Request $request): bool
    {
        return false;
    }

    public function actions(Request $request): array
    {
        return [
            ForceUserLogoutAction::make()
                ->canSee(fn() => true)
                ->canRun(fn() => true),
            BlockVideoStreamingAction::make()
                ->canSee(fn() => true)
                ->canRun(fn() => true),
            UnblockVideoStreamingAction::make()
                ->canSee(fn() => true)
                ->canRun(fn() => true),
        ];
    }
}
