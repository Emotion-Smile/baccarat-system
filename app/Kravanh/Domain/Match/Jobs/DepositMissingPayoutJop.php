<?php

namespace App\Kravanh\Domain\Match\Jobs;


use App\Kravanh\Domain\Match\Actions\PayoutDepositorAction;
use App\Kravanh\Domain\User\Models\Member;
use Bavix\Wallet\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Queue\SerializesModels;

class DepositMissingPayoutJop implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(public $match, public $ticket, public $isWin)
    {
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws \Throwable
     */
    public function handle(): void
    {

        $isPaid = $this->isMemberTicketHasPayout($this->ticket['memberId'], $this->match['id']);

        if (!$isPaid) {
            (new PayoutDepositorAction())(
                [$this->ticket],
                $this->matchPayload($this->match),
                $this->isWin
            );
        }

    }

    protected function isMemberTicketHasPayout($memberId, $matchId): bool
    {
        return Transaction::query()
            ->where('payable_type', Member::class)
            ->where('payable_id', $memberId)
            ->where('meta->type', 'payout')
            ->where('meta->match_id', $matchId)
            ->count('payable_id');
    }

    protected function matchPayload($match): array
    {
        $match = (object)$match;

        return [
            'environment_id' => $match->environment_id,
            'group_id' => $match->group_id,
            'match_id' => $match->id,
            'fight_number' => $match->fight_number,
            'result' => $match->result
        ];
    }

    public function middleware(): array
    {
        return [(new WithoutOverlapping("DepositMissingPayoutJop:" . $this->ticket['memberId']))->releaseAfter(5)];
    }
}
