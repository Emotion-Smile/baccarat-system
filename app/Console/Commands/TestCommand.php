<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Cache\LockTimeoutException;
use Illuminate\Support\Facades\Cache;

class TestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'f88:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'test execute';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        \cache()->flush();
        Cache::get('Bavix\Wallet\Services\BookkeeperService::9');
        $lock = Cache::lock('koko')->block(1, function () {
        });
        return 0;
    }
}
