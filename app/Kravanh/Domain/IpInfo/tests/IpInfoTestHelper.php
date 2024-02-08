<?php

namespace App\Kravanh\Domain\IpInfo\tests;

use Illuminate\Support\Facades\Http;

class IpInfoTestHelper
{

    public static function mockHttp(?array $payload = null): void
    {

        Http::fake([
            'https://ipinfo.io/*' => Http::response($payload ?? static::mockPayload())
        ]);
    }

    public static function mockHttpFreeInfo(?array $payload = null): void
    {

        Http::fake([
            'https://ipinfo.io/*' => Http::response($payload ?? static::mockPayloadFree())
        ]);
    }

    public static function mockPayload(
        string $country = 'KH',
        string $ip = '167.179.40.169',
        bool   $vpn = false,
        bool   $proxy = false,
        bool   $tor = false,
        bool   $relay = false,
        bool   $hosting = false
    ): array
    {
        return [
            'ip' => $ip,
            'hostname' => 'ntc.167.179.40.169.neocomisp.com',
            'city' => 'Battambang',
            'region' => 'Battambang',
            'country' => $country,
            'loc' => '3.1027,103.1982',
            'timezone' => 'Asia/Phnom_Penh',
            'asn' => [
                'asn' => 'AS9902',
                'name' => 'NEOCOMISP LIMITED, IPTX Transit and Network Service Provider in Cambodia.',
                'domain' => 'neocomisp.com.kh',
                'route' => '167.179.40.0/24',
                'type' => 'isp'
            ],
            'privacy' => [
                'vpn' => $vpn,
                'proxy' => $proxy,
                'tor' => $tor,
                'relay' => $relay,
                'hosting' => $hosting,
                'service' => ''
            ]
        ];
    }

    public static function mockPayloadFree(
        string $country = 'KH',
        string $ip = '167.179.40.169',
        bool   $vpn = false,
        bool   $proxy = false,
        bool   $tor = false,
        bool   $relay = false,
        bool   $hosting = false
    ): array
    {
        return [
            'ip' => $ip,
            'hostname' => 'ntc.167.179.40.169.neocomisp.com',
            'city' => 'Battambang',
            'region' => 'Battambang',
            'country' => $country,
            'loc' => '3.1027,103.1982',
            'timezone' => 'Asia/Phnom_Penh',
        ];
    }
}
