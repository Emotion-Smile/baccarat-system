<?php

namespace App\Kravanh\Domain\User\Actions;

use Illuminate\Support\Facades\DB;

final class GetMemberWinLoseTodayAction
{
    public function __invoke(int $memberId): int
    {
        $amount = DB::select("
        select
  SUM(
    CASE
      WHEN matches.result = 4 THEN 0
      WHEN matches.result = 3 THEN 0
      WHEN matches.result = 5 THEN 0
      WHEN matches.result = 0 THEN 0
      WHEN bet_records.bet_on = matches.result THEN bet_records.payout
      ELSE -(bet_records.amount)
    END
  ) AS win_lose
from
  `bet_records`
  inner join `matches` on `matches`.`id` = `bet_records`.`match_id`
where
  `bet_records`.`status` = 1
  AND `bet_records`.`bet_date` = ? AND `bet_records`.`user_id` =?
        ", [today()->toDateString(), $memberId])[0]->win_lose;

        return (int)$amount;
    }
}
