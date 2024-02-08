<?php

namespace App\Kravanh\Domain\Game\App\Nova;

use App\Kravanh\Application\Admin\Resource;
use Illuminate\Http\Request;

abstract class GameResourceGroup extends Resource
{

    public static $group = 'Game';

    public static $globallySearchable = false;


    public static function authorizedToViewAny(Request $request)
    {
        return $request->user()->isCompany();
    }

    public function authorizedToView(Request $request): bool
    {
        return $request->user()->isCompany();
    }

    public static function authorizedToCreate(Request $request): bool
    {
        return false;
    }

    public function authorizedToUpdate(Request $request): bool
    {
        return false;
    }

    public function authorizedToDelete(Request $request): bool
    {
        return false;
    }

    public static function createButtonLabel(): string
    {
        return 'Create';
    }

    public static function updateButtonLabel(): string
    {
        return 'Update';
    }


}
