<?php

namespace KravanhEco\Report\Http\Controllers\MissingPayout;

use App\Kravanh\Domain\User\Supports\Enums\UserType;
use App\Kravanh\Support\Enums\Currency;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class MissingPayoutReportController
{
    public function __invoke(): JsonResponse
    {
        if(!(user()->hasPermission('MissingPayout:view-any') || user()->isRoot())) abort(403);
        
        return response()->json([
            'missingPayouts' => array_map($this->map(), $this->query())
        ]);
    }

    protected function map() 
    {
        return function($item) {
            $currency = Currency::fromKey($item->currency);

            return [
                'matchId' => $item->id,
                'table' => $item->group_name,
                'fightNumber' => $item->fight_number,
                'memberName' => $item->member,
                'betAmount' => priceFormat(fromKHRtoCurrency($item->amount, $currency), $currency),
                'payout' => priceFormat(fromKHRtoCurrency($item->payout, $currency), $currency),
                'depositPrice' => priceFormat(fromKHRtoCurrency($item->deposit, $currency), $currency),
                'depositBack' => $item->deposit,
                'matchDate' => $item->match_date,
                'matchEndAt' => $item->match_end_at,
            ];
        };
    }

    protected function query(): array
    {
        $date = request('date') ?? Carbon::now()->format('Y-m-d');

        return DB::select(
            DB::raw("WITH payouts AS (
                    SELECT
                        matches.`id`,
                        matches.`group_id`,
                        `groups`.`name` as group_name,
                        matches.`fight_number`,
                        users.`name` as member,
                        users.`currency` as currency,
                        bet_records.`user_id`,
                        matches.`match_end_at`,
                        matches.`match_date`,
                        GROUP_CONCAT(bet_records.`id`) AS bet_ids,
                        SUM(bet_records.`amount`) as amount,
                        SUM(bet_records.`payout`) as payout,
                        SUM(amount + payout) as deposit
                    FROM
                        matches
                    LEFT JOIN bet_records ON matches.`id` = bet_records.`match_id`
                    LEFT JOIN users ON bet_records.user_id = users.`id`
                    LEFT JOIN `groups` ON matches.group_id = `groups`.`id`
                    WHERE
                        matches.`result` = bet_records.`bet_on`
                        AND matches.`match_date` = '{$date}'
                        -- AND `groups`.id = 1 
                        -- AND users.`name` = 'khm0059'
                    
                    GROUP BY
                        matches.id,
                        bet_records.user_id
                    HAVING
                        bet_ids IS NOT NULL
                    ORDER BY
                        matches.id DESC
                )
                
                SELECT
                    *
                FROM
                    payouts  
                WHERE
                    NOT EXISTS (
                        SELECT 
                            * 
                        FROM 
                            transactions 
                        WHERE 
                            DATE(transactions.created_at) = '{$date}'
                            AND transactions.type = 'deposit' 
                            AND json_unquote(json_extract(transactions.meta, '$.\"type\"')) = 'payout'
                            AND json_unquote(json_extract(transactions.meta, '$.\"bet_id\"')) = payouts.bet_ids
                    )
                ORDER BY payouts.id DESC 
            ")
        );
    }
}
