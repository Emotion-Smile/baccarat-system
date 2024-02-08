<?php

namespace App\Kravanh\Domain\DragonTiger\Models;

use App\Kravanh\Domain\DragonTiger\Collections\DragonTigerGameCollection;
use App\Kravanh\Domain\DragonTiger\Database\Factories\DragonTigerGameFactory;
use App\Kravanh\Domain\DragonTiger\Support\DragonTigerCard;
use App\Kravanh\Domain\DragonTiger\Support\DragonTigerGameDisplayFormat;
use App\Kravanh\Domain\DragonTiger\Support\DragonTigerGameWinner;
use App\Kravanh\Domain\DragonTiger\Support\QueryBuilders\DragonTigerGameQueryBuilder;
use App\Kravanh\Domain\DragonTiger\Support\TicketResult;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class DragonTigerGame extends Model
{
    use DragonTigerGameRelationship;
    use HasFactory;

    protected $table = 'dragon_tiger_games';

    protected $guarded = [];

    protected $casts = [
        'started_at' => 'datetime',
        'closed_bet_at' => 'datetime',
        'result_submitted_at' => 'datetime',
    ];

    public function newEloquentBuilder($query): DragonTigerGameQueryBuilder
    {
        return new DragonTigerGameQueryBuilder($query);
    }

    public function newCollection(array $models = []): DragonTigerGameCollection
    {
        return new DragonTigerGameCollection($models);
    }

    protected static function newFactory(): DragonTigerGameFactory
    {
        return DragonTigerGameFactory::new();
    }

    public function format(): DragonTigerGameDisplayFormat
    {
        return DragonTigerGameDisplayFormat::format($this);
    }

    public function gameNumber(): string
    {
        return $this->round.'_'.$this->number;
    }

    public function betStatus(): string
    {
        return $this->canBet() ? 'open' : 'close';
    }

    public function isLive(): bool
    {

        return is_null($this->winner);
        //
        //        return is_null($this->result_submitted_at)
        //            && is_null($this->result_submitted_user_id)
        //            && is_null($this->dragon_result)
        //            && is_null($this->dragon_type)
        //            && is_null($this->tiger_result)
        //            && is_null($this->tiger_type)
        //            && is_null($this->winner);
    }

    public function isCancel(): bool
    {
        return $this->winner === DragonTigerGameWinner::Cancel;
    }

    public function isTie(): bool
    {
        return $this->winner === DragonTigerGameWinner::Tie;
    }

    public function ticketResult(DragonTigerTicket $ticket): string
    {
        if ($this->isLive()) {
            return TicketResult::Pending;
        }

        if ($this->isCancel()) {
            return DragonTigerGameWinner::Cancel;
        }

        $lose = TicketResult::Lose;

        if ($this->isTie() && $ticket->isBetOnDragonOrTiger()) {
            $lose = TicketResult::LoseHalf;
        }

        if ($ticket->isBetOnMain()) {

            if ($this->winner === $ticket->bet_on) {
                return TicketResult::Win;
            }

            return $lose;
        }

        return in_array($ticket->betOn(), $this->makeSubResult())
            ? TicketResult::Win :
            $lose;

    }

    public function canBet(): bool
    {
        if (! $this->isLive()) {
            return false;
        }

        if ($this->isBetClosed()) {
            return false;
        }

        return true;
    }

    public function isBetClosed(): bool
    {
        if (now()->gt($this->closed_bet_at)) {
            return true;
        }

        return false;
    }

    public function bettingInterval(): int
    {
        $interval = $this->closed_bet_at->diffInSeconds(now());

        if (now()->equalTo($this->closed_bet_at) || now()->greaterThan($this->closed_bet_at)) {
            return 0;
        }

        return $interval;
    }

    public function mainResult(): string
    {
        if ($this->isLive()) {
            return '';
        }

        return $this->winner;
    }

    public function subResult()
    {
        return Collection::make($this->makeSubResult())->join(',');
    }

    public function makeSubResult(): array
    {
        if ($this->isLive()) {
            return [];
        }

        if ($this->isCancel()) {
            return [];
        }

        return [
            DragonTigerCard::Dragon.'_'.$this->dragon_color,
            DragonTigerCard::Dragon.'_'.$this->dragon_range,
            DragonTigerCard::Tiger.'_'.$this->tiger_color,
            DragonTigerCard::Tiger.'_'.$this->tiger_range,
        ];
    }
}
