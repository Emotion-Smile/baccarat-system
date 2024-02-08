<?php

namespace App\Kravanh\Application\Member\Controllers;

use Inertia\Inertia;
use Inertia\Response;

class VideoHistoryController
{
    public function __invoke(): Response
    {
        return Inertia::render('Member/VideoHistory');
    }
}