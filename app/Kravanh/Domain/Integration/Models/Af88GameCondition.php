<?php

namespace App\Kravanh\Domain\Integration\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Af88GameCondition extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'condition' => 'json',
    ];
}
