<?php

namespace App\Kravanh\Domain\IpInfo\DataTransferObject;

class IpInfoData
{
    public function __construct(
        public string $ip,
        public ?string $hostname,
        public ?string $city,
        public ?string $region,
        public ?string $country,
        public ?string $loc,
        public ?string $timezone,
        public IpInfoASNData $asn,
        public IpInfoPrivacyData $privacy,
        public array $payload
    ) {
    }

    public static function from(array $payload): IpInfoData
    {
        return new IpInfoData(
            ip: $payload['ip'],
            hostname: $payload['hostname'] ?? null,
            city: $payload['city'] ?? null,
            region: $payload['region'] ?? null,
            country: $payload['country'],
            loc: $payload['loc'] ?? null,
            timezone: $payload['timezone'] ?? null,
            asn: IpInfoASNData::from($payload['asn'] ?? []),
            privacy: IpInfoPrivacyData::from($payload['privacy'] ?? []),
            payload: $payload
        );
    }

    public function getPrivacy(): ?string
    {
        if ($this->privacy->vpn) {
            return 'VPN';
        }

        if ($this->privacy->proxy) {
            return 'Proxy';
        }

        if ($this->privacy->hosting) {
            return 'Hosting';
        }
        if ($this->privacy->relay) {
            return 'Relay';
        }

        return null;
    }
}
