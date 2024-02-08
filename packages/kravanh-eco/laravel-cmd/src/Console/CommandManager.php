<?php

namespace KravanhEco\LaravelCmd\Console;

use ErrorException;
use Illuminate\Support\Str;

trait CommandManager
{
    /**
     * @throws ErrorException
     */
    protected function getDefaultNamespace($rootNamespace): string
    {
        $rootNamespace .= '\Kravanh';

        if (!isset($this->namespace) || empty($this->namespace)) {
            throw new ErrorException('property namespace not found or empty value');
        }

        return $rootNamespace . $this->namespace;
    }

    protected function getNameInput(): string
    {
        return $this->modifyInputCommand(trim($this->argument('name')));
    }

    protected function modifyInputCommand(string $command): string
    {
        if (!$this->isCommandHasModule($command)) {
            return $command;
        }

        if (isset($this->keepOriginalInputValue) &&
            $this->keepOriginalInputValue) {
            return $command;
        }

        return $this->buildNamespaceAndSuffix($command);
    }

    protected function buildNamespaceAndSuffix($command): string
    {
        $namespace = explode('/', $command);
        $lastIndex = count($namespace) - 1;

        $className = Str::ucfirst($namespace[$lastIndex]);

        $namespace[$lastIndex] = Str::plural($this->type);

        if (isset($this->suffix)) {
            $className .= $this->suffix;
        } elseif (!$this->isClassNameHasSuffix($className)) {
            $className .= $this->type;
        }

        $namespace[] = $className;

        return implode('/', $namespace);
    }

    protected function isClassNameHasSuffix($className): bool
    {
        return Str::contains($className, $this->type);
    }

    protected function isCommandHasModule($command): bool
    {
        return Str::contains($command, '/');
    }
}
