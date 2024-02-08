<?php


namespace KravanhEco\LaravelCmd\Console;


use Laravel\Nova\Console\ValueCommand;

class NovaValueCommand extends ValueCommand
{
    use NovaCommander;

    protected $signature = 'kravanh:nova-value {name}';

    protected $description = 'Ex. php artisan kravanh:nova-value Books/TotalBook';

    protected string $suffix = 'Value';
}
