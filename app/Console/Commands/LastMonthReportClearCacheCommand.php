<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class LastMonthReportClearCacheCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:last-month-report-clear-cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear cache last month report';

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
    public function handle()
    {
        $keys = Cache::get('last:month:report:cache:keys');
        if (is_null($keys)) {
            $this->info('No key found');
            return 0;
        }

        foreach ($keys as $key) {
            $this->info("forget key: {$key}");
            Cache::forget($key);
        }

        Cache::forget('last:month:report:cache:keys');
        
        return 0;
    }
}
