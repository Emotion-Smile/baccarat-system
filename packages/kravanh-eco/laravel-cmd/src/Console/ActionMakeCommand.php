<?php

namespace KravanhEco\LaravelCmd\Console;

use Illuminate\Console\GeneratorCommand;

class ActionMakeCommand extends GeneratorCommand
{
    use CommandManager;

    protected $signature = 'kravanh:action {name}';

    protected $description = 'Ex. php artisan kravanh:action Books/CreateBook -> app/Kravanh/Domain/Books/Actions/CreateBookAction.php';

    protected $type = 'Action';

    protected $namespace = '\Domain';

    public function getStub()
    {
        return __DIR__ . '/../../stubs/action.stub';
    }
}
