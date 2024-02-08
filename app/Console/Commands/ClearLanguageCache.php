<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\Console\Command\Command as CommandAlias;

class ClearLanguageCache extends Command
{

    protected $signature = 'f88:clear-language';

    protected $description = 'clear language cache';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(): int
    {

        //Cache::remember('lang:translate:' . $locale
        Cache::forget('lang:translate:en');
        Cache::forget('lang:translate:km');
        Cache::forget('lang:translate:th');
        Cache::forget('lang:translate:vi');
        $this->info('language clean');
        return CommandAlias::SUCCESS;
    }
}
