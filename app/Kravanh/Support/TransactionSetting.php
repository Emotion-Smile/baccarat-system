<?php

namespace App\Kravanh\Support;

class TransactionSetting
{
    public static function isDisableMemberBet(): bool
    {
        return appGetSetting('disable_member_bet', false);
    }

    public static function isDisableWithdrawDeposit()
    {
        return appGetSetting('disable_withdraw_deposit', false);
    }
}
