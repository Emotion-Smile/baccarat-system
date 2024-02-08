<?php

use App\Kravanh\Domain\User\Jobs\UserLoginNotifyJobs;

test('it can send notify when user login', function () {
    (new UserLoginNotifyJobs([
        'privacy' => 'VPN',
        'name' => 'test',
        'country' => 'KH',
        'city' => 'BT',
        'region' => 'BT',
        'ip' => '127.0.0.1'
    ]))->handle();
})->skip();
