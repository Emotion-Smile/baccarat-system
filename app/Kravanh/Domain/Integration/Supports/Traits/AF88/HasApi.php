<?php

namespace App\Kravanh\Domain\Integration\Supports\Traits\AF88;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;

trait HasApi 
{
    protected function requestUrl(string $path): string
    {
        return Collection::make([
            $this->baseUrl(),
            $this->apiPrefix(),
            $path
        ])
            ->filter(fn ($path) => $path)
            ->map(fn ($path) => Str::of($path)->trim('/'))
            ->implode('/');
    }

    protected function apiPrefix(): string
    {
        return config('af88.api_prefix');
    }

    protected function baseUrl(): string
    {
        return config('af88.base_url');
    }
}