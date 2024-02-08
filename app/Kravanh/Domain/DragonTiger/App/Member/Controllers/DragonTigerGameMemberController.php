<?php

namespace App\Kravanh\Domain\DragonTiger\App\Member\Controllers;

use App\Kravanh\Domain\DragonTiger\Actions\DragonTigerGameAutoSetDefaultBetConditionAction;
use App\Kravanh\Domain\DragonTiger\DragonTigerGameService;
use App\Kravanh\Domain\DragonTiger\Dto\DragonTigerGameStateData;
use App\Kravanh\Domain\DragonTiger\Exceptions\DragonTigerGameNoLiveGameException;
use App\Kravanh\Domain\DragonTiger\Support\DateFilter;
use App\Kravanh\Domain\Game\Actions\GameDragonTigerGetTableConditionAction;
use App\Kravanh\Domain\User\Models\Member;
use App\Kravanh\Support\BetHelper;
use App\Kravanh\Support\Enums\Currency;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Inertia\Inertia;
use Inertia\Response;

final class DragonTigerGameMemberController
{
    public Member $member;

    public function __construct(public DragonTigerGameService $dragonTigerGameService)
    {
    }

    public function __invoke(Request $request): Response
    {

        $this->member = $request->user();

        app(DragonTigerGameAutoSetDefaultBetConditionAction::class)($this->member);

        $gameResultToday = $this->dragonTigerGameService->scoreboard(
            gameTableId: $this->member->getGameTableId()
        );

        return Inertia::render(
            component: 'DragonTiger/Member/DragonTiger',
            props: [
                'allTable' => $this->getTablesMarkingActive(currentActiveTableId: $this->member->getGameTableId()),
                'table' => fn () => $this->dragonTigerGameService->getTable($this->member->getGameTableId()),
                'gameState' => fn () => $this->gameState(),
                'chips' => fn () => $this->chips(),
                'outstandingTickets' => fn () => $this->outstandingTickets(),
                'todayTickets' => Inertia::lazy(fn () => $this->todayTickets()),
                'scoreboard' => fn () => $gameResultToday->toScoreboard(),
                'scoreboardCount' => fn () => $gameResultToday->toScoreboardCount(),
                'memberBetState' => fn () => $this->memberBetState(),
                'betLimit' => $this->betLimit(),
            ]
        );

    }

    private function gameState(): DragonTigerGameStateData
    {
        try {
            return DragonTigerGameStateData::from(
                game: $this->dragonTigerGameService->getLiveGame($this->member->getGameTableId())
            );
        } catch (DragonTigerGameNoLiveGameException) {
            return DragonTigerGameStateData::default(
                gameTableId: $this->member->getGameTableId()
            );
        }
    }

    private function chips(): array
    {
        $chips = BetHelper::betValue(
            currency: $this->member->currency
        );

        if ($this->member->currency->is(Currency::KHR)) {
            $chips = collect($chips)->filter(fn ($item) => ! in_array($item['key'], [10000, 50000]))->values()->all();
        }

        return $chips;
    }

    private function memberBetState(): array
    {
        try {
            return $this->dragonTigerGameService->memberBetState(
                userId: $this->member->id,
                currency: $this->member->currency,
                dragonTigerGameId: $this->dragonTigerGameService->liveGameId(
                    gameTableId: $this->member->getGameTableId()
                )
            );

        } catch (DragonTigerGameNoLiveGameException) {
            return [];
        }
    }

    private function outstandingTickets(): Collection
    {
        return $this->dragonTigerGameService->getMemberOutstandingTickets(
            userId: $this->member->id,
            gameTableId: $this->member->getGameTableId()
        );
    }

    private function betLimit(): array
    {
        $condition = app(GameDragonTigerGetTableConditionAction::class)(
            userId: $this->member->id,
            tableId: $this->member->getGameTableId()
        );

        $currency = $this->member->currency->symbol();

        return [
            'dragon_tiger' => $this->makeMinMax(
                min: $condition->getDragonTigerMinBetPerTicket(),
                max: $condition->getDragonTigerMaxBetPerTicket(),
                prefix: $currency
            ),
            'tie' => $this->makeMinMax(
                min: $condition->getTieMinBetPerTicket(),
                max: $condition->getTieMaxBetPerTicket(),
                prefix: $currency
            ),
            'red_black' => $this->makeMinMax(
                min: $condition->getRedBlackMinBetPerTicket(),
                max: $condition->getRedBlackMaxBetPerTicket(),
                prefix: $currency
            ),
        ];
    }

    private function makeMinMax(int $min, int $max, string $prefix): string
    {
        return __('frontend.min').": $prefix".number_format($min).', '.__('frontend.max').": $prefix".number_format($max);
    }

    private function todayTickets(): array
    {
        return $this->dragonTigerGameService->getMemberTickets(
            userId: $this->member->id,
            gameTableId: $this->member->getGameTableId(),
            filter: DateFilter::Today
        );
    }

    private function getTablesMarkingActive(int $currentActiveTableId): Collection
    {
        return $this
            ->dragonTigerGameService->getAllTable()
            ->map(function ($table) use ($currentActiveTableId) {
                $table['active'] = $table['id'] === $currentActiveTableId;

                return $table;
            })->values();
    }
}
