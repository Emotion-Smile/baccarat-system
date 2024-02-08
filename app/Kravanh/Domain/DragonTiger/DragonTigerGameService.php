<?php

namespace App\Kravanh\Domain\DragonTiger;

use App\Kravanh\Domain\DragonTiger\Actions\DragonTigerGameCreateManagerAction;
use App\Kravanh\Domain\DragonTiger\Actions\DragonTigerGameGetLastRoundAction;
use App\Kravanh\Domain\DragonTiger\Actions\DragonTigerGameGetLiveGameByTableAction;
use App\Kravanh\Domain\DragonTiger\Actions\DragonTigerGameGetLiveGameIdByTableAction;
use App\Kravanh\Domain\DragonTiger\Actions\DragonTigerGameGetMemberBetStateAction;
use App\Kravanh\Domain\DragonTiger\Actions\DragonTigerGameGetMemberOutstandingTicketsAction;
use App\Kravanh\Domain\DragonTiger\Actions\DragonTigerGameGetMemberTicketsAction;
use App\Kravanh\Domain\DragonTiger\Actions\DragonTigerGameGetSummaryBetAmountAction;
use App\Kravanh\Domain\DragonTiger\Actions\DragonTigerGameGetTodayResultAction;
use App\Kravanh\Domain\DragonTiger\Actions\DragonTigerGameHasLiveGameAction;
use App\Kravanh\Domain\DragonTiger\Actions\DragonTigerGameSubmitResultManagerAction;
use App\Kravanh\Domain\DragonTiger\Dto\DragonTigerGameCreateData;
use App\Kravanh\Domain\DragonTiger\Dto\DragonTigerGameSubmitResultData;
use App\Kravanh\Domain\DragonTiger\Exceptions\DragonTigerGameHasLiveGameException;
use App\Kravanh\Domain\DragonTiger\Exceptions\DragonTigerGameNoLiveGameException;
use App\Kravanh\Domain\DragonTiger\Exceptions\DragonTigerGameSubmitResultBetOpenException;
use App\Kravanh\Domain\DragonTiger\Support\DateFilter;
use App\Kravanh\Domain\Game\Actions\GameGetDragonTigerGameIdAction;
use App\Kravanh\Domain\Game\Actions\GameTableGetAction;
use App\Kravanh\Domain\Game\Actions\GameTableGetByGameIdAction;
use App\Kravanh\Domain\Game\Actions\GameTableGetForTraderAction;
use App\Kravanh\Support\Enums\Currency;
use Illuminate\Support\Collection;

/**
 * Just API wrapper
 */
final class DragonTigerGameService
{
    /**
     * @throws DragonTigerGameHasLiveGameException
     */
    public function create(DragonTigerGameCreateData $data)
    {
        return app(DragonTigerGameCreateManagerAction::class)(data: $data);
    }

    /**
     * @throws DragonTigerGameSubmitResultBetOpenException
     */
    public function submitResult(DragonTigerGameSubmitResultData $data): bool
    {
        return app(DragonTigerGameSubmitResultManagerAction::class)(data: $data);
    }

    /**
     * @throws DragonTigerGameNoLiveGameException
     */
    public function getLiveGame(int $gameTableId)
    {
        return app(DragonTigerGameGetLiveGameByTableAction::class)(gameTableId: $gameTableId);
    }

    public function getMemberOutstandingTickets(int $userId, int $gameTableId): Collection
    {
        return (new DragonTigerGameGetMemberOutstandingTicketsAction(
            userId: $userId,
            gameTableId: $gameTableId)
        )->tickets();
    }

    public function getMemberTickets(int $userId, int $gameTableId, DateFilter $filter): array
    {
        return app(DragonTigerGameGetMemberTicketsAction::class)(
            userId: $userId,
            gameTableId: $gameTableId,
            filterMode: $filter
        )->toReport();
    }

    public function memberBetState(int $userId, Currency|string $currency, int $dragonTigerGameId): array
    {
        return app(DragonTigerGameGetMemberBetStateAction::class)(
            userId: $userId,
            currency: $currency,
            dragonTigerGameId: $dragonTigerGameId
        );
    }

    public function scoreboard(int $gameTableId)
    {
        return app(DragonTigerGameGetTodayResultAction::class)(
            gameTableId: $gameTableId,
            roundNumber: app(DragonTigerGameGetLastRoundAction::class)(gameTableId: $gameTableId)
        );
    }

    public function hasLiveGame(int|array $gameTableIds): bool
    {
        return app(DragonTigerGameHasLiveGameAction::class)(gameTableId: $gameTableIds);
    }

    /**
     * @throws DragonTigerGameNoLiveGameException
     */
    public function liveGameId(int $gameTableId)
    {
        return app(DragonTigerGameGetLiveGameIdByTableAction::class)(gameTableId: $gameTableId);
    }

    public function getTable(int $id)
    {
        return app(GameTableGetAction::class)($id);
    }

    public function getTableForTrader(int $id)
    {
        return app(GameTableGetForTraderAction::class)($id);
    }

    public function getAllTable()
    {
        return app(GameTableGetByGameIdAction::class)(
            gameId: app(GameGetDragonTigerGameIdAction::class)()
        );
    }

    public function getBetSummary(int $dragonTigerGameId)
    {
        return app(DragonTigerGameGetSummaryBetAmountAction::class)(dragonTigerGameId: $dragonTigerGameId);
    }
}
