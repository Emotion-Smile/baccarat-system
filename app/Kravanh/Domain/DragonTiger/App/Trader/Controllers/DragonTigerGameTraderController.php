<?php

namespace App\Kravanh\Domain\DragonTiger\App\Trader\Controllers;

use App\Kravanh\Domain\DragonTiger\DragonTigerGameService;
use App\Kravanh\Domain\DragonTiger\Dto\DragonTigerGameStateData;
use App\Kravanh\Domain\DragonTiger\Exceptions\DragonTigerGameNoLiveGameException;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

final class DragonTigerGameTraderController
{
    /**
     * @var User
     */
    public mixed $trader;

    public function __construct(public DragonTigerGameService $dragonTigerGameService)
    {
    }

    public function __invoke(Request $request): RedirectResponse|Response
    {
        $this->trader = $request->user();

        if ($this->trader->isDragonTigerDealer()) {
            return redirect()->route('dragon-tiger.dealer');
        }

        $gameResultToday = $this->dragonTigerGameService->scoreboard(
            gameTableId: $this->trader->getGameTableId()
        );

        return Inertia::render(
            component: 'DragonTiger/Trader/Index',
            props: [
                'table' => fn () => $this->dragonTigerGameService->getTableForTrader($this->trader->getGameTableId()),
                'gameState' => fn () => $this->gameState(),
                'scoreboard' => fn () => $gameResultToday->toScoreboard(),
                'scoreboardCount' => fn () => $gameResultToday->toScoreboardCount(),
                'betSummary' => fn () => $this->getBetSummary(),
            ]
        );
    }

    private function gameState(): DragonTigerGameStateData
    {
        try {
            return DragonTigerGameStateData::from(
                game: $this->dragonTigerGameService->getLiveGame($this->trader->getGameTableId())
            );
        } catch (DragonTigerGameNoLiveGameException) {
            return DragonTigerGameStateData::default(
                gameTableId: $this->trader->getGameTableId()
            );
        }
    }

    public function getBetSummary()
    {
        try {

            return $this->dragonTigerGameService->getBetSummary(
                dragonTigerGameId: $this->dragonTigerGameService->liveGameId($this->trader->getGameTableId())
            );

        } catch (DragonTigerGameNoLiveGameException) {
            return [];
        }
    }
}
