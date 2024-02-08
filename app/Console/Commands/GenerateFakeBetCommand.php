<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Command\Command as CommandAlias;

class GenerateFakeBetCommand extends Command
{

    protected $signature = 'fake:bet';

    protected $description = 'Generate fake bet.';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(): int
    {

        return CommandAlias::SUCCESS;
    }
}
