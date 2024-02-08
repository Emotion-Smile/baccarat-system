<?php

namespace App\Kravanh\Domain\User\Models;

use App\Models\User;
use Bavix\Wallet\Traits\HasWallet;
use Database\Factories\UserAgentFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Agent extends User
{
    use HasWallet;
    use HasFactory;

    protected $table = 'users';


    protected static function newFactory(): UserAgentFactory
    {
        return UserAgentFactory::new();
    }
}
