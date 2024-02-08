<?php


namespace KravanhEco\LaravelCmd\Console;


use Laravel\Nova\Console\FilterCommand;

class NovaFilterCommand extends FilterCommand
{
    use NovaCommander;

    protected $signature = 'kravanh:nova-filter {name}
                             {--boolean : Indicates if the generated filter should be a boolean filter}
                             {--date : Indicates if the generated filter should be a date filter}
                            ';

    protected $description = 'Ex. php artisan kravanh:nova-filter Books/Active';

    protected string $suffix = 'Filter';

}
