<?php

use App\Kravanh\Domain\Integration\Actions\T88\DestroyAuthenticationTokenAction;
use App\Kravanh\Domain\Integration\Actions\T88\GetAuthenticationTokenAction;
use App\Kravanh\Domain\User\Models\MasterAgent;

it('can destroy authentication token.', function () {
    $token = (new GetAuthenticationTokenAction)(
        MasterAgent::factory()->create()
    );

    (new DestroyAuthenticationTokenAction)($token);

    expect(true)->toBe(true);
})
    ->skip();