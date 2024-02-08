<?php

namespace App\Kravanh\Domain\User\Models;

use App\Kravanh\Domain\Match\Supports\Enums\BetType;
use App\Kravanh\Domain\User\Supports\Enums\Status;
use App\Kravanh\Domain\User\Supports\Enums\UserType;
use App\Kravanh\Support\Enums\Currency;
use App\Models\User;
use Bavix\Wallet\Traits\HasWallet;
use Database\Factories\UserMemberFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Member extends User
{
    use HasWallet;
    use HasFactory;

    protected $table = 'users';

    protected $casts = [
        'group_id' => 'integer',
        'email_verified_at' => 'datetime',
        'type' => UserType::class,
        'status' => Status::class,
        'bet_type' => BetType::class,
        'condition' => 'array',
        'internet_betting' => 'boolean',
        'last_login_at' => 'datetime',
        'currency' => Currency::class
    ];

    protected static function newFactory(): UserMemberFactory|Factory
    {
        return UserMemberFactory::new();
    }
}
