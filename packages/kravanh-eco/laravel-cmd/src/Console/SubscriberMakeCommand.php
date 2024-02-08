<?php

namespace KravanhEco\LaravelCmd\Console;

use Illuminate\Console\GeneratorCommand;

class SubscriberMakeCommand extends GeneratorCommand
{
    use CommandManager;

    protected $signature = 'kravanh:subscriber {name}';

    protected $description = 'Ex. php artisan kravanh:subscriber Invoices/Invoice -> app/Kravanh/Domain/Invoices/Subscribers/InvoiceSubscriber.php';

    protected $type = 'Subscriber';

    protected $namespace = '\Domain';

    public function getStub()
    {
        return __DIR__ . '/../../stubs/subscriber.stub';
    }
}
