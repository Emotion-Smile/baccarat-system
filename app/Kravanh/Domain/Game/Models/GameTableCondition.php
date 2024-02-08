<?php

namespace App\Kravanh\Domain\Game\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class GameTableCondition extends Model
{
    protected $table = 'game_table_conditions';
    protected $guarded = [];
    public $incrementing = false;


    protected $casts = [
        'is_allowed' => 'bool',
        'share_and_commission' => 'array',
        'bet_condition' => 'array'
    ];

    protected function setKeysForSaveQuery($query): Builder
    {
        return $query
            ->where('game_id', $this->getAttribute('game_id'))
            ->where('game_table_id', $this->getAttribute('game_table_id'))
            ->where('user_id', $this->getAttribute('user_id'));

    }

    protected function setKeysForSelectQuery($query): Builder
    {
        return $this->setKeysForSaveQuery($query);
    }
}
