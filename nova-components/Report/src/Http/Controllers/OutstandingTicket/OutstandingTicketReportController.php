<?php

namespace KravanhEco\Report\Http\Controllers\OutstandingTicket;

use App\Kravanh\Domain\Match\Models\BetRecord;
use App\Kravanh\Domain\Match\Supports\Enums\BetOn;
use App\Kravanh\Domain\User\Jobs\TraceUserJob;
use App\Kravanh\Support\Enums\Currency;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;


class OutstandingTicketReportController
{
    public function __invoke(): JsonResponse
    {
        $user = castingUser(user());

        return response()->json([
            'outstandingTickets' => $this->outstandingTickets($user)
        ]);
    }

    protected function outstandingTickets(object $user): LengthAwarePaginator
    {
        $outstandingTickets = BetRecord::query()
            ->with(['user:id,current_team_id,name,type,agent', 'group:id,name'])
            ->onlyLiveMatch()
            ->exceptSomeUserType($user)
            ->orderByDesc('id')
            ->paginate(10);

        $uniqueTicket = $outstandingTickets;

        $uniqueTicket->unique('user_id')
            ->each(fn($ticket) => TraceUserJob::dispatchIf(
                in_array($ticket->user->name, $this->getTracingUser()),
                $ticket->user->name,
                "$user->type: Outstanding ticket",
                $ticket->match_id,
                $ticket->user_id
            ));

        return $outstandingTickets->through($this->mapOutstandingTicket())
            ?? new LengthAwarePaginator([], 0, 10);
    }

    private function getTracingUser()
    {
        $traceUsers = appGetSetting('trace_users');

        if (is_null($traceUsers)) {
            return [];
        }

        return explode(',', $traceUsers);

    }

    protected function mapOutstandingTicket(): \Closure
    {
        return function ($bet) {
            $currency = Currency::fromKey($bet->currency);

            $amount = amountDisplay(
                amount: $bet->amount,
                payoutRate: $bet->payout_rate,
                currency: $currency,
            );

            return [
                'id' => $bet->id,
                'ip' => $bet->ip,
                'amount' => $amount,
                'member' => $bet->user->name,
                'fightNumber' => $bet->fight_number,
                'betTime' => $bet->bet_time->format('H:i:s'),
                'betOn' => BetOn::fromValue($bet->bet_on)->description,
                'table' => $bet->group->name
            ];
        };
    }
}
