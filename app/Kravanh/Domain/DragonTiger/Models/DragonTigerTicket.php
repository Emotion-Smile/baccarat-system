<?php

namespace App\Kravanh\Domain\DragonTiger\Models;

use App\Kravanh\Domain\DragonTiger\Collections\DragonTigerTicketsCollection;
use App\Kravanh\Domain\DragonTiger\Database\Factories\DragonTigerTicketFactory;
use App\Kravanh\Domain\DragonTiger\Support\DragonTigerGameWinner;
use App\Kravanh\Domain\DragonTiger\Support\DragonTigerTicketDisplayFormat;
use App\Kravanh\Domain\DragonTiger\Support\QueryBuilders\DragonTigerTicketQueryBuilder;
use App\Kravanh\Domain\DragonTiger\Support\TicketResult;
use App\Kravanh\Domain\Game\Models\GameTable;
use App\Kravanh\Domain\User\Models\Member;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DragonTigerTicket extends Model
{
    use HasFactory;

    protected $table = 'dragon_tiger_tickets';
    protected $guarded = [];

    protected $casts = [
        'commission' => 'array',
        'share' => 'array'
    ];

    public function newEloquentBuilder($query): DragonTigerTicketQueryBuilder
    {
        return new DragonTigerTicketQueryBuilder($query);
    }

    public function newCollection(array $models = []): DragonTigerTicketsCollection
    {
        return new DragonTigerTicketsCollection($models);
    }

    protected static function newFactory(): DragonTigerTicketFactory
    {
        return DragonTigerTicketFactory::new();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'user_id', 'id');
    }

    public function game(): BelongsTo
    {
        return $this->belongsTo(DragonTigerGame::class, 'dragon_tiger_game_id', 'id');
    }

    public function gameTable(): BelongsTo
    {
        return $this->belongsTo(GameTable::class, 'game_table_id', 'id')
            ->withDefault([
                'label' => 'No table'
            ]);
    }

    public function format(): DragonTigerTicketDisplayFormat
    {
        return DragonTigerTicketDisplayFormat::format($this);
    }

    public function isBetOnMain(): bool
    {
        return $this->bet_on === $this->bet_type;
    }

    public function isBetOnDragonOrTiger(): bool
    {
        return in_array($this->betOnKey(), [DragonTigerGameWinner::Tiger, DragonTigerGameWinner::Dragon]);
    }

    public function betOn(): string
    {
        return $this->bet_on . '_' . $this->bet_type;
    }

    public function betOnKey(): string
    {
        return match ($this->betOn()) {
            'tie_tie' => DragonTigerGameWinner::Tie,
            'dragon_dragon' => DragonTigerGameWinner::Dragon,
            'tiger_tiger' => DragonTigerGameWinner::Tiger,
            default => $this->betOn()
        };
    }

    public function gameNumber(): string
    {
        return $this->game->gameNumber();
    }

    public function result(): string
    {
        return $this->game->ticketResult($this);
    }

    public function isWinning(): bool
    {
        return $this->result() === TicketResult::Win;
    }

    public function isLose(): bool
    {
        return in_array($this->result(), [TicketResult::Lose, TicketResult::LoseHalf]);
    }

    public function isCancel(): bool
    {
        return $this->result() === DragonTigerGameWinner::Cancel;
    }

    public function getAmount()
    {

        if ($this->result() === TicketResult::LoseHalf) {
            return $this->amount / 2;
        }

        return $this->amount;
    }

    public function tableName()
    {
        return $this->gameTable->label;
    }
}
