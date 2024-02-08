<?php

namespace KravanhEco\Report\Modules\Yuki\DataTransferObjects;

use App\Models\User;
use KravanhEco\Report\Modules\Support\Helpers\MoneyHelper;

class WinLoseItemData
{
    public readonly int $accountId;
    public readonly string $account;
    public readonly string $contact;
    public readonly string $currency;
    public readonly string $userType;

    public readonly float $betAmount;
    public readonly float $validAmount;

    public readonly float $memberWinLose;
    public readonly float $memberCommission;
    public readonly float $memberWinLosePlusCommission;

    public readonly float $currentWinLose;
    public readonly float $currentCommission;
    public readonly float $currentWinLosePlusCommission;

    public readonly float $upLineWinLose;
    public readonly float $upLineCommission;
    public readonly float $upLineWinLosePlusCommission;

    public readonly string $site;
    public readonly string $gameType;
    public readonly string $routeName;

    public function __construct(array $item)
    {
        $currency = $item['user_currency'] ?? 'KHR';

        $user = User::select(['id', 'name', 'phone'])
            ->whereName($item['account_name'])
            ->first();

        $this->accountId = $user->id;
        $this->account = $user->name;
        $this->contact = $user->phone ?? '';
        $this->currency = $currency;
        $this->userType = $item['type'] ?? '';

        $this->betAmount = MoneyHelper::fromAmount($item['bet_amount_value'], $currency)->toKHR();
        $this->validAmount = MoneyHelper::fromAmount($item['valid_amount_value'], $currency)->toKHR();
        
        $this->memberWinLose = MoneyHelper::fromAmount($item['down_line_win_lose_value'], $currency)->toKHR();
        $this->memberCommission = MoneyHelper::fromAmount($item['down_line_commission_value'], $currency)->toKHR();
        $this->memberWinLosePlusCommission = $this->memberWinLose + $this->memberCommission;

        $this->currentWinLose = MoneyHelper::fromAmount($item['mid_line_win_lose_value'], $currency)->toKHR();
        $this->currentCommission = MoneyHelper::fromAmount($item['mid_line_commission_value'], $currency)->toKHR();
        $this->currentWinLosePlusCommission = $this->currentWinLose + $this->currentCommission;

        $this->upLineWinLose = MoneyHelper::fromAmount($item['up_line_win_lose_value'], $currency)->toKHR();
        $this->upLineCommission = MoneyHelper::fromAmount($item['up_line_commission_value'], $currency)->toKHR();
        $this->upLineWinLosePlusCommission = $this->upLineWinLose + $this->upLineCommission;

        $this->site = 'T88';
        $this->gameType = 'Yuki';
        $this->routeName = 'report-t88-win-lose-bet-detail';
    }

    public static function from(array $item): WinLoseItemData
    {
        return new static($item);
    }
}