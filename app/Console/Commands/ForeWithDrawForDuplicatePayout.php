<?php

namespace App\Console\Commands;

use App\Kravanh\Domain\Match\Models\DuplicatePayout;
use App\Kravanh\Domain\User\Models\Member;
use Illuminate\Console\Command;

class ForeWithDrawForDuplicatePayout extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:force-withdraw-from-duplication-payout';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'app:force-withdraw-from-duplication-payout';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $this->info('start withdraw process');

        $duplicatePayouts = DuplicatePayout::where('already_withdraw', 0)->get();
        $this->info('total records: ' . $duplicatePayouts->count());

        foreach ($duplicatePayouts as $payout) {

            $member = Member::find($payout->user_id);

            $this->info('start withdraw: ' . $member->name . ' amount: ' . $payout->withdraw_amount);

            $member->forceWithdraw($payout->withdraw_amount, [
                'type' => 'force_withdraw',
                'match_id' => $payout->match_id,
                'user_id' => $payout->user_id,
                'remark' => 'duplicate payout'
            ]);

            DuplicatePayout::where('id', $payout->id)
                ->update([
                    'already_withdraw' => true
                ]);

            $this->info('end withdraw');
        }

        $this->info('end withdraw process');

        return 0;
    }
}
