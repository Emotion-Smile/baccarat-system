<?php

namespace KravanhEco\LaravelCmd\Console;

use Illuminate\Console\GeneratorCommand;

class QueryBuilderMakeCommand extends GeneratorCommand
{
    use CommandManager;

    protected $signature = 'kravanh:query-builder {name}';

    protected $description = 'Ex. php artisan kravanh:query-builder Books/Book -> app/Kravanh/Domain/Books/QueryBuilders/BookQueryBuilder.php';

    protected $type = 'QueryBuilder';

    protected $namespace = '\Domain';

    public function getStub()
    {
        return __DIR__ . '/../../stubs/QueryBuilder.stub';
    }
}
