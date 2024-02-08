<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SessionClear extends Command
{

    protected $signature = 'f88:session-clear';


    protected $description = 'flush all session';

    public function __construct()
    {
        parent::__construct();
    }


    public function handle()
    {
        // not work
        session()->flush();
        $this->info('session clear');
        return Command::SUCCESS;
    }
}
