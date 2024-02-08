<?php

namespace App\Kravanh\Domain\Baccarat\App\Member\Controllers;

use Inertia\Inertia;
use Inertia\Response;

class BaccaratGameMemberController
{
    public function __invoke(): Response
    {
        dd('OK');
        return Inertia::render(
            component: 'Baccarat/Member/Betting',
            props: []
        );
    }
}
