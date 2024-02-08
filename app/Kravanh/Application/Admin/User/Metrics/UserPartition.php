<?php

namespace App\Kravanh\Application\Admin\User\Metrics;

use App\Models\User;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Partition;

class UserPartition extends Partition
{

    public function calculate(NovaRequest $request): mixed
    {
        //ray(User::groupBy('type')->get('id'));
        ray()->showQueries();
        return $this->count($request, User::class, 'name');
    }


    public function cacheFor()
    {
        // return now()->addMinutes(5);
    }

    public function uriKey(): string
    {
        return 'user-user-partition';
    }
}
