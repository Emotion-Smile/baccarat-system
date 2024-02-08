<?php


namespace KravanhEco\LaravelCmd\Console;


use Laravel\Nova\Console\PartitionCommand;

class NovaPartitionCommand extends PartitionCommand
{
    use NovaCommander;

    protected $signature = 'kravanh:nova-partition {name}';

    protected $description = 'Ex. php artisan kravanh:nova-partition Books/BookType';

    protected string $suffix = 'Partition';
}
