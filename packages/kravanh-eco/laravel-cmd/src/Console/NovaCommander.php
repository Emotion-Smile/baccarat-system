<?php


namespace KravanhEco\LaravelCmd\Console;


use Illuminate\Support\Str;

trait NovaCommander
{
    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace . '\Kravanh\Application\Admin';
    }

    protected function getNameInput(): string
    {
        // Example: Appointment/PatientCount -> Appointment/Metrics/PatientCount
        return $this->modifyInputCommand(trim($this->argument('name')));
    }

    protected function modifyInputCommand(string $command): string
    {
        if (!Str::contains($command, '/')) {
            return $command;
        }

        if (isset($this->keepOriginalInputValue) &&
            $this->keepOriginalInputValue) {
            return $command;
        }

        $namespace = explode('/', $command);
        $lastIndex = count($namespace) - 1;

        $className = Str::ucfirst($namespace[$lastIndex]);

        $namespace[$lastIndex] = Str::plural($this->type);

        if (isset($this->suffix)) {
            $className .= $this->suffix;
        }

        $namespace[] = $className;

        return implode('/', $namespace);
    }
}
