<?php

namespace App\Kravanh\Domain\DragonTiger\App\Nova;

use App\Kravanh\Domain\DragonTiger\Actions\DragonTigerGameGetLiveIdsAction;
use App\Kravanh\Domain\DragonTiger\Models\DragonTigerTicket;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class DragonTigerOutstandingTicketResource extends DragonTigerResourceGroup
{
    public static string $model = DragonTigerTicket::class;

    public static $with = [
        'user:id,name,currency',
        'gameTable:id,label',
        'game'
    ];

    public static $displayInNavigation = false;

    public static $searchable = false;

    public static $perPageOptions = [10];

    public static $perPageViaRelationship = 100;

    public static function label(): string
    {
        return 'D&T Outstanding Tickets';
    }


    public static function indexQuery(NovaRequest $request, $query): Builder
    {

//        if ($request->viaResource() === DragonTigerGameResource::class) {
//            $request->merge(['perPage' => "100"]);
//        }

        $user = $request->user();

        if ($user->isCompany() || $user->isCompanySubAccount()) {
            return $query->whereIn('dragon_tiger_game_id', (new DragonTigerGameGetLiveIdsAction())());
        }

        return $query
            ->whereIn('dragon_tiger_game_id', (new DragonTigerGameGetLiveIdsAction())())
            ->where($user->getEnsureType(), $user->getEnsureId());
    }


    public function fields(Request $request): array
    {
        /**
         * @var DragonTigerTicket $this ;
         */
        $ticketFormat = $this->format();

        return [
            Text::make('Table', fn() => $ticketFormat->table()),
            Text::make('Time', fn() => $this->created_at->format(config('kravanh.time_format'))),
            Text::make('Ip', fn() => $ticketFormat->ipAsHtml())->asHtml(),
            Text::make('Game#', fn() => $ticketFormat->gameNumber()),
            Text::make('Member', fn() => $ticketFormat->member()),
            Text::make('Bet', fn() => $ticketFormat->betAsHtml())->asHtml(),
            Text::make('Amount', fn() => $ticketFormat->outstandingAmountAsHtml())->asHtml(),
        ];
    }

    public function authorizedToView(Request $request): bool
    {
        return false;
    }
}
