<?php

namespace KravanhEco\LaravelCmd\Console;

use Illuminate\Console\GeneratorCommand;

class CollectionMakeCommand extends GeneratorCommand
{
    use CommandManager;

    protected $signature = 'kravanh:collection {name}';

    protected $description = 'Ex. php artisan kravanh:collection Books/Book -> app/Kravanh/Domain/Books/Collections/BookCollection.php';

    protected $type = 'Collection';

    protected $namespace = '\Domain';

    public function getStub()
    {
        return __DIR__ . '/../../stubs/collection.stub';
    }

}
