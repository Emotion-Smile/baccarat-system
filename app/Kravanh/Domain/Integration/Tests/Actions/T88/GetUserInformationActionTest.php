<?php

use App\Kravanh\Domain\Integration\Actions\T88\GetAuthenticationTokenAction;
use App\Kravanh\Domain\Integration\Actions\T88\GetUserInformationAction;
use App\Kravanh\Domain\User\Models\MasterAgent;

it('can get user information.', function () {
    $token = (new GetAuthenticationTokenAction)(
        MasterAgent::factory()->create()
    );

    $userInformation = (new GetUserInformationAction)($token);

    expect($userInformation)->not()->toBe([]);
})
    ->skip();