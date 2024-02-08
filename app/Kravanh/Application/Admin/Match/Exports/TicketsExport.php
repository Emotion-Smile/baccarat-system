<?php

namespace App\Kravanh\Application\Admin\Match\Exports;

use App\Kravanh\Domain\Match\Supports\Enums\BetOn;
use App\Kravanh\Support\Enums\Currency;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TicketsExport implements FromCollection, WithHeadings, WithMapping
{
    public function __construct(
        protected Collection $tickets
    ) {}

    public function headings(): array
    {
        return [
            'ID',
            'Group',
            'Fight Number',
            'IP',
            'Time',
            'Member',
            'Bet On',
            'Currency',
            'Amount',
            'Total Payout'
        ];
    }
    
    public function collection()
    {
        return $this->tickets;
    }

    public function map($ticket): array
    {
        $currency = Currency::fromKey($ticket->currency);
        $amount = fromKHRtoCurrency($ticket->amount, $currency);
        $payout = fromKHRtoCurrency($ticket->payout, $currency);

        return [
            'id' => $ticket->id,
            'group' => $ticket->group->name,
            'fightNumber' => $ticket->fight_number,
            'ip' => $ticket->ip,
            'time' => $ticket->bet_time->format(config('kravanh.date_time_format')),
            'member' => $ticket->user->name, 
            'betOn' => BetOn::fromValue($ticket->bet_on)->description,
            'currency' => $ticket->currency,
            'amount' => priceFormat($amount, $currency) . ' x ' . $ticket->payout_rate . ' = ' . priceFormat($payout, $currency),
            'totalPayout' => priceFormat($amount + $payout, $currency)
        ];
    }
}