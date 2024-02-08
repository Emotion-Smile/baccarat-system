<?php

namespace App\Kravanh\Application\Member\Controllers;

use App\Kravanh\Domain\Match\Models\BetRecord;
use App\Kravanh\Support\Enums\Currency;
use Inertia\Inertia;
use Inertia\Response;

class TicketPrintController
{
    public function __invoke(int $id): Response
    {
        $betRecord = BetRecord::findOrFail($id);
        abort_if($betRecord->user_id !== user()->id, 404);

        $ticket = $this->toTicket($betRecord);

        return Inertia::render('Member/Print', [
            'ticket' => $ticket
        ]);
    }

    protected function toTicket(BetRecord $bet): array
    {
        $currency = Currency::fromKey($bet->currency);

        $betAmount = currencyFormatFromKHR($bet->amount, $currency);
        $payoutEst = currencyFormatFromKHR($bet->payout, $currency);

        return [
            'table' => $bet->group->name,
            'fight_number' => $bet->fight_number . '-' . $bet->id,
            'time' => $bet->bet_time->format('d-m-Y ' . config('kravanh.time_format')),
            'bet_on' => $bet->getBetOnLabel(),
            'amount' => $betAmount,
            'user' => $bet->user->name,
            'currency' => $bet->currency,
            'payout_rate' => $bet->payout_rate,
            'payout_est' => $payoutEst
        ];


    }
}
