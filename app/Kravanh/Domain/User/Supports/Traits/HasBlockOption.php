<?php

namespace App\Kravanh\Domain\User\Supports\Traits;

use App\Models\User;
use Illuminate\Support\Facades\Cache;

/**
 * @mixin User
 */
trait HasBlockOption
{

    public function blockVideoStreaming(): void
    {
        Cache::put(User::KEY_BLOCK_VIDEO_STREAM . $this->id, true);
    }

    public function unblockVideoStreaming(): void
    {
        Cache::forget(User::KEY_BLOCK_VIDEO_STREAM . $this->id);
    }

    public function videoIsBlocked(): bool
    {
        return !Cache::has(User::KEY_BLOCK_VIDEO_STREAM . $this->id);
    }

    public function isCreditLessThanMinimumAllowedToViewVideo(): bool
    {
//        return ($this->type->is(UserType::MEMBER) && $this->balanceInt < 10000);
        return false;
    }

    public function balanceIsBlocked(): bool
    {
        return Cache::get(self::BALANCE_BLOCK . $this->id, false);
    }

    public function blockBalance(): void
    {
        Cache::put(self::BALANCE_BLOCK . $this->id, 'block', now()->addMinute());
    }

    public function releaseBalance(): void
    {
        Cache::forget(self::BALANCE_BLOCK . $this->id);
    }

   
}
