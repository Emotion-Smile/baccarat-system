<?php

namespace KravanhEco\LaravelCmd\Console;

use Illuminate\Foundation\Console\PolicyMakeCommand as command;
use Illuminate\Support\Str;

class PolicyMakeCommand extends command
{
    use CommandManager;

    protected $signature = 'kravanh:policy {name}
                            {--m|model= : The model that the policy applies to}
                            {--g|guard= : The guard that the policy relies on}
                            ';

    protected $description = 'Ex. php artisan kravanh:policy Books/Book -> app/Kravanh/Domain/Books/Policies/BookPolicy.php';

    protected string $suffix = 'Policy';

    protected string $namespace = '\Domain';

    protected function replaceModel($stub, $model): string
    {
        $folder = Str::plural($model);
        $model = "App/Kravanh/Domain/{$folder}/Models/{$model}";

        return parent::replaceModel($stub, $model);
    }

    public function getStub(): string
    {
        return $this->option('model')
            ? __DIR__ . '/../../stubs/policy.stub'
            : $this->resolveStubPath('/stubs/policy.plain.stub') ;

    }
}
