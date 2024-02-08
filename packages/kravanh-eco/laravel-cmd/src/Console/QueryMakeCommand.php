<?php

namespace KravanhEco\LaravelCmd\Console;

use Illuminate\Console\GeneratorCommand;

class QueryMakeCommand extends GeneratorCommand
{
    use CommandManager;

    protected $signature = 'kravanh:query {name}';

    protected $description = 'Ex. php artisan kravanh:query Books/Book -> app/Kravanh/Application/Books/Queries/BookQuery.php';

    protected $type = 'Query';

    protected $namespace = '\Application';

    public function getStub()
    {
        return __DIR__ . '/../../stubs/query.stub';
    }
}
