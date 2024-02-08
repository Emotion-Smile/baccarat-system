<?php

namespace App\Kravanh\Domain\IpInfo\Actions;

use App\Kravanh\Domain\IpInfo\DataTransferObject\IpInfoData;
use App\Kravanh\Domain\IpInfo\Models\IpInfo;

class IpInfoCreateAction
{
    public function __invoke(
        int        $userId,
        string     $name,
        IpInfoData $info,
    ): int
    {

        return IpInfo::upsert([
            [
                'user_id' => $userId,
                'name' => $name,
                'ip' => $info->ip,
                'city' => $info->city,
                'region' => $info->region,
                'country' => $info->country,
                'vpn' => $info->privacy->vpn,
                'proxy' => $info->privacy->proxy,
                'tor' => $info->privacy->tor,
                'relay' => $info->privacy->relay,
                'hosting' => $info->privacy->hosting,
                'payload' => json_encode($info->payload)
            ]
        ], ['ip']);
    }
}
