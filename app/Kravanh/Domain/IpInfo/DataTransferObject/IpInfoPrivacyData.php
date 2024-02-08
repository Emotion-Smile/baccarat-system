<?php

namespace App\Kravanh\Domain\IpInfo\DataTransferObject;

class IpInfoPrivacyData
{
    public function __construct(
        public bool   $vpn,
        public bool   $proxy,
        public bool   $tor,
        public bool   $relay,
        public bool   $hosting,
        public string $service
    )
    {
    }

    public static function from(array $privacy): IpInfoPrivacyData
    {
        return new IpInfoPrivacyData(
            vpn: $privacy['vpn'] ?? false,
            proxy: $privacy['proxy'] ?? false,
            tor: $privacy['tor'] ?? false,
            relay: $privacy['relay'] ?? false,
            hosting: $privacy['hosting'] ?? false,
            service: $privacy['service'] ?? false
        );
    }

}
