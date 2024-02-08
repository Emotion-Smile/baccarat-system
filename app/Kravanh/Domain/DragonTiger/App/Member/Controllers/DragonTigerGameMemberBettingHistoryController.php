<?php

namespace App\Kravanh\Domain\DragonTiger\App\Member\Controllers;

use App\Kravanh\Domain\DragonTiger\DragonTigerGameService;
use App\Kravanh\Domain\DragonTiger\Support\DateFilter;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

final class DragonTigerGameMemberBettingHistoryController
{
    public function __construct(public DragonTigerGameService $dragonTigerGameService)
    {
    }

    public function __invoke(Request $request): Response
    {
        $member = $request->user();

        return Inertia::render(
            component: 'DragonTiger/Member/BettingHistory',
            props: [
                'tickets' => $this->dragonTigerGameService->getMemberTickets(
                    userId: $member->id,
                    gameTableId: $member->getGameTableId(),
                    filter: DateFilter::fromStr($request->get('date', 'today'))
                )
            ]
        );
    }
}
