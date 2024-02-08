<?php

namespace App\Kravanh\Domain\User\Models;

use App\Kravanh\Domain\Environment\Models\Group;
use App\Kravanh\Domain\Game\Models\GameTable;
use App\Kravanh\Domain\Match\Supports\Enums\BetType;
use App\Kravanh\Domain\User\Supports\Enums\Status;
use App\Kravanh\Domain\User\Supports\Enums\UserType;
use App\Kravanh\Support\Enums\Currency;
use App\Models\User;
use Bavix\Wallet\Traits\HasWallet;
use Database\Factories\TraderFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Trader extends User
{
    use HasWallet, HasFactory;

    protected $table = 'users';

    protected $casts = [
        'environment_id' => 'integer',
        'email_verified_at' => 'datetime',
        'group_id' => 'integer',
        'type' => UserType::class,
        'status' => Status::class,
        'bet_type' => BetType::class,
        'condition' => 'array',
        'internet_betting' => 'boolean',
        'last_login_at' => 'datetime',
        'currency' => Currency::class
    ];

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    public function gameTable(): BelongsTo
    {
        return $this->belongsTo(GameTable::class, 'group_id', 'id');
    }

    protected static function newFactory(): TraderFactory
    {
        return TraderFactory::new();
    }
}
