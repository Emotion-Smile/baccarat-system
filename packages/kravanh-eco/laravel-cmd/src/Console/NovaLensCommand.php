<?php


namespace KravanhEco\LaravelCmd\Console;


use Laravel\Nova\Console\LensCommand;

class NovaLensCommand extends LensCommand
{
    use NovaCommander;

    protected $signature = 'kravanh:nova-lens {name}';

    protected $description = 'Ex. php artisan kravanh:nova-lens Books/TopBook';

    protected string $suffix = 'Lens';
}
