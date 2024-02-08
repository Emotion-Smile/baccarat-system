<?php

namespace App\Kravanh\Domain\WalletBackup\Models;

use Illuminate\Database\Eloquent\Model;

class WalletBackup extends Model
{

    protected $table = 'wallet_backups';
    protected $casts = [
        'is_cache_balance_updated' => 'boolean',
    ];


}
