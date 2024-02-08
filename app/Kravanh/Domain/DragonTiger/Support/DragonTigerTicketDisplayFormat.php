<?php

namespace App\Kravanh\Domain\DragonTiger\Support;

use App\Kravanh\Domain\DragonTiger\Models\DragonTigerTicket;
use App\Kravanh\Support\Enums\Currency;
use Illuminate\Support\Str;

final class DragonTigerTicketDisplayFormat
{

    public function __construct(
        public readonly DragonTigerTicket $ticket
    )
    {
    }

    public static function format(DragonTigerTicket $ticket): DragonTigerTicketDisplayFormat
    {
        return new DragonTigerTicketDisplayFormat($ticket);
    }

    public function bet(): string
    {
        if ($this->ticket->isBetOnMain()) {
            return Str::title($this->ticket->bet_on);
        }

        return Str::title($this->ticket->bet_on . ' ' . $this->ticket->bet_type);
    }

    private function currency(): Currency
    {
        return $this->ticket->user?->currency ?? Currency::fromKey('KHR');
    }

    public function amount(): string
    {
        return $this->amountFormat($this->ticket->getAmount());
    }

    public function payout(): string
    {
        return $this->amountFormat($this->ticket->payout);
    }

    public function amountFormat(float|int $amount): string
    {
        return priceFormat(fromKHRtoCurrency($amount, $this->currency()), $this->currency());
    }

    public function gameResult(): ?string
    {
        if ($this->ticket->isCancel()) {
            return $this->p(text: DragonTigerGameWinner::Cancel, color: 'gray');
        }

        $subResults = "";
        $mainColor = (Str::lower($this->bet()) === Str::lower($this->ticket->game->mainResult())) ? '#840bbb' : '';

        foreach ($this->ticket->game->makeSubResult() as $result) {
            $subResult = Str::of($result)->replace('_', ' ')->title();
            $subResults .= $this->p($subResult, ($subResult == $this->bet()) ? '#840bbb' : '');
        }

        return <<<HTML
                {$this->p($this->ticket->game->mainResult(), $mainColor)}
                $subResults
               HTML;
    }

    public function gameNumber(): ?string
    {
        return $this->ticket->game?->gameNumber();
    }

    public function member(): ?string
    {
        return $this->ticket->user?->name;
    }

    public function table(): ?string
    {
        return $this->ticket->gameTable?->label;
    }

    public function betAmountAsHtml(): string
    {
        return $this->p($this->amountFormat($this->ticket->amount));
    }

    public function outstandingAmountAsHtml(): string
    {
        return $this->p("{$this->amount()} * {$this->ticket->payout_rate} = {$this->payout()}");
    }

    public function ipAsHtml(): string
    {
        return <<<HTML
                  <a target="_blank" href="https://ip-api.com/#{$this->ticket->ip}">{$this->ticket->ip}</a>
               HTML;
    }


    public function winLoseAsHtml(): string
    {
        return $this->p(
            text: $this->winLose(),
            color: $this->ticketColor()
        );
    }

    public function ticketColor(): string
    {
        return TicketResult::color(result: $this->ticket->result());
    }

    public function winLose(): string
    {
        if ($this->ticket->isCancel()) {
            return DragonTigerGameWinner::Cancel;
        }

        if ($this->ticket->isLose()) {
            return "-{$this->amount()}";
        }

        return "{$this->amount()} * {$this->ticket->payout_rate} = {$this->payout()}";
    }

    public function betAsHtml(): string
    {
        return $this->p($this->bet());
    }

    public function resultAsHtml(): string
    {
        $result = $this->ticket->result();
        $color = TicketResult::color($result);

        return $this->p(
            text: $result,
            color: $color
        );

    }

    public function time(): string
    {
        return $this->ticket->created_at->format(config('kravanh.time_format'));
    }

    public function dateTime(): string
    {
        return $this->ticket->created_at->format(config('kravanh.date_time_format'));
    }

    protected function p(string $text, string $color = ''): string
    {
        return <<<HTML
                  <p class="whitespace-no-wrap font-semibold capitalize p-1" style="color: {$color}">{$text}</p>
               HTML;
    }

}
