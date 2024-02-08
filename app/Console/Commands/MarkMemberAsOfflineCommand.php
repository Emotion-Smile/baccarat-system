<?php

namespace App\Console\Commands;

use App\Kravanh\Domain\User\Events\ForceUserLogout;
use App\Kravanh\Domain\User\Supports\Enums\UserType;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Command\Command as CommandAlias;

class MarkMemberAsOfflineCommand extends Command
{
    protected $signature = 'app:mark-member-as-offline';

    protected $description = 'The command ensure user is offline';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(): int
    {
        if (!appGetSetting('enable_mark_user_as_offline', false)) {
            $this->info('setting mark_user_as_offline not enable');
            return CommandAlias::SUCCESS;
        }

        $this->markAsOffline();
        return CommandAlias::SUCCESS;
    }

    public function markAsOffline()
    {

        User::query()
            ->select(['id'])
            ->where('online', 1)
            ->where('allow_stream', 0)
            ->where('type', UserType::MEMBER)
            ->chunk(200, function ($membersOnline) {
                $ids = [];
                foreach ($membersOnline as $member) {
                    $hour = Date::createFromTimestamp($member->getLastActivity())->diffInHours();

                    $threshold = (int)appGetSetting('offline_hour_threshold', 12);

                    if ($hour > $threshold) {
                        $ids[] = $member->id;
                    }

                }

                if (!empty($ids)) {
                    $inIds = implode(',', $ids);
                    DB::update("UPDATE users SET `online` = 0 WHERE `id` in ({$inIds})", []);
                }

                foreach ($ids as $id) {
                    ForceUserLogout::dispatch(1, $id);
                }

            });

    }
}
