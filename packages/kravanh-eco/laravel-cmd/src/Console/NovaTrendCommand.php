<?php


namespace KravanhEco\LaravelCmd\Console;


use Laravel\Nova\Console\TrendCommand;

class NovaTrendCommand extends TrendCommand
{
    use NovaCommander;

    protected $signature = 'kravanh:nova-trend {name}';

    protected $description = 'Ex. php artisan kravanh:nova-trend Books/BookType';

    protected string $suffix = 'Trend';

}
