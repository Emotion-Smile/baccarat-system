<?php

namespace App\Kravanh\Domain\User\Models;

use App\Models\User;
use Bavix\Wallet\Traits\HasWallet;
use Database\Factories\UserSuperSeniorFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Senior extends User
{
    use HasWallet, HasFactory;

    protected $table = 'users';

    protected static function newFactory(): UserSuperSeniorFactory|Factory
    {
        return UserSuperSeniorFactory::new();
    }

}
