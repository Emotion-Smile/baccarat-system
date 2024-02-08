<?php

namespace KravanhEco\LaravelCmd\Console;

use Illuminate\Routing\Console\ControllerMakeCommand as Command;

class ControllerMakeCommand extends Command
{
    use CommandManager;

    protected $namespace = '\Application';

    protected $signature = 'kravanh:controller {name}
                            {--api : Exclude the create and edit methods from the controller.}
                            {--type= : Manually specify the controller stub file to use.}
                            {--force : Create the class even if the controller already exists}
                            {--i|invokable : Generate a single method, invokable controller class.}
                            {--m|model= : Generate a resource controller for the given model.}
                            {--p|parent= : Generate a nested resource controller class.}
                            {--r|resource : Generate a resource controller class.}
                            ';

    protected $description = 'Ex. php artisan kravanh:controller Books/Book -> app/Kravanh/Application/Books/Controllers/BookController.php';
}
