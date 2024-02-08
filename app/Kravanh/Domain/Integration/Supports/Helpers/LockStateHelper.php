<?php 

namespace App\Kravanh\Domain\Integration\Supports\Helpers;

use Closure;
use Exception;
use Illuminate\Support\Facades\Cache;

class LockStateHelper
{
    public function __construct(
        protected string $key
    )
    {}

    public static function make(string $key): LockStateHelper 
    {
        return new static($key);
    }

    public function wrap(Closure $callback)
    {
        if($this->isProcessing()) {
            throw new Exception('Request is processing.');
        }

        $this->lock();

        return $callback($this);
    }

    public function lock(): bool
    {
        return Cache::forever($this->key, true);
    }

    public function release(): bool
    {
        return Cache::forget($this->key); 
    }

    public function isProcessing(): bool
    {
        return Cache::get($this->key, false); 
    }
}