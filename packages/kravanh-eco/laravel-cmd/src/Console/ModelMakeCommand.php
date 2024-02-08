<?php

namespace KravanhEco\LaravelCmd\Console;

use Illuminate\Foundation\Console\ModelMakeCommand as command;
use Illuminate\Support\Str;

class ModelMakeCommand extends Command
{
    use CommandManager;

    protected $signature = 'kravanh:model {name}
                            {--a|all : Generate a migration, seeder, factory, and resource controller for the model}
                            {--c|controller : Create a new controller for the model}
                            {--f|factory : Create a new factory for the model}
                            {--force : Create the class even if the model already exists}
                            {--m|migration : Create a new migration file for the model}
                            {--s|seed : Create a new seeder file for the model}
                            {--p|pivot : Indicates if the generated model should be a custom intermediate table model}
                            {--r|resource : Indicates if the generated controller should be a resource controller}
                            {--api : Indicates if the generated controller should be an API controller}
                            ';

    protected $description = 'Ex. php artisan kravanh:model Books/Book -> app/Kravanh/Domain/Books/Models/Book.php';

    protected string $suffix = '';

    protected string $namespace = '\Domain';

    protected function createController() : void
    {
        $controller = Str::replace('/Models', '', $this->getNameInput());

        $modelName = $this->qualifyClass($this->getNameInput());

        $this->call('kravanh:controller', array_filter([
            'name'  => "{$controller}",
            '--model' => $this->option('resource') || $this->option('api') ? $modelName : null,
            '--api' => $this->option('api'),
        ]));
    }
}
