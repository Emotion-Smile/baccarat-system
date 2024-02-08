<?php

namespace KravanhEco\LaravelCmd\Console;

use Illuminate\Foundation\Console\RequestMakeCommand as Command;

class RequestMakeCommand extends Command
{
    use CommandManager;

    protected $signature = 'kravanh:request {name}';

    protected $description = 'Ex. php artisan kravanh:request Books/Book -> app/Kravanh/Application/Books/Requests/BookRequest.php';

    protected $namespace = '\Application';
}
