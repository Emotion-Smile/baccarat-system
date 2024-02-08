<?php

namespace App\Kravanh\Domain\Baccarat\Actions;

use App\Kravanh\Domain\Baccarat\Models\BaccaratTicket;

final class BaccaratGameGetMemberWinLoseTodayForVerifyAction
{
    public function __invoke(int $userId)
    {
        $tickets = BaccaratTicket::query()
            ->leftJoin('dragon_tiger_games', 'dragon_tiger_tickets.dragon_tiger_game_id', 'dragon_tiger_games.id')
            ->where('dragon_tiger_tickets.user_id', $userId)
            ->whereNotNull('dragon_tiger_games.winner')
            ->select([
                'dragon_tiger_tickets.bet_on',
                'dragon_tiger_tickets.bet_type',
                'dragon_tiger_tickets.amount',
                'dragon_tiger_tickets.payout',
                'dragon_tiger_games.winner',
                'dragon_tiger_games.tiger_color',
                'dragon_tiger_games.tiger_range',
                'dragon_tiger_games.dragon_color',
                'dragon_tiger_games.dragon_range'
            ])
            ->get()
            ->map(function ($ticket) {

                $bet = "{$ticket->bet_on}_$ticket->bet_type";

                $tieLoseHalf = ['dragon_dragon', 'tiger_tiger'];

                $gameResults = [
                    "{$ticket->winner}_$ticket->winner",
                    "dragon_$ticket->dragon_color",
                    "dragon_$ticket->dragon_range",
                    "tiger_$ticket->tiger_color",
                    "tiger_$ticket->tiger_range",
                ];


                $ticket->result = 'lose';
                $ticket->loseStatus = 'full';
                $ticket->betOn = $bet;

                if (in_array($bet, $gameResults)) {
                    $ticket->result = 'win';
                }

                if ($ticket->winner === 'tie' && in_array($bet, $tieLoseHalf)) {
                    ray($ticket->result, $ticket);
                    $ticket->loseStatus = 'half';
                }


                return $ticket;
            })->values();

        $win = $tickets->where('result', 'win')->sum('payout');
        //tie result
        $loseFull = $tickets->where('result', 'lose')->where('loseStatus', 'full')->sum('amount');
        $loseHalf = $tickets->where('result', 'lose')->where('loseStatus', 'half')->sum('amount') / 2;

        $totalLose = -($loseFull + $loseHalf);

        $winLose = [
            'confirmTotalBet' => $win + ($loseHalf * 2) + $loseFull,
            'totalBet' => $tickets->sum('amount'),
            'win' => $win,
            'loseHalf' => -$loseHalf,
            'loseFull' => -$loseFull,
            'totalLose' => $totalLose,
            'winLose' => ($win + $totalLose)
        ];

        ray($winLose);

        return $tickets;
    }
}
