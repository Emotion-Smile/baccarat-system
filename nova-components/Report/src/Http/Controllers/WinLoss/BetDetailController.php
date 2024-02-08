<?php

namespace KravanhEco\Report\Http\Controllers\WinLoss;

use App\Kravanh\Domain\Match\Models\BetRecord;
use App\Kravanh\Domain\User\Models\Member;
use App\Kravanh\Domain\User\Supports\Enums\UserType;
use App\Kravanh\Support\Enums\Currency;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class BetDetailController
{
    public function __invoke(Member $member): JsonResponse
    {
        return response()
            ->json([
                'reports' => $this->reports($member->id),
                'preventUserId' => $this->getDetailPreviousUser($member)?->id,
            ], 200);
    }

    protected function getDetailPreviousUser(User $member): ?User
    {
        $userType = user()->type->value;

        if(user()->isSubAccount()) {
            $userType = user()->getEnsureType(); 
        }
    
        return $userType !== UserType::AGENT
            ? User::find($member->agent)
            : null;
    }

    protected function reports(int $memberId)
    {
        $betHistories = BetRecord::query()
            ->with('group:id,name')
            ->where('user_id', $memberId)
            ->with([
                'transaction:id,meta',
                'user:id,name,currency',
                'match:id,fight_number,result'
            ])
            ->withoutLiveMatch()
            ->byDate(request('date'))
            ->when(request('from') && request('to'), function ($query) {
                $query->whereBetween('bet_date', [
                    request('from'),
                    request('to')
                ]);
            })
            ->orderByDesc('created_at')
            ->paginate(perPage: 1000)
            ->withQueryString();

        return $betHistories
            ->through($this->prepareBetHistories())
            ->through($this->prepareReport($betHistories));
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
        return collect($betResult[$result])->sum('win_and_loss_amount');
    }

    protected function prepareBetHistories(): \Closure
    {

        return function ($item) {

            $match = $item->match;
            $winAndLossAmountDisplay = '---';
            $winAndLossAmount = $item->amount;
            $status = '---';
            $betResult = strtolower($match->result->key);

            $currency = Currency::fromKey($item['currency']);

            $payout = priceFormat(fromKHRtoCurrency($item->payout, $currency), $currency);
            $betAmount = priceFormat(fromKHRtoCurrency($item->amount, $currency), $currency);

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
                'match_id' => $match->id,
                'username' => $item->user->name,
                'before_balance' => priceFormat($beforeBalance, ''),
                'transaction_amount' => $item->amount,
                'before_bet_balance' => 0,
                'after_bet_balance' => $afterBetBalance,
                'current_balance' => 0,
                'fight' => $match->fight_number,
                'date' => $item->bet_date->format('d-m-Y'),
                'time' => $item->bet_time->format(config('kravanh.time_format')),
                'bet' => Str::lower($item->bet_on->description),
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
                'header' => false,
                'group' => $item->group->name
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
}
