<?php

namespace App\Kravanh\Application\Member\Controllers;

use App\Kravanh\Domain\Match\Models\BetRecord;
use App\Kravanh\Support\Enums\Currency;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Inertia\Inertia;
use Inertia\Response;

class BettingHistoryController
{
    public function __invoke(): Response
    {
        return Inertia::render('Member/BettingHistory', [
            'filters' => [
                'status' => request('status')
            ],
            'betTypeFilters' => $this->betTypeFilter(),
            'betHistoryRecords' => $this->betHistories(),
        ]);
    }

    protected function betHistories(): LengthAwarePaginator
    {

        $betHistories = request()
            ->user()
            ->bets()
            ->with(['transaction:id,meta', 'match:id,fight_number,result', 'group:id,name'])
            ->withoutLiveMatch()
            ->byDate(request('date'))
            ->byStatus(request('status'))
            ->orderByDesc('created_at')
            ->paginate(perPage: 4000)
            ->withQueryString();


        return $betHistories
            ->through($this->prepareBetHistories())
            ->through($this->prepareReport($betHistories));

    }

    protected function multipleBetPerMatch($bet, $item, $matches): array
    {
        if ($bet['id'] === $item['id']) {

            $item['header'] = true;

            $totalBetAmount = collect($matches)->sum('bet_amount');
            $betResult = collect($matches)->groupBy('result');

            $win = $this->betResultCalculateAmount($betResult, 'win');
            $draw = $this->betResultCalculateAmount($betResult, 'draw');
            $cancel = $this->betResultCalculateAmount($betResult, 'cancel');

            $totalWinLoss = $win + $draw + $cancel;
            $afterBetBalance = $bet['after_bet_balance'];

            $item['total_win_and_loss_amount'] = priceFormat($totalWinLoss, '');
            $item['before_bet_balance'] = priceFormat($afterBetBalance + $totalBetAmount, '');
            $item['total_bet_amount'] = priceFormat($totalBetAmount, '');
            $item['after_bet_balance'] = priceFormat($afterBetBalance, '');
            $item['current_balance'] = priceFormat($afterBetBalance + $totalWinLoss, '');

        }

        return $item;
    }


    protected function singleBetPerMatch($bet, $item): array
    {
        $item['header'] = true;
        $totalWinLoss = 0;
        $afterBetBalance = $bet['after_bet_balance'];
        $balanceAfterEndMatch = $afterBetBalance;

        if ($bet['result'] === 'win') {
            $totalWinLoss = $bet['total_payout'];
            $balanceAfterEndMatch += $totalWinLoss;
        }

        if (in_array($bet['result'], ['draw', 'cancel'])) {
            $balanceAfterEndMatch += $bet['bet_amount'];
            $totalWinLoss = $bet['bet_amount'];
        }


        $item['total_win_and_loss_amount'] = priceFormat($totalWinLoss, '');
        $item['before_bet_balance'] = priceFormat($afterBetBalance + $bet['bet_amount'], '');
        $item['after_bet_balance'] = priceFormat($afterBetBalance, '');
        $item['total_bet_amount'] = $bet['amount'];
        $item['current_balance'] = priceFormat($balanceAfterEndMatch, '');

        return $item;

    }

    protected function betResultCalculateAmount($betResult, $result)
    {
        if (!isset($betResult[$result])) {
            return 0;
        }
        return collect($betResult[$result])->sum('wn_and_loss_amount');
    }

    protected function betTypeFilter()
    {
        return [
            'loss' => 'Loss',
            'win' => 'Win',
            'cancel' => 'Cancel',
            'draw' => 'Draw',
            'pending' => 'Pending'
        ];
    }

    protected function prepareBetHistories(): \Closure
    {
        /**@var BetRecord $item * */
        return function ($item) {

            $match = $item->match;
            $winAndLossAmountDisplay = '---';
            $winAndLossAmount = $item->amount;
            $status = '---';

            $betResult = strtolower($match->result->key);
            $currency = Currency::fromKey($item['currency']);

            $payout = priceFormat(fromKHRtoCurrency($item->payout, $currency), $currency, false);
            $betAmount = priceFormat(fromKHRtoCurrency($item->amount, $currency), $currency, false);

            if (in_array($betResult, ['meron', 'wala'])) {

                if ($item->bet_on->value == $match->result->value) {
                    $status = $betAmount . " * $item->payout_rate = " . $payout;
                    $winAndLossAmountDisplay = '+' . $payout;
                    $winAndLossAmount = ($item->amount + (int)$item->payout);
                    $betResult = 'win';
                } else {
                    $status = '-' . $betAmount;
                    $betResult = 'loss';
                    $winAndLossAmountDisplay = '-' . $betAmount;
                }

            }

            $afterBetBalance = $item->transaction?->meta['current_balance'] ?? 0;
            $beforeBalance = $item->transaction?->meta['before_balance'] ?? 0;


            return [
                'id' => $item->id,
                'table' => $item->group->name,
                'match_id' => $match->id,
                'before_balance' => priceFormat($beforeBalance, ''),
                'transaction_amount' => $item->amount,
                'before_bet_balance' => 0,
                'after_bet_balance' => $afterBetBalance,
                'current_balance' => 0,
                'fight' => $match->fight_number,
                'date' => $item->bet_date->format('d-m-Y'),
                'time' => $item->bet_time->format(config('kravanh.time_format')),
                'betOn' => [
                    'label' => $item->getBetOnLabel(),
                    'value' => strtolower($item->bet_on->description)
                ],
                'amount' => $betAmount,
                'bet_amount' => $item->amount,
                'total_payout' => ($item->amount + $item->payout),
                'payout' => $item->payout,
                'result' => $betResult,
                'type' => $item->type->description,
                'ticket_status' => $item->status->description,
                'status' => $status,
                'win_and_loss_amount_display' => $winAndLossAmountDisplay,
                'win_and_loss_amount' => $winAndLossAmount,
                'header' => false
            ];
        };
    }

    protected function prepareReport($betHistories): \Closure
    {
        return function ($item) use ($betHistories) {

            $groupBetByMatches = collect($betHistories->items())->groupBy('match_id');
            $matches = $groupBetByMatches[$item['match_id']];
            $totalBetCount = count($matches);
            $firstBet = $matches[0];

            if ($totalBetCount === 1) {
                return $this->singleBetPerMatch($firstBet, $item);
            }

            return $this->multipleBetPerMatch($firstBet, $item, $matches);
        };
    }
}
