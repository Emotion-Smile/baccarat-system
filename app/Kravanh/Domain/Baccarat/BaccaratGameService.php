<?php

namespace App\Kravanh\Domain\Baccarat;

use App\Kravanh\Domain\Baccarat\Actions\BaccaratGameCreateManagerAction;
use App\Kravanh\Domain\Baccarat\Actions\BaccaratGameGetLastRoundAction;
use App\Kravanh\Domain\Baccarat\Actions\BaccaratGameGetLiveGameByTableAction;
use App\Kravanh\Domain\Baccarat\Actions\BaccaratGameGetLiveGameIdByTableAction;
use App\Kravanh\Domain\Baccarat\Actions\BaccaratGameGetMemberBetStateAction;
use App\Kravanh\Domain\Baccarat\Actions\BaccaratGameGetMemberOutstandingTicketsAction;
use App\Kravanh\Domain\Baccarat\Actions\BaccaratGameGetMemberTicketsAction;
use App\Kravanh\Domain\Baccarat\Actions\BaccaratGameGetSummaryBetAmountAction;
use App\Kravanh\Domain\Baccarat\Actions\BaccaratGameGetTodayResultAction;
use App\Kravanh\Domain\Baccarat\Actions\BaccaratGameHasLiveGameAction;
use App\Kravanh\Domain\Baccarat\Actions\BaccaratGameSubmitResultManagerAction;
use App\Kravanh\Domain\Baccarat\Dto\BaccaratGameCreateData;
use App\Kravanh\Domain\Baccarat\Dto\BaccaratGameSubmitResultData;
use App\Kravanh\Domain\Baccarat\Exceptions\BaccaratGameHasLiveGameException;
use App\Kravanh\Domain\Baccarat\Exceptions\BaccaratGameNoLiveGameException;
use App\Kravanh\Domain\Baccarat\Exceptions\BaccaratGameSubmitResultBetOpenException;
use App\Kravanh\Domain\Baccarat\Support\DateFilter;
use App\Kravanh\Domain\Game\Actions\GameGetBaccaratGameIdAction;
use App\Kravanh\Domain\Game\Actions\GameTableGetAction;
use App\Kravanh\Domain\Game\Actions\GameTableGetByGameIdAction;
use App\Kravanh\Domain\Game\Actions\GameTableGetForTraderAction;
use App\Kravanh\Support\Enums\Currency;
use Illuminate\Support\Collection;

/**
 * Just API wrapper
 */
final class BaccaratGameService
{
    /**
     * @throws BaccaratGameHasLiveGameException
     */
    public function create(BaccaratGameCreateData $data)
    {
        return app(BaccaratGameCreateManagerAction::class)(data: $data);
    }

    /**
     * @throws BaccaratGameSubmitResultBetOpenException
     */
    public function submitResult(BaccaratGameSubmitResultData $data): bool
    {
        return app(BaccaratGameSubmitResultManagerAction::class)(data: $data);
    }

    /**
     * @throws BaccaratGameNoLiveGameException
     */
    public function getLiveGame(int $gameTableId)
    {
        return app(BaccaratGameGetLiveGameByTableAction::class)(gameTableId: $gameTableId);
    }

    public function getMemberOutstandingTickets(int $userId, int $gameTableId): Collection
    {
        return (new BaccaratGameGetMemberOutstandingTicketsAction(
            userId: $userId,
            gameTableId: $gameTableId)
        )->tickets();
    }

    public function getMemberTickets(int $userId, int $gameTableId, DateFilter $filter): array
    {
        return app(BaccaratGameGetMemberTicketsAction::class)(
            userId: $userId,
            gameTableId: $gameTableId,
            filterMode: $filter
        )->toReport();
    }

    public function memberBetState(int $userId, Currency|string $currency, int $baccaratGameId): array
    {
        return app(BaccaratGameGetMemberBetStateAction::class)(
            userId: $userId,
            currency: $currency,
            baccaratGameId: $baccaratGameId
        );
    }

    public function scoreboard(int $gameTableId)
    {
        return app(BaccaratGameGetTodayResultAction::class)(
            gameTableId: $gameTableId,
            roundNumber: app(BaccaratGameGetLastRoundAction::class)(gameTableId: $gameTableId)
        );
    }

    public function hasLiveGame(int|array $gameTableIds): bool
    {
        return app(BaccaratGameHasLiveGameAction::class)(gameTableId: $gameTableIds);
    }

    /**
     * @throws BaccaratGameNoLiveGameException
     */
    public function liveGameId(int $gameTableId)
    {
        return app(BaccaratGameGetLiveGameIdByTableAction::class)(gameTableId: $gameTableId);
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
            gameId: app(GameGetBaccaratGameIdAction::class)()
        );
    }

    public function getBetSummary(int $baccaratGameId)
    {
        return app(BaccaratGameGetSummaryBetAmountAction::class)(baccaratGameId: $baccaratGameId);
    }
}
