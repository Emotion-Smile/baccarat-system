<?php

namespace App\Kravanh\Application\Admin\Match;

use App\Kravanh\Domain\Match\Models\DuplicatePayout as DuplicatePayoutModel;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;

class DuplicatePayout extends MatchResourceGroup
{
    public static $globallySearchable = false;

    public static $model = DuplicatePayoutModel::class;

    public static function availableForNavigation(Request $request)
    {
        return false;
//        return $request->user()->isCompany();
    }

    public function fields(Request $request): array
    {
        return [
            ID::make('Id'),
            Text::make('group'),
            Text::make('user'),
            Text::make('amount'),
            Number::make('Tx Count'),
            Text::make('Withdraw Amount'),
            Boolean::make('Already Withdraw')
        ];
    }

}
