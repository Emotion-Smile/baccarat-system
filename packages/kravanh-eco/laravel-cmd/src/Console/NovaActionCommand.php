<?php


namespace KravanhEco\LaravelCmd\Console;


use Laravel\Nova\Console\ActionCommand;

class NovaActionCommand extends ActionCommand
{
    use NovaCommander;

    protected $signature = 'kravanh:nova-action {name}
                             {--destructive : Indicate that the action deletes / destroys resources}
                            ';

    protected $description = 'Ex. php artisan kravanh:nova-action Books/Publish';

    protected string $suffix = 'Action';


}
