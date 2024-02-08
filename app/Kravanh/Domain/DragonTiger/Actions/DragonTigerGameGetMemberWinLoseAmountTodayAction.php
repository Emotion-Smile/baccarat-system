<?php

namespace App\Kravanh\Domain\DragonTiger\Actions;

use App\Kravanh\Domain\DragonTiger\Support\DragonTigerGameWinner;
use Illuminate\Support\Facades\DB;

final class DragonTigerGameGetMemberWinLoseAmountTodayAction
{
    public function __invoke(int $userId): int
    {
        $winLose = DB::select("SELECT
		SUM(
		CASE WHEN CONCAT(t.bet_on, '_', t.bet_type)
		IN(CONCAT(g.winner, '_', g.winner), CONCAT('tiger', '_', g.tiger_color), CONCAT('tiger', '_', g.tiger_range), CONCAT('dragon', '_', g.dragon_color), CONCAT('dragon', '_', g.dragon_range)) THEN
			t.payout
		WHEN ((g.winner = 'tie')
			AND(CONCAT(t.bet_on, '_', t.bet_type)
				IN('dragon_dragon', 'tiger_tiger'))) THEN
			- (t.amount / 2)
		ELSE
			- (t.amount)
		END) win_lose
FROM
	dragon_tiger_tickets AS t
	LEFT JOIN dragon_tiger_games AS g ON t.dragon_tiger_game_id = g.id
WHERE
	t.user_id = ? AND in_day = ?
	AND g.winner IS NOT NULL AND g.winner != ?", [$userId, now()->format('Ymd'), DragonTigerGameWinner::Cancel]);

        return (int)$winLose[0]->win_lose;
    }
}
