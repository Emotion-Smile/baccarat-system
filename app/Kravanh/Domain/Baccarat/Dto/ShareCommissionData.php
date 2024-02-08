<?php

namespace App\Kravanh\Domain\Baccarat\Dto;

class ShareCommissionData
{
    public function __construct(public int $share, public float $commission)
    {

    }

    public static function make(int $share, float $commission): ShareCommissionData
    {
        return new ShareCommissionData($share, $commission);
    }
}
