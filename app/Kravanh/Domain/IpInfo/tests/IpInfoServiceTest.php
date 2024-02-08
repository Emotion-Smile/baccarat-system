<?php

use App\Kravanh\Domain\IpInfo\DataTransferObject\IpInfoData;
use App\Kravanh\Domain\IpInfo\IpInfoServiceInterface;
use App\Kravanh\Domain\IpInfo\tests\IpInfoTestHelper;

test('it can get fresh ip info', function () {
    IpInfoTestHelper::mockHttp();
    $payload = IpInfoTestHelper::mockPayload();

    $info = app(IpInfoServiceInterface::class)->getFreshInfo('167.179.40.169');

    expect($info)->toBeInstanceOf(IpInfoData::class)
        ->and($info->ip)->toBe($payload['ip'])
        ->and($info->hostname)->toBe($payload['hostname'])
        ->and($info->city)->toBe($payload['city'])
        ->and($info->region)->toBe($payload['region'])
        ->and($info->country)->toBe($payload['country'])
        ->and($info->loc)->toBe($payload['loc'])
        ->and($info->asn->asn)->toBe($payload['asn']['asn'])
        ->and($info->asn->name)->toBe($payload['asn']['name'])
        ->and($info->asn->domain)->toBe($payload['asn']['domain'])
        ->and($info->asn->route)->toBe($payload['asn']['route'])
        ->and($info->asn->type)->toBe($payload['asn']['type'])
        ->and($info->privacy->vpn)->toBe($payload['privacy']['vpn'])
        ->and($info->privacy->proxy)->toBe($payload['privacy']['proxy'])
        ->and($info->privacy->tor)->toBe($payload['privacy']['tor'])
        ->and($info->privacy->relay)->toBe($payload['privacy']['relay'])
        ->and($info->privacy->hosting)->toBe($payload['privacy']['hosting'])
        ->and($info->privacy->service)->toBe($payload['privacy']['service']);
});

test('it can get fresh ip info free', function () {
    IpInfoTestHelper::mockHttpFreeInfo();
    $payload = IpInfoTestHelper::mockPayloadFree();
    $info = app(IpInfoServiceInterface::class)->getFreshInfo('167.179.40.169');

    expect($info)->toBeInstanceOf(IpInfoData::class)
        ->and($info->ip)->toBe($payload['ip'])
        ->and($info->hostname)->toBe($payload['hostname'])
        ->and($info->city)->toBe($payload['city'])
        ->and($info->region)->toBe($payload['region'])
        ->and($info->country)->toBe($payload['country'])
        ->and($info->loc)->toBe($payload['loc'])
        ->and($info->asn->asn)->toBeNull()
        ->and($info->asn->name)->toBeNull()
        ->and($info->asn->domain)->toBeNull()
        ->and($info->asn->route)->toBeNull()
        ->and($info->asn->type)->toBeNull()
        ->and($info->privacy->vpn)->toBeFalse()
        ->and($info->privacy->proxy)->toBeFalse()
        ->and($info->privacy->tor)->toBeFalse()
        ->and($info->privacy->relay)->toBeFalse()
        ->and($info->privacy->hosting)->toBeFalse()
        ->and($info->privacy->service)->toBeEmpty();
});

test('it can get ip info and cache', function () {
    IpInfoTestHelper::mockHttp();
    $info = app(IpInfoServiceInterface::class)->getInfo('167.179.40.169');

    expect($info)->toBeInstanceOf(IpInfoData::class)
        ->and(Cache::get('ipinfo:167.179.40.169'))->toBeInstanceOf(IpInfoData::class);
});
