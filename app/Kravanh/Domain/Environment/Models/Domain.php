<?php

namespace App\Kravanh\Domain\Environment\Models;

use Illuminate\Database\Eloquent\Model;

class Domain extends Model
{
    protected $casts = [
        'meta' => 'array'
    ];

    public function scopeFindByDomain($query, string $domain): null | Domain
    {
        return $query->whereDomain($domain)->first();
    }
}
