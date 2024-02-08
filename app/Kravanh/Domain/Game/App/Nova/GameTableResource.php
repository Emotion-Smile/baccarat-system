<?php

namespace App\Kravanh\Domain\Game\App\Nova;

use App\Kravanh\Domain\Game\Models\GameTable;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Text;

class GameTableResource extends GameResourceGroup
{

    public static $title = 'label';

    public static $searchable = false;

    public static function label(): string
    {
        return 'Game Table';
    }

    public static string $model = GameTable::class;


    public function fields(Request $request): array
    {
        return [
            BelongsTo::make(
                'Game',
                'game',
                GameResource::class
            )->hideWhenUpdating(),
            Text::make('Label')->rules(['required']),
            Text::make('Stream Url')->rules(['required']),
            Boolean::make('Active')
        ];
    }

    public function authorizedToDelete(Request $request): bool
    {
        return false;
    }

    public static function authorizedToCreate(Request $request): bool
    {
        return $request->user()->isCompany();
    }

    public function authorizedToUpdate(Request $request): bool
    {
        return $request->user()->isCompany();
    }
}
