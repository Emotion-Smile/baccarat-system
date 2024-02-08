<?php

namespace KravanhEco\LaravelCmd\Console;

use Illuminate\Console\GeneratorCommand;

class ViewModelMakeCommand extends GeneratorCommand
{
    use CommandManager;

    protected $signature = 'kravanh:view-model {name}';

    protected $description = 'Ex. php artisan kravanh:view-model Books/Book -> app/Kravanh/Application/Books/ViewModels/BookViewModel.php';

    protected $type = 'ViewModel';

    protected $namespace = '\Application';

    public function getStub()
    {
        return __DIR__ . '/../../stubs/ViewModel.stub';
    }
}
