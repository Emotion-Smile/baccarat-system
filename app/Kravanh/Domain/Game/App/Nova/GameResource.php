<?php

namespace App\Kravanh\Domain\Game\App\Nova;

use App\Kravanh\Domain\Game\Models\Game;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;

class GameResource extends GameResourceGroup
{

    public static $title = 'label';

    public static $searchable = false;


    public static function label(): string
    {
        return 'Game';
    }

    public static string $model = Game::class;


    public function fields(Request $request): array
    {
        return [
            Text::make('Label')->rules(['required']),
            Text::make('name')->rules(['required']),
            Text::make('Type')->rules(['required'])
        ];
    }

    public function authorizedTo(Request $request, $ability): bool
    {
        return false;
    }

    public static function authorizedToCreate(Request $request): bool
    {
        return false;
    }

}
