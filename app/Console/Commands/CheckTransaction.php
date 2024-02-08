<?php

namespace App\Console\Commands;

use App\Kravanh\Domain\Match\Models\Matches;
use App\Models\User;
use Bavix\Wallet\Models\Transaction;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class CheckTransaction extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'f88:tx-check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'check transaction';

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
        //ray()->showCache();
        //\cache()->forget('allTransactions');
        //\cache()->flush();
//        ray()->showQueries();
        $allTransactions = Cache::remember('allTransactions', now()->addHour(), function () {
            return Transaction::query()
                ->selectRaw(
                    'payable_id
                ,COUNT(*) AS tx,
                GROUP_CONCAT(`id`) AS txId'
                )
                ->where('created_at', '>', '2022-06-14 5:00')
                ->where('created_at', '<', '2022-06-14 8:00')
                ->groupBy('payable_id')
                ->get();
        });

        $rows = [];

        foreach ($allTransactions as $tx) {

//            \cache()->forget('tran' . $tx->payable_id);

            $transactions = Cache::remember('tran' . $tx->payable_id,
                now()->addDay(),
                function () use ($tx) {
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
                        ->whereIn('id', explode(',', $tx->txId))->get();
                });


            foreach ($transactions as $index => $transaction) {

                if ($index === 0) {
                    continue;
                }

                $prevIndex = $index - 1;

                $prevTx = $transactions[$prevIndex];

                $previousBeforeBalance = $prevTx->before_balance;

                $previousAmount = $prevTx->amount;

                $previousCurrentBalance = $prevTx->current_balance;

                if ($transaction->before_balance === $previousCurrentBalance) {
                    continue;
                }

                $status = $transaction->before_balance > $previousCurrentBalance ? 'UP' : 'DOWN';
                $upDownAmount = $transaction->before_balance - $previousCurrentBalance;

                $match = [
                    'group' => '',
                    'fight_number' => ''
                ];

                $matchId = $transaction->match_id;

                $user = Cache::remember(
                    'user-' . $transaction->payable_id,
                    now()->addDay(),
                    fn() => User::select(['name', 'type'])->find($transaction->payable_id)
                );

                if ($matchId) {

                    $match = Cache::remember(
                        'match-' . $matchId,
                        now()->addDay(), function () use ($matchId) {
                        $m = Matches::query()->
                        select(['fight_number', 'group_id'])
                            ->find($matchId);
                        $group = ['PitMaster', 'Sabong Express', '', 'Sabong International'];
                        return [
                            'group' => $group[$m->group_id - 1],
                            'fight_number' => $m->fight_number
                        ];
                    });
                }


                $rows[] = [
                    $prevTx->id . '|' . $transaction->id,
                    $user->name,
                    $user->type,
                    $match['group'],
                    $match['fight_number'],
                    $status,
                    $prevTx->type . '|' . $transaction->type ?? 'Company',
                    ($prevTx->meta_type ?? 'Company-' . $prevTx->type) . '|' . ($transaction->meta_type ?? 'Company-' . $transaction->type),
//                    $previousBeforeBalance,
                    $previousAmount,
                    $previousCurrentBalance,
                    $transaction->before_balance,
                    $upDownAmount,
                    $transaction->amount,
                    $transaction->current_balance,
                    $transaction->created_at
                ];
            }
        }

        $this->table(['TxId', 'User', 'UserType', 'Group', 'FightNumber', 'Status', 'Type', 'MetaType', 'pAmount', 'pCBalance', 'BBalance', 'UpDown', 'amount', 'CBalance', 'time'], $rows);
//        ray()->stopShowingQueries();
        return 0;
    }
}
