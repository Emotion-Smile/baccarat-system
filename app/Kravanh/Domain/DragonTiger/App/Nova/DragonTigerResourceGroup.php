<?php

namespace App\Kravanh\Domain\DragonTiger\App\Nova;

use App\Kravanh\Application\Admin\Resource;
use App\Kravanh\Domain\DragonTiger\DragonTigerSetting;
use Illuminate\Http\Request;

abstract class DragonTigerResourceGroup extends Resource
{

    public static $group = 'Dragon & Tiger';

    public static $globallySearchable = false;


    public static function isOutStandingTicket(): bool
    {

        return static::uriKey() === DragonTigerOutstandingTicketResource::uriKey();
    }

    public static function authorizedToViewAny(Request $request)
    {
        if (static::isOutStandingTicket()) {
            return DragonTigerSetting::allow(allow: $request->user()->isCompany());
        }

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
