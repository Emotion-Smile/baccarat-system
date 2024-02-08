<?php

namespace App\Kravanh\Domain\IpInfo;

use App\Kravanh\Domain\IpInfo\Actions\IpInfoCreateAction;
use App\Kravanh\Domain\IpInfo\DataTransferObject\IpInfoData;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class IpInfoService implements IpInfoServiceInterface
{
    private string $baseUrl = 'https://ipinfo.io/';

    private string $token;

    private string $cacheKey = 'ipinfo:';

    public function __construct(?string $token = null)
    {
        $this->token = $token ?? config('ipinfo.token');
    }

    public function getInfo(string $ip): IpInfoData
    {
        return Cache::remember(
            "$this->cacheKey$ip",
            now()->addMonth(),
            fn () => $this->getFreshInfo($ip)
        );
    }

    protected function buildUrl(string $ip): string
    {
        return $this->baseUrl.$ip.'?token='.$this->token;
    }

    public function getFreshInfo(string $ip): IpInfoData
    {
        return IpInfoData::from(Http::get($this->buildUrl($ip))->json());
    }

    public function record(int $userId, string $name, string $ip): bool
    {
        return (bool) (new IpInfoCreateAction())(
            userId: $userId,
            name: $name,
            info: $this->getInfo($ip)
        );
    }
}
