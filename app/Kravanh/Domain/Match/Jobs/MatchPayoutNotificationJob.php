<?php

namespace App\Kravanh\Domain\Match\Jobs;

use App\Kravanh\Domain\Environment\Models\Group;
use App\Kravanh\Domain\Match\Events\MatchDepositPayoutCompleted;
use App\Kravanh\Domain\Match\Models\Matches;
use App\Kravanh\Support\External\Telegram\Telegram;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;

class MatchPayoutNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    public function __construct(public array $payload = [])
    {

    }

    public function handle()
    {
        if (empty($this->payload)) {
            return;
        }

        $group = Group::select(['name', 'telegram_chat_id'])->find($this->payload['groupId']);
        $match = Matches::select('total_ticket')->find($this->payload['matchId']);

        $message = "
        Payout Completed:
        Match Id: {$this->payload['matchId']}
        Table: {$group->name}
        Fight Number: {$this->payload['fightNumber']}
        Result: {$this->payload['result']}
        Total User: {$this->payload['totalUser']}
        Total Payout Tickets: {$this->payload['totalPayoutTickets']}
        Duration: {$this->payload['duration']}
        Total Bet Tickets: {$match->total_ticket}
        Action: Deposit Payout
        ";

        //$telegramGroup = ['', -678008851, -765940874, '', -789504633];

        MatchDepositPayoutCompleted::dispatch($this->payload);

        Telegram::send($group->telegram_chat_id)
            ->text($message);
    }
}
