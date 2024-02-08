<?php

namespace App\Kravanh\Domain\DragonTiger\Actions;

use App\Kravanh\Domain\DragonTiger\Dto\DragonTigerGameCreateTicketData;
use App\Kravanh\Domain\DragonTiger\Models\DragonTigerTicket;
use App\Kravanh\Domain\DragonTiger\Support\TicketStatus;
use Illuminate\Support\Facades\Date;

final class DragonTigerGameCreateTicketAction
{

    public static function from(DragonTigerGameCreateTicketData $data): DragonTigerTicket
    {
        return (new DragonTigerGameCreateTicketAction())($data);
    }


    public function __invoke(DragonTigerGameCreateTicketData $data): DragonTigerTicket
    {
        $now = Date::now();
        $member = $data->member;

        return DragonTigerTicket::create([
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
