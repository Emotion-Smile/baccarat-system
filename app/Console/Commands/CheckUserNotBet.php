<?php

namespace App\Console\Commands;

use App\Kravanh\Domain\Match\Models\Matches;
use App\Kravanh\Domain\User\Events\ForceUserLogout;
use App\Kravanh\Domain\User\Supports\Enums\UserType;
use App\Models\User;
use Illuminate\Console\Command;

class CheckUserNotBet extends Command
{

    protected $signature = 'betting:check-user-not-bet';

    protected $description = 'force logout user if not bet at least 5 matches';

    public function __construct()
    {
        parent::__construct();
    }


    public function handle(): int
    {
        $this->info('CheckUserNotBet');

        User::query()
            ->select(['id', 'name', 'environment_id', 'last_login_at', 'online'])
            ->where('online', 1)
            ->where('allow_stream', 0)
            ->where('type', UserType::MEMBER)
            ->get()
            ->each(function (User $user) {

                $matchLastFightNumber = Matches::lastFightNumber($user->environment_id, $user->group_id);

                if ($matchLastFightNumber === 0) {
                    return;
                }

                $memberLastFightNumber = $user->getLastBetAt()['fight_number'];
                $loginDurationInMinute = $user->last_login_at->diffInMinutes();

                if ($loginDurationInMinute > 30) {
                    $this->forceLogout($user);
                    return;
                }

                if ($memberLastFightNumber === 0 && $loginDurationInMinute > 10) {

                    $this->forceLogout($user);
                    return;
                }

                if (($memberLastFightNumber > $matchLastFightNumber) && $loginDurationInMinute > 10) {
                    $this->forceLogout($user);
                    return;
                }

                $betweenMatch = $matchLastFightNumber - $memberLastFightNumber;

                if ($betweenMatch > 5) {
                    $this->forceLogout($user);
                }

            });

        return Command::SUCCESS;
    }

    protected function forceLogout($user): void
    {
        info("force logout: $user->name");
        $user->online = 0;
        $user->save();

        ForceUserLogout::dispatch($user->environment_id, $user->id);
    }
}
