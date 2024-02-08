<?php

namespace App\Console\Commands;

use App\Kravanh\Domain\Match\Models\Matches;
use App\Models\User;
use Bavix\Wallet\Models\Transaction;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Spatie\SimpleExcel\SimpleExcelWriter;

class CheckDuplicatePayout extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-payout';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check Duplicate Payout';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $this->info('start');
//        \cache()->forget('allDepositTransactions');
        $dataForExcel = [];

        $allDepositTransactions = Cache::remember('allDepositTransactions', now()->addDays(10),
            function () {
                ray()->showQueries();
                $tx = Transaction::query()
                    ->selectRaw(
                        'payable_id
                ,COUNT(*) AS tx,
                GROUP_CONCAT(`id`) AS txId'
                    )
                    ->where('created_at', '>', '2022-06-17 14:00')
                    ->where('created_at', '<', '2022-06-17 15:00')
                    ->where('type', 'deposit')
                    ->groupBy('payable_id')
                    ->get();

                ray()->stopShowingQueries();
                return $tx;

            });

        foreach ($allDepositTransactions as $depositTx) {
            //\cache()->forget('depositTransaction' . $depositTx->payable_id);

            $transactions = Cache::remember('depositTransaction' . $depositTx->payable_id,
                now()->addDays(10),
                function () use ($depositTx) {
                    return Transaction::query()
                        ->select([
                            'id',
                            'type',
                            'payable_id',
                            'meta->match_id as match_id',
                            'meta->type as meta_type',
                            'meta->before_balance as before_balance',
                            'amount',
                            'meta->current_balance as current_balance',
                            'created_at'
                        ])
                        ->whereIn('id', explode(',', $depositTx->txId))->get();
                });

            $payoutTx = $transactions->filter(function ($item) {
                return $item->match_id != null;
            });

            $payoutTxDuplicateInMatches = $payoutTx->groupBy('match_id')
                ->filter(function ($item) {
                    if ($item->count() > 1) {
                        ray($item->toArray());
//                        if ($item[0]->current_balance === $item[1]->current_balance) {
//                            return $item;
//                        }
                        return $item;
                    }
                });

            foreach ($payoutTxDuplicateInMatches as $matchId => $duplicateInMatch) {

                $match = Cache::remember(
                    'match-' . $matchId,
                    now()->addDays(10), function () use ($matchId) {
                    $m = Matches::query()->
                    select(['fight_number', 'group_id'])
                        ->find($matchId);
                    $group = ['TV1', 'TV2', '', 'TV3'];
                    return [
                        'group' => $group[$m->group_id - 1],
                        'fight_number' => $m->fight_number
                    ];
                });

                $payout = $duplicateInMatch->first();

                $user = Cache::remember(
                    'user-' . $payout->payable_id,
                    now()->addDays(10),
                    fn() => User::find($payout->payable_id)
                );

                $item = [
                    'match_id' => $matchId,
                    'user_id' => $user->id,
                    'type' => $user->type,
                    'TV' => $match['group'],
                    'fight_number' => $match['fight_number'],
                    'user' => $user->name,
                    'amount' => $payout->amount,
                    'txCount' => $duplicateInMatch->count(),
                ];

                $dataForExcel[] = $item;

//                DuplicatePayout::updateOrCreate(
//                    ['match_id' => $item['match_id'], 'user_id' => $item['user_id']],
//                    [
//                        'match_id' => $item['match_id'],
//                        'user_id' => $item['user_id'],
//                        'group' => $item['TV'],
//                        'user' => $item['user'],
//                        'amount' => $item['amount'],
//                        'tx_count' => $item['txCount'],
//                        'withdraw_amount' => $item['amount'] * ($item['txCount'] - 1),
//                        'already_withdraw' => false,
//                    ]
//                );

            }

        }

//        }


        SimpleExcelWriter::create(storage_path('app') . '/payout_not_increase_balance_g3_fight_14.csv')->addRows($dataForExcel);

        $this->info('success: tx count: ' . $allDepositTransactions->count());

        return 0;
    }
}
