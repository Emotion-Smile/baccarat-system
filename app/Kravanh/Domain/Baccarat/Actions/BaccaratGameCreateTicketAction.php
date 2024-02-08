<?php

namespace App\Kravanh\Domain\Baccarat\Actions;

use App\Kravanh\Domain\Baccarat\Dto\BaccaratGameCreateTicketData;
use App\Kravanh\Domain\Baccarat\Models\BaccaratTicket;
use App\Kravanh\Domain\Baccarat\Support\TicketStatus;
use Illuminate\Support\Facades\Date;

final class BaccaratGameCreateTicketAction
{

    public static function from(BaccaratGameCreateTicketData $data): BaccaratTicket
    {
        return (new BaccaratGameCreateTicketAction())($data);
    }


    public function __invoke(BaccaratGameCreateTicketData $data): BaccaratTicket
    {
        $now = Date::now();
        $member = $data->member;

        return BaccaratTicket::create([
            'game_table_id' => $data->gameTableId,
            'dragon_tiger_game_id' => $data->dragonTigerGameId,
            'user_id' => $member->id,
            'super_senior' => $member->super_senior,
            'senior' => $member->senior,
            'master_agent' => $member->master_agent,
            'agent' => $member->agent,
            'amount' => $data->amountKHR(),
            'bet_on' => $data->betOn,
            'bet_type' => $data->betType,
            'payout_rate' => $data->payoutRate,
            'payout' => $data->payout(),
            'status' => TicketStatus::Accepted,
            'share' => $data->share(),
            'commission' => $data->commission(),
            'in_year' => $now->year,
            'in_month' => $now->month,
            'in_day' => $now->format('Ymd'),
            'ip' => $data->ip,
        ]);
    }
}
