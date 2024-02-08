<?php

namespace Tests;

use Bavix\Wallet\Interfaces\Storable;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Spatie\LaravelRay\RayServiceProvider;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, LazilyRefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        app(Storable::class)->fresh();
    }

    protected function getPackageProviders($app): array
    {
        return [
            RayServiceProvider::class,
        ];
    }
}
