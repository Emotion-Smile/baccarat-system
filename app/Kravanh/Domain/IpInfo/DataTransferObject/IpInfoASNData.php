<?php

namespace App\Kravanh\Domain\IpInfo\DataTransferObject;

class IpInfoASNData
{
    public function __construct(
        public ?string $asn,
        public ?string $name,
        public ?string $domain,
        public ?string $route,
        public ?string $type
    )
    {
    }

    public static function from(array $asn): IpInfoASNData
    {
        return new IpInfoASNData(
            asn: $asn['asn'] ?? null,
            name: $asn['name'] ?? null,
            domain: $asn['domain'] ?? null,
            route: $asn['route'] ?? null,
            type: $asn['type'] ?? null
        );
    }
}
