<?php

namespace KravanhEco\LaravelCmd\Console;

use Illuminate\Console\GeneratorCommand;

class StateMakeCommand extends GeneratorCommand
{
    use CommandManager;

    protected $signature = 'kravanh:state {name}';

    protected $description = 'Ex. php artisan kravanh:state Invoices/Invoice -> app/Kravanh/Domain/Invoices/States/InvoiceState.php';

    protected $type = 'State';

    protected $namespace = '\Domain';

    public function getStub()
    {
        return __DIR__ . '/../../stubs/state.stub';
    }
}
