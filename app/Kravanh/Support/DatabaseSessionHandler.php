<?php

namespace App\Kravanh\Support;

use Illuminate\Session\DatabaseSessionHandler as Session;

class DatabaseSessionHandler extends Session
{
    protected function addRequestInformation(&$payload): DatabaseSessionHandler
    {
        if ($this->container->bound('request')) {
            $payload['ip_address_vapor'] = $this->ipAddressFromVapor();
            $payload['host'] = $this->getHost();
        }

        return parent::addRequestInformation($payload);
    }

    protected function ipAddressFromVapor()
    {
        return $this->container->make('request')->header('x-vapor-source-ip');
    }

    protected function getHost(): string
    {
        return $this->container->make('request')->getHost();
    }


}
