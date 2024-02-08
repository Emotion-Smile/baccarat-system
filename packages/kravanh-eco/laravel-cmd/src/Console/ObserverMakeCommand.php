<?php

namespace KravanhEco\LaravelCmd\Console;

use Illuminate\Console\GeneratorCommand;

class ObserverMakeCommand extends GeneratorCommand
{
    use CommandManager;

    protected $signature = 'kravanh:observer {name}';

    protected $description = 'Ex. php artisan kravanh:observer Books/Book -> app/Kravanh/Domain/Books/Observers/BookObserver.php';

    protected $type = 'Observer';

    protected $namespace = '\Domain';

    public function getStub(): string
    {
        return __DIR__ . '/../../stubs/observer.stub';
    }
}
