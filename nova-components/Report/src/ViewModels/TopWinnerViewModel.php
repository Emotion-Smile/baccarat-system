<?php

namespace KravanhEco\Report\ViewModels;

use App\Kravanh\Support\Enums\Currency;

class TopWinnerViewModel
{
    protected $topWinner;

    public function __construct($topWinner)
    {
        $this->topWinner = $topWinner; 
    }

    public function getId(): int
    {
        return $this->topWinner->user_id;
    }

    public function getName(): string
    {
        return $this->topWinner->name;
    }

    public function getCurrency(): string
    {
        return $this->topWinner->currency;
    }

    public function getTotalBetAmount(): int|float
    {
        return $this->topWinner->totalBetAmount;
    }

    public function getTotalTicket(): int
    {
        return $this->topWinner->totalTicket;
    }

    public function getWinCount(): int
    {
        return $this->topWinner->winCount;
    }

    public function getAvgAmountPerTicket(): int|float
    {
        return $this->topWinner->avgAmountPerTicket;
    }

    public function getWinRate(): int|float
    {
        return $this->topWinner->winRate;
    }

    public function getTotalBetAmountAsHtml(): string
    {
        return $this->getAmountAsHtml($this->getTotalBetAmount());
    }

    public function getAvgAmountPerTicketAsHtml(): string
    {
        return $this->getAmountAsHtml($this->getAvgAmountPerTicket());
    }

    public function getWinRateAsHtml(): string
    {
        return $this->getRateAsHtml($this->getWinRate());
    }

    protected function getAmountAsHtml($amount): string
    {
        $amount = $this->convertToCurrencyFormat($amount);
        
        return <<<HTML
            <span style="color: #2A69B6;" class="font-bold">{$amount}</span>
        HTML;
    }

    protected function getRateAsHtml($rate): string
    {
        $rate = number_format($rate, 2);

        return <<<HTML
            <span style="color: #EE5246;" class="font-bold">{$rate} %</span>
        HTML;
    }

    protected function convertToCurrencyFormat($amount): string
    {
        $currency = Currency::fromValue($this->getCurrency()); 
        
        return priceFormat(fromKHRtoCurrency($amount, $currency), $currency);
    } 
}