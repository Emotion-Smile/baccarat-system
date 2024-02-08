<?php

namespace App\Kravanh\Application\Admin\User;

use App\Kravanh\Application\Admin\User\Filters\DateTimeFilter;
use App\Kravanh\Application\Admin\User\Filters\PeriodFilter;
use App\Kravanh\Domain\User\Models\LoginHistory as LoginHistoryModel;
use App\Kravanh\Domain\User\Supports\Enums\UserType;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
// use Titasgailius\SearchRelations\SearchesRelations;

class LoginHistory extends RoleResourceGroup
{
    // use SearchesRelations;

    public static $model = LoginHistoryModel::class;

    // public static $with = ['user:id,name'];

    public static $search = [
        'users.name',
        'login_histories.ip',
        'login_histories.user_agent'
    ];

    // public static $searchRelations = [
    //     'user' => ['name'],
    // ];
    
    // public static $searchRelationsGlobally = false;

    public function fields(Request $request): array
    {
        return [
            // Text::make('Name', function() {
            //     return $this->user?->name;
            // }),

            Text::make('Name', 'name'),

            Text::make('Ip Address', 'ip')
                ->resolveUsing(function () {
                    return '<a href="https://ip-api.com/#' . $this->ip . '" target="_blank">' . $this->ip . '</a>';
                })->asHtml()->sortable(),

            DateTime::make('Login At', 'login_at'),

            Text::make('User Agent', 'user_agent')
        ];
    }

    public static function indexQuery(NovaRequest $request, $query)
    {
        return $query->select([
            'users.name as name',
            'login_histories.ip as ip',
            'login_histories.login_at as login_at',
            'login_histories.user_agent as user_agent'
        ])
            ->join('users', 'users.id', '=', 'login_histories.user_id')
            ->when(!in_array(user()->type, [UserType::COMPANY, UserType::DEVELOPER]), function($query) {
                $query->where('users.' . user()->type, user()->id);
            }); 
    }

    public function filters(Request $request)
    {
        return [
            new PeriodFilter('login_at'),
            new DateTimeFilter('From', 'login_at', '>='),
            new DateTimeFilter('To', 'login_at', '<=')
        ];
    }
}
