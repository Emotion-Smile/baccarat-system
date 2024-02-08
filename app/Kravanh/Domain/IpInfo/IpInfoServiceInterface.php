<?php

namespace App\Kravanh\Domain\IpInfo;

use App\Kravanh\Domain\IpInfo\DataTransferObject\IpInfoData;

interface IpInfoServiceInterface
{
    public function getInfo(string $ip): IpInfoData;

    public function getFreshInfo(string $ip): IpInfoData;

    public function record(int $userId, string $name, string $ip): bool;

}

