<?php

namespace Tests;

use Bavix\Wallet\Interfaces\Storable;
use Bavix\Wallet\WalletServiceProvider;
use Cerbero\OctaneTestbench\TestsOctaneApplication;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Spatie\LaravelRay\RayServiceProvider;

abstract class TestCaseDatabaseMigration extends BaseTestCase
{
    use DatabaseMigrations, TestsOctaneApplication;

    protected function setUp(): void
    {
        parent::setUp();
        app(Storable::class)->fresh();
    }

    protected function getPackageProviders($app): array
    {
        return [
            RayServiceProvider::class,
            WalletServiceProvider::class,
        ];
    }
}
