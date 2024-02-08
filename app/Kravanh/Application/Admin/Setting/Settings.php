<?php

namespace App\Kravanh\Application\Admin\Setting;

use App\Kravanh\Domain\Camera\App\Nova\Setting as CameraSetting;
use App\Kravanh\Domain\Environment\Models\Group;
use Eminiarts\Tabs\Tabs;
use Illuminate\Support\Arr;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Panel;

class Settings
{
    public static function fields(): array
    {
        $settings = [
            new Tabs('Marquee', self::marqueeFields()),
            self::makeLoginLogoImage(),
            self::makeLoginBackgroundImage(),
            self::transaction(),
            self::makeBetSetting(),
            self::makeReportSetting(),
            self::makeAlertSetting(),
            self::makeUserOfflineSetting(),
            self::blockCountrySetting(),
            self::apiRateLimit(),
            new Tabs('Auto trader', self::autoTraderSetting()),
            new Tabs('Camera Setting', CameraSetting::fields())
        ];

        if (user()->name === 'company_kd') {
            $settings[] = self::traceUsers();
        }
        
        return $settings;

    }

    public static function castsFields(): array
    {
        return [
            'disable_withdraw_deposit' => 'boolean',
            'disable_member_bet' => 'boolean'
        ];
    }

    protected static function transaction(): Panel
    {
        return Panel::make('Transaction', [
            Boolean::make('Disable withdraw/deposit', 'disable_withdraw_deposit'),
            Boolean::make('Disable member bet', 'disable_member_bet')
        ]);
    }

    protected static function traceUsers()
    {

        return Panel::make('Trace Users', [
            Text::make('Users', 'trace_users')
        ]);
    }

    protected static function makeAlertSetting(): Panel
    {
        return Panel::make('Alert', [
            Boolean::make('Login Alert', 'enable_login_alert')->default(false)
        ]);
    }

    protected static function makeBetSetting(): Panel
    {
        return Panel::make('Bet', [
            Number::make('Disable bet threshold amount (KHR)', 'disable_bet_threshold_amount'),
//            Boolean::make('Enable auto drop payout', 'enable_auto_drop_payout')
//                ->default(false),
//            Select::make('Auto drop payout mode')->options([
//                'auto_drop_payout_mode_stand_alone' => 'stand alone',
//                'auto_drop_payout_mode_with_disable_bet' => 'with disable bet'
//            ]),
//            Number::make('Auto drop payout after open bet in sec', 'auto_drop_payout_after_open_bet_in_sec'),
//            Number::make('Drop payout meron', 'drop_meron_amount')->default(0),
//            Number::make('Drop payout wala', 'drop_wala_amount')->default(0)
        ]);
    }

    protected static function makeReportSetting(): Panel
    {
        return Panel::make('Report', [
            Boolean::make('Cache win/lose Current month for company', 'cache_win_lose_current_month'),
            Boolean::make('Cache win/lose Last month', 'cache_win_lose_last_month'),
        ]);
    }

    protected static function makeUserOfflineSetting(): Panel
    {
        return Panel::make('User Offline', [
            Boolean::make('Enable auto mark user as offline', 'enable_mark_user_as_offline'),
            Number::make('Threshold (hour)', 'offline_hour_threshold')
        ]);
    }

    protected static function marqueeFields(): array
    {
        $fields = Group::select(['id', 'name'])
            ->whereActive(true)
            ->orderBy('order')
            ->get()
            ->map(fn($group) => [
                $group->name => [
                    Textarea::make('Text', 'marquee_text_' . $group->id)
                        ->translatable(),

                    Number::make('Speed', 'marquee_speed_' . $group->id)
                        ->help('Default speed 50'),

                    Boolean::make('Active', 'marquee_status_' . $group->id)
                ]
            ])
            ->toArray();

        return Arr::collapse($fields);
    }

    protected static function makeLoginLogoImage(): Panel
    {
        return Panel::make('Login Logo Image', [
            Text::make('Logo', 'login_logo'),
        ]);
    }

    protected static function makeLoginBackgroundImage(): Panel
    {
        return Panel::make('Login Background Image', [
            Text::make('I6', 'login_bg_i6'),
            Text::make('I6P', 'login_bg_i6p'),
            Text::make('Mobile', 'login_bg_mobile'),
            Text::make('Desktop', 'login_bg_desktop'),
        ]);
    }

    protected static function blockCountrySetting()
    {
        return Panel::make('Authentication', [
            Boolean::make('Disable record and notification', 'block_disable_record_and_notification'),
            Text::make('Block Country Code', 'block_country_code'),
            Text::make('Block IP', 'block_ip'),
            Boolean::make('Block VPN', 'block_vpn'),
            Boolean::make('Block Proxy', 'block_proxy'),
            Boolean::make('Block Tor', 'block_tor'),
            Boolean::make('Block Relay', 'block_relay'),
            Boolean::make('Block Hosting', 'block_hosting')
        ]);
    }

    protected static function apiRateLimit()
    {
        return Panel::make('Api rate limit', [
            Number::make('Request per minute', 'api_rate_limit')
        ]);
    }

    protected static function autoTraderSetting(): array
    {
        $fields = Group::select(['id', 'name'])
            ->whereActive(true)
            ->where('use_second_trader', false)
            ->orderBy('order')
            ->get()
            ->map(fn($group) => [
                $group->name => [
                    Text::make('F88 token', 'f88_token_' . $group->id),
                    Text::make('Cf789 token', 'cf789_token_' . $group->id),
                ]
            ])
            ->toArray();

        return Arr::collapse($fields);
    }
}
