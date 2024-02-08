<?php 

namespace App\Kravanh\Domain\Integration\Supports\Helpers;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class HttpJsonHelper
{
    protected PendingRequest $http;

    public function __construct()
    {
        $this->http = Http::contentType('application/json')->acceptJson();
    }

    public static function prepare()
    {
        return new static();
    }
    
    public function authorization($token, $type = 'Bearer'): HttpJsonHelper 
    {
        $this->http->withToken($token, $type);

        return $this;
    }

    public function get(string $url, $query = null): Response
    {
        return $this->http->get($url, $query);
    }

    public function post(string $url, array $data = []): Response
    {
        return $this->http->post($url, $data);
    }

    public function put(string $url, array $data = []): Response
    {
        return $this->http->put($url, $data);
    }

    public function delete(string $url, array $data = []): Response
    {
        return $this->http->delete($url, $data);
    }
}