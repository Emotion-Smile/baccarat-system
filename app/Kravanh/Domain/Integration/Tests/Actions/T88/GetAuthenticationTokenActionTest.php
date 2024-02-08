<?php

use App\Kravanh\Domain\Integration\Actions\T88\GetAuthenticationTokenAction;
use App\Kravanh\Domain\User\Models\MasterAgent;

it('can get authentication token.', function () {
    $token = (new GetAuthenticationTokenAction)(
        MasterAgent::factory()->create()
    );

    expect($token)->toBeString();
})
    ->skip();