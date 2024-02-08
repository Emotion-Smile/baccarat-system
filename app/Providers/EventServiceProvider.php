<?php

namespace App\Providers;

use App\Kravanh\Application\Admin\Setting\NovaSettingObserver;
use App\Kravanh\Domain\DragonTiger\Events\DragonTigerGameCreated;
use App\Kravanh\Domain\DragonTiger\Events\DragonTigerGameResultSubmitted;
use App\Kravanh\Domain\DragonTiger\Subscribers\ChangeCameraPresetNumberAfterGameClosedBetSubscriber;
use App\Kravanh\Domain\DragonTiger\Subscribers\ChangeCameraPresetNumberAfterGameResultSubmittedSubscriber;
use App\Kravanh\Domain\Integration\Subscribers\AutoCreateT88GameCondition;
use App\Kravanh\Domain\Integration\Subscribers\LogoutFromAF88;
use App\Kravanh\Domain\Integration\Subscribers\LogoutFromT88;
use App\Kravanh\Domain\Integration\Subscribers\MakeAsUserOfflineAF88;
use App\Kravanh\Domain\Integration\Subscribers\MakeAsUserOfflineT88;
use App\Kravanh\Domain\Match\Events\MatchEnded;
use App\Kravanh\Domain\Match\Events\MatchResultUpdated;
use App\Kravanh\Domain\Match\Subscribers\DepositPayoutToMember;
use App\Kravanh\Domain\User\Subscribers\CreateLoginHistory;
use App\Kravanh\Domain\User\Subscribers\UpdateLastLoginAt;
use App\Kravanh\Domain\User\Subscribers\UserOffline;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use OptimistDigital\NovaSettings\Models\Settings;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],

        MatchEnded::class => [
            DepositPayoutToMember::class,
        ],

        MatchResultUpdated::class => [
            DepositPayoutToMember::class,
        ],

        DragonTigerGameCreated::class => [
            ChangeCameraPresetNumberAfterGameClosedBetSubscriber::class
        ],

        DragonTigerGameResultSubmitted::class => [
            ChangeCameraPresetNumberAfterGameResultSubmittedSubscriber::class
        ],

        Login::class => [
            CreateLoginHistory::class,
            UpdateLastLoginAt::class,
            AutoCreateT88GameCondition::class,
        ],

        Logout::class => [
            UserOffline::class,
            LogoutFromT88::class,
            LogoutFromAF88::class,
            MakeAsUserOfflineT88::class,
            MakeAsUserOfflineAF88::class,
        ],
    ];

    public function boot(): void
    {
        Settings::observe(NovaSettingObserver::class);
    }
}
