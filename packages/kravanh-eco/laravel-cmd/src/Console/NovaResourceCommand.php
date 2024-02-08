<?php


namespace KravanhEco\LaravelCmd\Console;


use Laravel\Nova\Console\ResourceCommand;

class NovaResourceCommand extends ResourceCommand
{
    use NovaCommander;

    protected $signature = 'kravanh:nova-resource {name}
                            {--m|model= : The model class being represented.}
                            ';

    protected $description = 'Ex. php artisan kravanh:nova-resource Books/Book';

    protected string $suffix = '';
    
    protected bool $keepOriginalInputValue = true;

    public function getStub(): string
    {
        return __DIR__ . '/../../stubs/resource.stub';
    }
}
