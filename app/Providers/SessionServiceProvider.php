<?php

namespace App\Providers;

use App\Kravanh\Support\DatabaseSessionHandler;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\ServiceProvider;

class SessionServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Session::extend('kravanh_database_session', function ($app) {
            return new DatabaseSessionHandler(
                $app['db']->connection($app['config']['session.connection']),
                $app['config']['session.table'],
                $app['config']['session.lifetime'],
                $app
            );
        });
    }
}
