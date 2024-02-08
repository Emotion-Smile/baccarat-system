<?php

namespace KravanhEco\LaravelCmd\Console;

use Illuminate\Console\GeneratorCommand;

class DtoMakeCommand extends GeneratorCommand
{
    use CommandManager;

    protected $signature = 'kravanh:dto {name}';

    protected $description = 'Ex. php artisan kravanh:dto Books/Book -> app/Kravanh/Domain/Books/DataTransferObjects/BookData.php';

    protected $type = 'DataTransferObject';

    protected $suffix = 'Data';

    protected $namespace = '\Domain';

    public function getStub()
    {
        return __DIR__ . '/../../stubs/dto.stub';
    }
}
