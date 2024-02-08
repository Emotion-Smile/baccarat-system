<?php

namespace KravanhEco\Report\Http\Controllers\TopWinner;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use KravanhEco\Report\ViewModels\TopWinnerViewModel;

class TopWinnerController
{
    public function __invoke(): JsonResponse
    {
        return asJson([
            'reports' => $this->query()->map($this->makeTopWinner())
        ]);
    }

    protected function makeTopWinner()
    {
        return function ($topWinner) {
            $topWinnerViewModel = new TopWinnerViewModel($topWinner);

            return [
                'id' => $topWinnerViewModel->getId(),
                'name' => $topWinnerViewModel->getName(),
                'totalBetAmount' => $topWinnerViewModel->getTotalBetAmountAsHtml(),
                'totalTicket' => $topWinnerViewModel->getTotalTicket(),
                'winCount' => $topWinnerViewModel->getWinCount(),
                'avgAmountPerTicket' => $topWinnerViewModel->getAvgAmountPerTicketAsHtml(),
                'winRate' => $topWinnerViewModel->getWinRateAsHtml(),  
            ];
        };
    }

    protected function query()
    {
        $date = request('date') ?? now()->format('Y-m-d'); 

        return collect(DB::select(
            "SELECT 
                *, 
                CAST(( totalBetAmount / totalTicket ) AS UNSIGNED ) AS avgAmountPerTicket, 
                CAST((( winCount / totalTicket ) * 100 ) AS UNSIGNED ) AS winRate 
            FROM
                (
                SELECT
                    b.`user_id`,
                    u.`name`,
                    u.`currency`,
                    SUM( `amount` ) AS totalBetAmount,
                    COUNT( b.`user_id` ) AS totalTicket,
                    SUM( CASE WHEN b.`bet_on` = m.`result` THEN 1 ELSE 0 END ) AS winCount 
                FROM
                    bet_records AS b
                    LEFT JOIN `users` AS u ON b.`user_id` = u.`id`
                    LEFT JOIN `matches` AS m ON b.`match_id` = m.`id` 
                WHERE
                    b.`bet_date` = '{$date}' 
                    AND m.`result` IN ( 1, 2 ) 
                GROUP BY
                    `user_id` 
                HAVING
                    totalBetAmount > 2000000 
                ) AS userBet 
            HAVING
                winRate > 55 
            ORDER BY
                winRate DESC"
        ));
    }
}
