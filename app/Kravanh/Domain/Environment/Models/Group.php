<?php

namespace App\Kravanh\Domain\Environment\Models;

use Database\Factories\GroupFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Laravel\Nova\Actions\Actionable;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;

class Group extends Model implements Sortable
{
    use Actionable, HasFactory, SortableTrait;

    protected $guarded = [];

    protected $casts = [
        'meta' => 'array',
        'show_fight_number' => 'boolean'
    ];

    public $sortable = [
        'order_column_name' => 'order',
        'sort_when_creating' => true,
    ];

    public function environment(): BelongsTo
    {
        return $this->belongsTo(Environment::class);
    }

    protected static function newFactory(): GroupFactory
    {
        return GroupFactory::new();
    }


}
