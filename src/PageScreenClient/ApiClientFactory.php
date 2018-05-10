<?php

namespace PageScreenClient;

use GuzzleHttp\Client;

class ApiClientFactory
{
    public function factory($handler)
    {
        $client = new Client([
            // Base URI is used with relative requests
            'base_uri' => 'https://api.pagescreen.io',
            // You can set any number of default request options.
            'timeout' => 2.0,
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'auth' => [getenv('PS_APIKEY'), getenv('PS_SECRET')],
            'handler' => $handler,
        ]);
        return $client;
    }
}
