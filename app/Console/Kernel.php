<?php

namespace App\Console;

use App\Console\Commands\AddPermission;
use App\Console\Commands\CheckDuplicatePayout;
use App\Console\Commands\CheckTransaction;
use App\Console\Commands\CheckUserNotBet;
use App\Console\Commands\ClearLanguageCache;
use App\Console\Commands\LastMonthReportClearCacheCommand;
use App\Console\Commands\MarkMemberAsOfflineCommand;
use App\Console\Commands\SessionClear;
use App\Console\Commands\TestCommand;
use App\Console\Commands\TodayWinLoseReportNotifyCommand;
use App\Console\Commands\UserCreateTokenCommand;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        CheckUserNotBet::class,
        AddPermission::class,
        ClearLanguageCache::class,
        SessionClear::class,
        MarkMemberAsOfflineCommand::class,
        CheckTransaction::class,
        TestCommand::class,
        CheckDuplicatePayout::class,
        LastMonthReportClearCacheCommand::class,
        TodayWinLoseReportNotifyCommand::class,
        UserCreateTokenCommand::class,
    ];

    protected function schedule(Schedule $schedule): void
    {
        //        $schedule
        //            ->command('betting:check-user-not-bet')
        //            ->everyFiveMinutes();

        $schedule
            ->command('app:today-win-lose-notify')
            ->everyThirtyMinutes();

        $schedule
            ->command('app:mark-member-as-offline')
            ->everyTwoHours();

        //        $schedule
        //            ->command('local:dt:create-new-game')
        //            ->everyMinute();

    }

    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
