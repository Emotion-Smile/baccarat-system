<?php

namespace App\Kravanh\Application\Trader\Controllers;

use App\Kravanh\Domain\Match\Models\Matches;
use App\Kravanh\Domain\Match\Supports\MemberTypeHelper;
use App\Kravanh\Domain\User\Models\MemberType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;
use Inertia\Response;

class OpenBetController
{
    public function __invoke(Request $request): Response
    {

        $user = $request->user();
        $envId = $user->environment_id;
        $groupId = $user->group_id;

        $match = Matches::liveWithDefault($user);
        $matchId = $match['id'] ?? 0;

        return Inertia::render('OpenBet/Index', [
            'payoutIncreaseAndDecreaseValue' => fn() => $this->payoutIncreaseAndDecreaseValue(),
            'matchInfo' => fn() => $match,
            'group' => fn() => $user->group,
            'resultCount' => fn() => Matches::todayResultCount($groupId),
            'benefit' => fn() => Matches::estimateBenefit($envId, $groupId),
            'todayWinLoss' => fn() => 0, //fn() => priceFormat(Matches::todayWinLoss($envId, $groupId), ''),
            'resultSymbols' => fn() => Matches::todayResultAsSymbol($groupId),
            'isMeronBetEnable' => fn() => Cache::get("match:{$matchId}:disable:1", true),
            'isWalaBetEnable' => fn() => Cache::get("match:{$matchId}:disable:2", true),
            'memberTypes' => fn() => $this->getMemberType($matchId),
            'memberTypeMapIdToName' => fn() => $this->getMemberTypeMapIdToName()
        ]);
    }

    public function getMemberType($matchId): array
    {

        return MemberType::select(['id', 'name'])
            ->get()
            ->filter(fn($item) => !in_array($item->name, ['Normal', 'VIP']))
            ->keyBy('name')
            ->map(fn($item) => [
                'id' => $item->id,
                'name' => $item->name,
                'meron' => 0,
                'wala' => 0,
                'status' => MemberTypeHelper::from($matchId, $item->id)->getBetStatus()['status']
            ])
            ->toArray();
    }

    protected function getMemberTypeMapIdToName(): array
    {
        return MemberType::select(['id', 'name'])
            ->get()
            ->keyBy('id')
            ->map(fn($header) => $header->name)
            ->toArray();
    }

    public function payoutIncreaseAndDecreaseValue(): array
    {
        return [
            [
                'value' => '-1',
                'action' => 'minus',
                'key' => 1
            ],
            [
                'value' => '-2',
                'action' => 'minus',
                'key' => 2
            ], [
                'value' => '-3',
                'action' => 'minus',
                'key' => 3
            ],
            [
                'value' => '-4',
                'action' => 'minus',
                'key' => 4
            ],
            [
                'value' => '-5',
                'action' => 'minus',
                'key' => 5
            ],
            [
                'value' => '-6',
                'action' => 'minus',
                'key' => 6
            ],
            // plus
            [
                'value' => '1',
                'action' => 'plus',
                'key' => 1
            ],
            [
                'value' => '2',
                'action' => 'plus',
                'key' => 2
            ], [
                'value' => '3',
                'action' => 'plus',
                'key' => 3
            ],
            [
                'value' => '4',
                'action' => 'plus',
                'key' => 4
            ],
            [
                'value' => '5',
                'action' => 'plus',
                'key' => 5
            ],
            [
                'value' => '6',
                'action' => 'plus',
                'key' => 6
            ]
        ];
    }
}
