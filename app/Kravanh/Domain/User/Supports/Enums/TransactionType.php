<?php

namespace App\Kravanh\Domain\User\Supports\Enums;

use BenSampo\Enum\Enum;

final class TransactionType extends Enum
{
    const DEPOSIT = 'deposit';
    const WITHDRAW = 'withdraw';

    public static function list()
    {
        return [
            self::DEPOSIT => 'Deposit',
            self::WITHDRAW => 'Withdraw',
        ];
    }
}