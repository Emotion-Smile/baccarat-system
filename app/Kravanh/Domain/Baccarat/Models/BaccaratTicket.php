<?php

namespace App\Kravanh\Domain\Baccarat\Models;

use App\Kravanh\Domain\Baccarat\Collections\BaccaratTicketsCollection;
use App\Kravanh\Domain\Baccarat\Database\Factories\BaccaratTicketFactory;
use App\Kravanh\Domain\Baccarat\Support\BaccaratGameWinner;
use App\Kravanh\Domain\Baccarat\Support\BaccaratTicketDisplayFormat;
use App\Kravanh\Domain\Baccarat\Support\QueryBuilders\BaccaratTicketQueryBuilder;
use App\Kravanh\Domain\Baccarat\Support\TicketResult;
use App\Kravanh\Domain\Game\Models\GameTable;
use App\Kravanh\Domain\User\Models\Member;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BaccaratTicket extends Model
{
    use HasFactory;

    protected $table = 'baccarat_tickets';
    protected $guarded = [];

    protected $casts = [
        'commission' => 'array',
        'share' => 'array'
    ];

    public function newEloquentBuilder($query): BaccaratTicketQueryBuilder
    {
        return new BaccaratTicketQueryBuilder($query);
    }

    public function newCollection(array $models = []): BaccaratTicketsCollection
    {
        return new BaccaratTicketsCollection($models);
    }

    protected static function newFactory(): BaccaratTicketFactory
    {
        return BaccaratTicketFactory::new();
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
        return $this->belongsTo(BaccaratGame::class, 'baccarat_game_id', 'id');
    }

    public function gameTable(): BelongsTo
    {
        return $this->belongsTo(GameTable::class, 'game_table_id', 'id')
            ->withDefault([
                'label' => 'No table'
            ]);
    }

    public function format(): BaccaratTicketDisplayFormat
    {
        return BaccaratTicketDisplayFormat::format($this);
    }

    public function isBetOnMain(): bool
    {
        return $this->bet_on === $this->bet_type;
    }

    public function isBetOnPlayerOrBanker(): bool
    {
        return in_array($this->betOnKey(), [BaccaratGameWinner::Player, BaccaratGameWinner::Banker]);
    }

    public function betOn(): string
    {
        return $this->bet_on . '_' . $this->bet_type;
    }

    public function betOnKey(): string
    {
        return match ($this->betOn()) {
            'tie' => BaccaratGameWinner::Tie,
            'player' => BaccaratGameWinner::Player,
            'banker' => BaccaratGameWinner::Banker,
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
        return $this->result() === BaccaratGameWinner::Cancel;
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
