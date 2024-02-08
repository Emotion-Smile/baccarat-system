<?php

namespace KravanhEco\LaravelCmd\Console;

use Illuminate\Console\GeneratorCommand;

class EventMakeCommand extends GeneratorCommand
{
    use CommandManager;

    protected $signature = 'kravanh:event {name}';

    protected $description = 'Ex. php artisan kravanh:event Books/BookSaving -> app/Kravanh/Domain/Books/Events/BookSavingEvent.php';

    protected $type = 'Event';

    protected $namespace = '\Domain';

    public function getStub()
    {
        return __DIR__ . '/../../stubs/event.stub';
    }
}
