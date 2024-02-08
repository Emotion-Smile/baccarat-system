<?php

namespace App\Kravanh\Domain\User\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Translatable\HasTranslations;

class Message extends Model
{
    use HasTranslations;

    public $translatable = [
        'message'
    ];

    protected $casts = [
        'message' => 'array',
        'sent_at' => 'datetime',
        'created_at' => 'datetime'
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'message_user', 'message_id', 'user_id');
    }
}
