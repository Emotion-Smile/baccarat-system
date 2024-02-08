<?php

use App\Kravanh\Domain\IpInfo\Actions\IpInfoCreateAction;
use App\Kravanh\Domain\IpInfo\IpInfoServiceInterface;
use App\Kravanh\Domain\IpInfo\Models\IpInfo;
use App\Kravanh\Domain\IpInfo\tests\IpInfoTestHelper;

test('it can create or update IP info', function () {
    IpInfoTestHelper::mockHttp();

    $info = app(IpInfoServiceInterface::class)->getInfo('167.179.40.169');

    $result = (new IpInfoCreateAction())(1, 'koko', $info);

    $ipInfo = IpInfo::first();

    expect((bool)$result)->toBeTrue()
        ->and(IpInfo::count())->toBe(1)
        ->and($info->ip)->toBe($ipInfo->ip);
});

test('it can create or update IP info not subscript', function () {
    IpInfoTestHelper::mockHttpFreeInfo();

    $info = app(IpInfoServiceInterface::class)->getInfo('167.179.40.169');

    $result = (new IpInfoCreateAction())(1, 'koko', $info);

    $ipInfo = IpInfo::first();

    expect((bool)$result)->toBeTrue()
        ->and(IpInfo::count())->toBe(1)
        ->and($info->ip)->toBe($ipInfo->ip);
});
