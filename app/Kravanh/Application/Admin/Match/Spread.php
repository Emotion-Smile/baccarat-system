<?php

namespace App\Kravanh\Application\Admin\Match;

use App\Kravanh\Application\Admin\User\Agent;
use App\Kravanh\Application\Admin\User\Member;
use App\Kravanh\Application\Admin\User\RoleResourceGroup;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;

class Spread extends RoleResourceGroup
{
    public static $globallySearchable = false;


    public static $model = \App\Kravanh\Domain\Match\Models\Spread::class;

    public static function label(): string
    {
        return 'Group Spread';
    }

    public function title(): string
    {
        return "{$this->name} ({$this->payout_deduction})";
    }


    public function fields(Request $request): array
    {
        return [
            ID::make('Id'),
            Text::make('name')->rules(['required']),
            Number::make('Spread', 'payout_deduction')
                ->rules(['required', 'numeric'])->help("Ex. spread = 10 -> payout: 0.90 - 0.10 = 0.80"),
            Number::make("User", function () {
                return $this->users->count();
            }),
            Boolean::make('Active')->default(true),
            HasMany::make('Members', 'members', Member::class),
            HasMany::make('Agents', 'agents', Agent::class)
        ];
    }

    public function authorizedToDelete(Request $request): bool
    {
        return false;
    }

}
