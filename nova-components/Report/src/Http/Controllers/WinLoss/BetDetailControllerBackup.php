<?php

namespace KravanhEco\Report\Http\Controllers\WinLoss;

use App\Kravanh\Domain\Match\Models\BetRecord;
use App\Kravanh\Domain\Match\Supports\Enums\BetOn;
use App\Kravanh\Domain\Match\Supports\Enums\BetStatus;
use App\Kravanh\Domain\Match\Supports\Enums\MatchResult;
use App\Kravanh\Domain\User\Supports\Enums\UserType;
use Illuminate\Http\JsonResponse;

class BetDetailControllerBackup
{
    public function __invoke(int $memberId): JsonResponse
    {
        return response()
            ->json([
                'reports' => $this->reports($memberId),
                'total' => $this->total($memberId),
            ], 200);
    }

    protected function reports(int $memberId)
    {
        $user = request()->user();

        return BetRecord::whereHas('match')
            ->where('user_id', $memberId)
            ->where('status', BetStatus::ACCEPTED)
            ->when(!in_array($user->type, [UserType::COMPANY, UserType::DEVELOPER]), function ($query) use ($user) {
                $query->where($user->type, $user->id);
            })
            ->when(request('startDate') ?? null, function ($query) {
                $query->whereDate('created_at', '>=', request('startDate'));
            })
            ->when(request('endDate') ?? null, function ($query) {
                $query->whereDate('created_at', '<=', request('endDate'));
            })
            ->orderByDesc('bet_records.id')
            ->paginate(10)
            ->through($this->mapReports());
    }

    protected function mapReports(): \Closure
    {
        return function ($item) {

            $betType = BetOn::fromValue($item->bet_on)->description;
            $betAmount = priceFormat($item->amount, '');

            $result = null;
            $match = $item->match;
            $playerWinLose = 0;

            if ($match->result->is(MatchResult::CANCEL)) {
                $result = 'cancel';
            } else if ($match->result->is(MatchResult::DRAW)) {
                $result = 'draw';
            } else {
                if ($item->bet_on->value == $match->result->value) {
                    $result = 'win';
                    $playerWinLose = priceFormat($item->payout, '');
                } else {
                    $result = 'loss';
                    $playerWinLose = priceFormat(($item->amount * -1), '');
                }
            }

            $previousBalance = priceFormat($item->transaction?->meta['before_balance'] ?? 0, '');
            $currentBalance = $item->transaction?->meta['current_balance'] ?? 0;

            if ($result === 'win') {
                $currentBalance += $item->amount + $item->payout;
            }

            if ($result === 'draw' || $result === 'cancel') {
                $currentBalance += $item->amount;
            }

            $currentBalance = priceFormat($currentBalance, '');
            
            return [
                'betId' => $item->id,
                'userName' => $item->user->name,
                'fightNo' => $match->fight_number,
                'betType' => $betType,
                'result' => $result,
                'previousBalance' => $previousBalance, 
                'betAmount' => $betAmount,
                'currentBalance' => $currentBalance, 
                'playerWinLose' => $playerWinLose,
                'betDate' => $item->created_at->format('d-m-Y H:i:s A'),
            ];
        };
    }

    protected function total(int $memberId)
    {
        $user = request()->user();

        return BetRecord::select([
                \DB::raw('SUM(bet_records.amount) AS bet_amount'),
                \DB::raw('SUM(
                        CASE
                            WHEN matches.result = ' . MatchResult::CANCEL . ' THEN 0
                            WHEN matches.result = ' . MatchResult::DRAW . ' THEN 0
                            WHEN bet_records.bet_on = matches.result THEN bet_records.payout
                            ELSE -(bet_records.amount)
                        END
                    ) AS win_lose'),
            ])
            ->join('matches', 'matches.id', '=', 'bet_records.match_id')
            ->where('bet_records.user_id', $memberId)
            ->where('bet_records.status', BetStatus::ACCEPTED)
            ->when(!in_array($user->type, [UserType::COMPANY, UserType::DEVELOPER]), function ($query) use ($user) {
                $query->where("bet_records.{$user->type}", $user->id);
            })
            ->when(request('startDate') ?? null, function ($query) {
                $query->whereDate('bet_records.created_at', '>=', request('startDate'));
            })
            ->when(request('endDate') ?? null, function ($query) {
                $query->whereDate('bet_records.created_at', '<=', request('endDate'));
            })
            ->get()
            ->map(fn($item) => [
                'bet_amount' => priceFormat($item->bet_amount, ''),
                'win_lose' => priceFormat($item->win_lose, '')
            ])
            ->first();
    }
}
