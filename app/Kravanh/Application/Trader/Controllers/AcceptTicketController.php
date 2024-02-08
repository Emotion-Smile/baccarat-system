<?php

namespace App\Kravanh\Application\Trader\Controllers;

use App\Kravanh\Domain\Match\Models\Matches;
use App\Kravanh\Domain\Match\Supports\Enums\BetOn;
use App\Kravanh\Domain\User\Models\MemberType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;
use Inertia\Response;

class AcceptTicketController
{
    public function __invoke(Request $request): Response
    {
        $envId = $request->user()->environment_id;
        $groupId = $request->user()->group_id;

        return Inertia::render('AcceptTicket/Index', [
            'matchInfo' => fn() => Matches::liveWithDefault($request->user()),
            'benefit' => fn() => Matches::estimateBenefit($envId, $groupId),
            'todayWinLoss' => fn() => 0, //priceFormat(Matches::todayWinLoss($envId, $groupId), ''),
            'tickets' => fn() => $this->tickets(),
            'group' => fn() => $request->user()->group,
            'ticketHeader' => fn() => $this->ticketHeaderMap()
        ]);
    }

    protected function tickets(): array
    {
        $match = Matches::live(user());

        if (!$match) {

            $ticketHeaders = MemberType::select(['id', 'name'])
                ->get()
                ->map(fn($memberType) => [$memberType->name => []])
                ->collapse()
                ->toArray();


            return ['General' => []] + $ticketHeaders;
//            return [
//                'AUTO_ACCEPT' => [],
//                'CHECK' => [],
//                'SUSPECT' => [],
//            ];
        }

        $betRecords = $match->betRecords()
            ->with('user:id,name')
            ->orderByDesc('id')
            ->get([
                'id',
                'member_type_id',
                'fight_number',
                'user_id',
                'bet_time',
                'bet_on',
                'ip',
                'status',
                'type',
                'amount',
                'payout_rate'
            ])->map(function ($bet) {
                return [
                    'id' => $bet->id,
                    'fight_number' => $bet->fight_number,
                    'member_type_id' => $bet->member_type_id,
                    'ip' => $bet->ip,
                    'country_code' => Cache::get($bet->ip, ''),
                    'member' => $bet->user->name,
                    'bet_time' => $bet->bet_time->format('H:i:s'),
                    'bet_on' => strtolower(BetOn::fromValue($bet->bet_on)->description),
                    'amount' => $bet->amountDisplay(),
                    'type' => $bet->type->key
                ];
            })
            ->groupBy('member_type_id')
            ->toArray();

        $ticketHeaders = MemberType::select(['id', 'name'])
            ->get()
            ->keyBy('name')
            ->map(fn($memberType) => $memberType->id)
            ->toArray();

        $tickets = [];

        $tickets['General'] = $betRecords[""] ?? [];

        foreach ($ticketHeaders as $key => $value) {
            $tickets[$key] = $betRecords[$value] ?? [];
        }

        return $tickets;
//        return [
//            'AUTO_ACCEPT' => $betRecords[""] ?? [],
//            'CHECK' => $betRecords[1] ?? [],
//            'SUSPECT' => $betRecords[2] ?? [],
//        ];
//        return [
//            'AUTO_ACCEPT' => $betRecords['AUTO_ACCEPT'] ?? [],
//            'CHECK' => $betRecords['CHECK'] ?? [],
//            'SUSPECT' => $betRecords['SUSPECT'] ?? [],
//        ];
    }

    protected function ticketHeaderMap(): array
    {
        $ticketHeaders = MemberType::select(['id', 'name'])
            ->get()
            ->keyBy('id')
            ->map(fn($header) => $header->name)
            ->toArray();
        return ['general' => 'General'] + $ticketHeaders;

    }
}
