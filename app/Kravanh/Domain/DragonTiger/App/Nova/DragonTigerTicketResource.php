<?php

namespace App\Kravanh\Domain\DragonTiger\App\Nova;

use App\Kravanh\Domain\DragonTiger\Actions\DragonTigerGameGetLiveIdsAction;
use App\Kravanh\Domain\DragonTiger\Models\DragonTigerTicket;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class DragonTigerTicketResource extends DragonTigerResourceGroup
{
    public static string $model = DragonTigerTicket::class;

    public static $with = [
        'user:id,name,currency',
        'gameTable:id,label',
        'game'
    ];

    public static $searchable = false;

    // public static $perPageOptions = [10];

    public static $perPageViaRelationship = 100;

    public static function label(): string
    {
        return 'Tickets';
    }


    public static function indexQuery(NovaRequest $request, $query): Builder
    {
        return $query->whereNotIn('dragon_tiger_game_id', (new DragonTigerGameGetLiveIdsAction())());
    }

    public function fields(Request $request): array
    {

        /**
         * @var DragonTigerTicket $this ;
         */
        $ticketFormat = $this->format();

        return [
            ID::make('Id'),
            Text::make('Table', fn() => $ticketFormat->table()),
            Text::make('Game#', fn() => $ticketFormat->gameNumber()),
            Text::make('Member', fn() => $ticketFormat->member()),
            DateTime::make('Date', fn() => $this->created_at),
            Text::make('Bet Amount', fn() => $ticketFormat->betAmountAsHtml())->asHtml(),
            Text::make('Bet', fn() => $ticketFormat->betAsHtml())->asHtml(),
            Text::make('Game Result', fn() => $ticketFormat->gameResult())->asHtml(),
            Text::make('Ticket Result', fn() => $ticketFormat->resultAsHtml())->asHtml(),
            Text::make('Win/Lose', fn() => $ticketFormat->winLoseAsHtml())->asHtml(),
            Text::make('Ip', fn() => $ticketFormat->ipAsHtml())->asHtml(),
        ];
    }

    public function authorizedToView(Request $request): bool
    {
        return false;
    }
}
