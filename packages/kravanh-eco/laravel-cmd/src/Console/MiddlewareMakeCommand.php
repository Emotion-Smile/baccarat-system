<?php

namespace KravanhEco\LaravelCmd\Console;

use Illuminate\Routing\Console\MiddlewareMakeCommand as Command;

class MiddlewareMakeCommand extends Command
{
    use CommandManager;

    protected $signature = 'kravanh:middleware {name}';

    protected $description = 'Ex. php artisan kravanh:middleware EnsureValidClientId -> app/Kravanh/Support/Middleware/EnsureValidClientId.php';

    protected string $namespace = '\Support\Middleware';

    protected string $suffix = '';

    protected bool $keepOriginalInputValue = true;
}
