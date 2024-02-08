<?php

namespace App\Kravanh\Support;

use Illuminate\Contracts\Cache\Lock;
use Illuminate\Support\Facades\Cache;

class LockHelper
{
    public static function lockWallet(int $userId, int $sec = 10): Lock
    {
        return Cache::store('redis')->lock("lock:balance:$userId", $sec);
    }

    
}
