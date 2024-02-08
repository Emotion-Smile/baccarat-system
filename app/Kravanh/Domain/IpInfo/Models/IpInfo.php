<?php

namespace App\Kravanh\Domain\IpInfo\Models;

use Illuminate\Database\Eloquent\Model;

class IpInfo extends Model
{
    protected $table = 'ip_infos';
    protected $guarded = [];
    protected $casts = [
        'vpn' => 'boolean',
        'proxy' => 'boolean',
        'tor' => 'boolean',
        'relay' => 'boolean',
        'hosting' => 'boolean',
        'payload' => 'array',
    ];

}
