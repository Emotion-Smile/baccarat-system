<?php

namespace App\Kravanh\Domain\DragonTiger\App\Nova;

use App\Kravanh\Application\Admin\User\Trader;
use App\Kravanh\Domain\DragonTiger\Models\DragonTigerGame;
use App\Kravanh\Domain\Game\App\Nova\GameTableResource;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;

class DragonTigerGameResource extends DragonTigerResourceGroup
{
    public static string $model = DragonTigerGame::class;

    public static $searchable = false;

    public static function label(): string
    {
        return 'Game';
    }

    public function fields(Request $request): array
    {
        //https://fontawesomeicons.com/svg/icons/diamond-thin
        return [
            ID::make('id'),
            BelongsTo::make('Trader', 'user', Trader::class),
            BelongsTo::make('Table', 'gameTable', GameTableResource::class),
            BelongsTo::make('Result Submitted', 'submittedResult', Trader::class)->onlyOnDetail(),
            Text::make('Game#')->resolveUsing(fn() => $this->gameNumber()),
            Text::make('Dragon')
                ->resolveUsing(fn() => $this->format()->dragonResultAsHtml())
                ->asHtml(),
            Text::make('Tiger')
                ->resolveUsing(fn() => $this->format()->tigerResultAsHtml())
                ->asHtml(),
            Text::make('Winner'),
            DateTime::make('Started At', 'started_at'),
//            DateTime::make('Closed At', 'closed_bet_at'),
            DateTime::make('Result Submitted At', 'result_submitted_at'),
            HasMany::make('Tickets', 'tickets', DragonTigerTicketResource::class)
        ];
    }
}
