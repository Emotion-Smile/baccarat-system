<?php

namespace App\Kravanh\Support;

use App\Kravanh\Domain\IpInfo\DataTransferObject\IpInfoData;
use App\Kravanh\Domain\IpInfo\IpInfoServiceInterface;

class AuthenticationManager
{
    public IpInfoData $info;

    public function __construct(public string $ip)
    {
        $this->info = app(IpInfoServiceInterface::class)->getInfo($this->ip);
    }

    public static function check(string $ip): AuthenticationManager
    {
        return new AuthenticationManager($ip);
    }

    public function blockAll(): void
    {
        $this->blockCountry();
        $this->blockIp();
        $this->blockVPN();
        $this->blockProxy();
        $this->blockTor();
        $this->blockRelay();
        $this->blockHosting();
    }

    public function blockVPN(): static
    {

        abort_if(
            ($this->info->privacy->vpn && appGetSetting('block_vpn', false)),
            404
        );

        return $this;
    }

    public function blockProxy(): static
    {
        abort_if(
            ($this->info->privacy->proxy && appGetSetting('block_proxy', false)),
            404
        );

        return $this;
    }

    public function blockTor(): static
    {
        abort_if(
            ($this->info->privacy->tor && appGetSetting('block_tor', false)),
            404
        );

        return $this;
    }

    public function blockRelay(): static
    {
        abort_if(
            ($this->info->privacy->relay && appGetSetting('block_relay', false)),
            404
        );
        return $this;
    }

    public function blockHosting(): static
    {
        abort_if(
            ($this->info->privacy->hosting && appGetSetting('block_hosting', false)),
            404
        );

        return $this;
    }

    public function blockCountry(): static
    {
        //103.23.133.71 -> cambodia
        //127.0.0.1 -> false
        //206.71.50.230 -> usa
        //101.36.103.255 -> vn
        //1.2.255.255 -> thailand
        $countryCode = $this->info->country;
        abort_if(is_null($countryCode), 500);

        abort_if(
            in_array($countryCode, $this->getBlockCountry()),
            404
        );

        return $this;
    }

    public function blockIp(): static
    {
        abort_if(
            in_array($this->info->ip, $this->getBlockIp()),
            404
        );

        return $this;
    }

    private function getBlockIp(): array
    {
        return explode(',', appGetSetting('block_ip', '127.0.0.1'));
    }

    private function getBlockCountry(): array
    {
        return explode(',', appGetSetting('block_country_code', 'PH'));
    }
}
