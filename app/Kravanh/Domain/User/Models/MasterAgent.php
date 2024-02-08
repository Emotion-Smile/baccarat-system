<?php

namespace App\Kravanh\Domain\User\Models;

use App\Models\User;
use Bavix\Wallet\Traits\HasWallet;
use Database\Factories\UserMasterAgentFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MasterAgent extends User
{
    use HasWallet;
    use HasFactory;

    protected $table = 'users';

    protected static function newFactory(): UserMasterAgentFactory|Factory
    {
        return UserMasterAgentFactory::new();
    }
}
