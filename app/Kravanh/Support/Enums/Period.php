<?php

namespace App\Kravanh\Support\Enums;

use BenSampo\Enum\Enum;

class Period extends Enum
{
    const TODAY = 'today';
    const YESTERDAY = 'yesterday';
    const THIS_WEEK = 'this-week';
    const LAST_WEEK = 'last-week';
    const CURRENT_MONTH = 'current-month';
    const LAST_MONTH = 'last-month';

    public static function options(): array
    {
        return [
            'Today' => self::TODAY,
            'Yesterday' => self::YESTERDAY,
            'This Week' => self::THIS_WEEK,
            'Last Week' => self::LAST_MONTH,
            'Current Month' => self::CURRENT_MONTH,
            'Last Month' => self::LAST_MONTH
        ];
    }
}
