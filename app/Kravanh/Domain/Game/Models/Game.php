<?php

namespace App\Kravanh\Domain\Game\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Game extends Model
{
    protected $table = 'games';
    protected $guarded = [];

    public function table(array $columns = ['*']): HasMany
    {
        return $this->hasMany(GameTable::class, 'game_id', 'id')->select($columns);
    }

    public function firstTable(array $columns = ['*']): Model
    {
        return $this->table($columns)->firstOrFail();
    }

    public function firstTableId()
    {
        return $this->firstTable(columns: ['id'])->id;
    }
}
