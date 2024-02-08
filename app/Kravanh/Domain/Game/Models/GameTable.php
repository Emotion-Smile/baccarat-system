<?php

namespace App\Kravanh\Domain\Game\Models;

use App\Kravanh\Domain\Game\Database\Factories\GameTableFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GameTable extends Model
{
    use HasFactory;

    protected $table = 'game_tables';

    protected $guarded = [];


    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class, 'game_id', 'id');
    }

    protected static function newFactory(): GameTableFactory
    {
        return GameTableFactory::new();
    }


}

