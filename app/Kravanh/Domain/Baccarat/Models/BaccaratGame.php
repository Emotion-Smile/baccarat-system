<?php

namespace App\Kravanh\Domain\Baccarat\Models;

use App\Kravanh\Domain\Baccarat\Collections\BaccaratGameCollection;
use App\Kravanh\Domain\Baccarat\Database\Factories\BaccaratGameFactory;
use App\Kravanh\Domain\Baccarat\Support\BaccaratCard;
use App\Kravanh\Domain\Baccarat\Support\BaccaratGameDisplayFormat;
use App\Kravanh\Domain\Baccarat\Support\BaccaratGameWinner;
use App\Kravanh\Domain\Baccarat\Support\QueryBuilders\BaccaratGameQueryBuilder;
use App\Kravanh\Domain\Baccarat\Support\TicketResult;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class BaccaratGame extends Model
{
    use BaccaratGameRelationship;
    use HasFactory;

    protected $table = 'baccarat_games';

    protected $guarded = [];

    protected $casts = [
        'started_at' => 'datetime',
        'closed_bet_at' => 'datetime',
        'result_submitted_at' => 'datetime',
        'winner' => 'array'
    ];

    public function newEloquentBuilder($query): BaccaratGameQueryBuilder
    {
        return new BaccaratGameQueryBuilder($query);
    }

    public function newCollection(array $models = []): BaccaratGameCollection
    {
        return new BaccaratGameCollection($models);
    }

    protected static function newFactory(): BaccaratGameFactory
    {
        return BaccaratGameFactory::new();
    }

    public function format(): BaccaratGameDisplayFormat
    {
        return BaccaratGameDisplayFormat::format($this);
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

//        return is_null($this->winner);
        return empty($this->winner);
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
//        return $this->winner === BaccaratGameWinner::Cancel;
        return in_array('cancel', (array)$this->winner) == BaccaratGameWinner::Cancel;
    }

    public function isTie(): bool
    {
//        return $this->winner === BaccaratGameWinner::Tie;
        return in_array('tie', (array)$this->winner) == BaccaratGameWinner::Tie;
    }

    // public function ticketResult(DragonTigerTicket $ticket): string
    // {
    //     if ($this->isLive()) {
    //         return TicketResult::Pending;
    //     }

    //     if ($this->isCancel()) {
    //         return DragonTigerGameWinner::Cancel;
    //     }

    //     $lose = TicketResult::Lose;

    //     if ($this->isTie() && $ticket->isBetOnDragonOrTiger()) {
    //         $lose = TicketResult::LoseHalf;
    //     }

    //     if ($ticket->isBetOnMain()) {

    //         if ($this->winner === $ticket->bet_on) {
    //             return TicketResult::Win;
    //         }

    //         return $lose;
    //     }

    //     return in_array($ticket->betOn(), $this->makeSubResult())
    //         ? TicketResult::Win :
    //         $lose;

    // }

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
//        implode(",", $this->winner);
        return $this->winner[0];
//        return $this->winner;
    }

    public function subResult()
    {
        dd(Collection::make($this->makeSubResult())->join(','));
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

        $result = [];

        if ($this->player_points === $this->banker_points) {
            $result = BaccaratCard::Tie;
        }

        if ($this->player_points > $this->banker_points) {
            $result = BaccaratCard::Player;
        }

        if ($this->player_points < $this->banker_points) {
            $result = BaccaratCard::Banker;
        }

        if ($this->banker_first_card_points === $this->banker_second_card_points) {
            $result = BaccaratCard::BankerPair;
        }

        if ($this->player_third_card_points || $this->banker_third_card_points) {
            $result = BaccaratCard::Big;
        }

        if (!$this->player_third_card_points || !$this->banker_third_card_points) {
            $result = BaccaratCard::Small;
        }

//        return $result;

        return [
            $this->player_points > $this->banker_points ? BaccaratCard::Player : '',
            $this->player_points < $this->banker_points ? BaccaratCard::Banker : '',
            $this->player_points == $this->banker_points ? BaccaratCard::Tie : '',
        ];

//        return [
//            BaccaratCard::Player.'_'.$this->player_first_card_color,
//            BaccaratCard::Player.'_'.$this->player_second_card_color,
//            BaccaratCard::Player.'_'.$this->player_third_card_color,
//            BaccaratCard::Player.'_'.$this->player_first_card_points,
//            BaccaratCard::Player.'_'.$this->player_second_card_points,
//            BaccaratCard::Player.'_'.$this->player_third_card_points,
////            BaccaratCard::Dragon.'_'.$this->dragon_range,
//            BaccaratCard::Banker.'_'.$this->banker_first_card_color,
//            BaccaratCard::Banker.'_'.$this->banker_second_card_color,
//            BaccaratCard::Banker.'_'.$this->banker_third_card_color,
//            BaccaratCard::Banker.'_'.$this->banker_first_card_points,
//            BaccaratCard::Banker.'_'.$this->banker_second_card_points,
//            BaccaratCard::Banker.'_'.$this->banker_third_card_points,
////            BaccaratCard::Tiger.'_'.$this->tiger_range,
//        ];
    }
}
