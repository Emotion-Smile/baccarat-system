<?php

namespace App\Kravanh\Application\Admin\User;

use App\Kravanh\Application\Admin\Resource;
use App\Kravanh\Domain\User\Supports\Enums\UserType;
use Illuminate\Http\Request;
use Laravel\Nova\Http\Requests\NovaRequest;

abstract class UserResourceGroup extends Resource
{
    public static $group = 'User';

    public static function indexQuery(NovaRequest $request, $query)
    {
        $user = $request->user();

        if (!in_array($user->type, [UserType::COMPANY, UserType::DEVELOPER])) {
            $query->where($user->type, $user->id);
        }

    }

    public static function availableForNavigation(Request $request): bool
    {
        if ($request->user()->isRoot()) return true;

        $permission = makeResourceName(static::uriKey()) . ':' . 'menu';

        return $request->user()->hasPermission($permission);
    }
}
