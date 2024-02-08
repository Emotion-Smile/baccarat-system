<?php

use App\Kravanh\Domain\IpInfo\tests\IpInfoTestHelper;
use App\Kravanh\Support\AuthenticationManager;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

test('it can block user by ip', function () {

    IpInfoTestHelper::mockHttp(
        IpInfoTestHelper::mockPayload(ip: '127.0.0.2')
    );

    Cache::put('app:setting:block_ip', '127.0.0.1,127.0.0.2');

    AuthenticationManager::check('127.0.0.1')->blockIp();

})->expectException(NotFoundHttpException::class);

test('it can block user by ip free info', function () {

    IpInfoTestHelper::mockHttpFreeInfo(
        IpInfoTestHelper::mockPayloadFree(ip: '127.0.0.2')
    );

    Cache::put('app:setting:block_ip', '127.0.0.1,127.0.0.2');

    AuthenticationManager::check('127.0.0.1')->blockIp();

})->expectException(NotFoundHttpException::class);

test('it can block user by country code', function () {

    IpInfoTestHelper::mockHttp(
        IpInfoTestHelper::mockPayload(country: 'PH')
    );

    AuthenticationManager::check('127.0.0.1')->blockCountry();

})->expectException(NotFoundHttpException::class);

test('it can block user by country code free info', function () {

    IpInfoTestHelper::mockHttpFreeInfo(
        IpInfoTestHelper::mockPayloadFree(country: 'PH')
    );

    AuthenticationManager::check('127.0.0.1')->blockCountry();

})->expectException(NotFoundHttpException::class);

test('it can block VPN user', function () {

    IpInfoTestHelper::mockHttp(
        IpInfoTestHelper::mockPayload(vpn: true)
    );

    Cache::put('app:setting:block_vpn', true);

    AuthenticationManager::check('127.0.0.1')->blockVPN();

})->expectException(NotFoundHttpException::class);

test('it can block VPN user free info', function () {

    IpInfoTestHelper::mockHttpFreeInfo(
        IpInfoTestHelper::mockPayloadFree()
    );

    Cache::put('app:setting:block_vpn', true);
    $message = '';
    try {
        AuthenticationManager::check('127.0.0.1')->blockVPN();
    } catch (Exception $e) {
        $message = $e->getMessage();
    } finally {
        expect($message)->toBeEmpty();
    }

});

test('it can block proxy user', function () {

    IpInfoTestHelper::mockHttp(
        IpInfoTestHelper::mockPayload(proxy: true)
    );

    Cache::put('app:setting:block_proxy', true);

    AuthenticationManager::check('127.0.0.1')->blockProxy();

})->expectException(NotFoundHttpException::class);

test('it can block tor user', function () {

    IpInfoTestHelper::mockHttp(
        IpInfoTestHelper::mockPayload(tor: true)
    );

    Cache::put('app:setting:block_tor', true);

    AuthenticationManager::check('127.0.0.1')->blockTor();

})->expectException(NotFoundHttpException::class);


test('it can block relay user', function () {

    IpInfoTestHelper::mockHttp(
        IpInfoTestHelper::mockPayload(relay: true)
    );

    Cache::put('app:setting:block_relay', true);

    AuthenticationManager::check('127.0.0.1')->blockRelay();

})->expectException(NotFoundHttpException::class);

test('it can block hosting user', function () {

    IpInfoTestHelper::mockHttp(
        IpInfoTestHelper::mockPayload(hosting: true)
    );

    Cache::put('app:setting:block_hosting', true);

    AuthenticationManager::check('127.0.0.1')->blockHosting();

})->expectException(NotFoundHttpException::class);
