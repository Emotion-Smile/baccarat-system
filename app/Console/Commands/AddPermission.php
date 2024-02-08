<?php

namespace App\Console\Commands;

use Database\Seeders\PermissionSingleSeeder;
use Illuminate\Console\Command;

class AddPermission extends Command
{
    protected $signature = 'kravanh:add-permission {object}';

    protected $description = 'Add a single permission object';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $object = $this->argument('object');
        
        (new PermissionSingleSeeder())
            ->callWith(PermissionSingleSeeder::class, ['object' => $object]);

        return Command::SUCCESS;
    }
}
