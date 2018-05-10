<?php

namespace App;

chdir(dirname(__DIR__));

require 'config/env.php';
require 'vendor/autoload.php';

use JsonMapper;
use GuzzleHttp\Client;
use GuzzleHttp\Middleware;
use GuzzleHttp\Command\Guzzle\Description;
use GuzzleHttp\Command\Guzzle\GuzzleClient;
use GuzzleHttp\Command\Guzzle\Parameter;
use GuzzleHttp\HandlerStack;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

function auth($apikey, $secret)
{
    return Middleware::mapRequest(function (RequestInterface $request) use ($apikey, $secret) {
        $request = $request->withHeader('key', $apikey);
        $request = $request->withHeader('secret', $secret);
        return $request;
    });
}
function logRequest($logger)
{
    return Middleware::mapRequest(function (RequestInterface $request) use ($logger) {
        $logger->log($request);
        return $request;
    });
}
function logResponse($logger)
{
    return Middleware::mapResponse(function (ResponseInterface $response) use ($logger) {
        $logger->log($response);
        return $response;
    });
}
$logger = new class {
    public function log($entry)
    {
        if ($entry instanceof RequestInterface)
        {
            return $this->logRequest($entry);
        }
        if ($entry instanceof ResponseInterface)
        {
            return $this->logResponse($entry);
        }
        echo $entry;
    }
    private function logRequest(RequestInterface $request)
    {
        echo (string) $request->getMethod() . ' ' . $request->getUri();
        // echo json_encode([
        //     'uri' => (string) $request->getUri(),
        //     // 'body' => $request->getBody(),
        //     // 'headers' => $request->getHeaders(),
        // ]);
        echo PHP_EOL;
    }
    private function logResponse(ResponseInterface $response)
    {
        echo (string) $response->getBody();
        // echo json_encode([
        //     'body' => (string) $response->getBody(),
        //     'headers' => $response->getHeaders(),
        // ]);
        echo PHP_EOL;
    }

};
$stack = HandlerStack::create();
// $stack->push(auth(getenv('PS_APIKEY'), getenv('PS_SECRET')));
$stack->push(logRequest($logger));
$stack->push(logResponse($logger));
$client = new Client([
    // Base URI is used with relative requests
    'base_uri' => 'https://api.pagescreen.io',
    // You can set any number of default request options.
    'timeout' => 2.0,
    'headers' => [
        'Content-Type' => 'application/json',
    ],
    'auth' => [getenv('PS_APIKEY'), getenv('PS_SECRET')],
    'handler' => $stack,
]);

class BaseEntity extends Parameter
{
}

$description = new Description([
    // 'baseUri' => 'http://httpbin.org/',
    'operations' => [
        'capture' => [
            'httpMethod' => 'GET',
            'uri' => '/v1/capture{/ID}.json',
            'responseModel' => 'Model\Capture',
            'parameters' => [
                'ID' => [
                    'type' => 'string',
                    'location' => 'uri'
                ],
            ],
        ],
        'url' => [
            'httpMethod' => 'GET',
            'uri' => '/v1/url{/ID}.json',
            'responseModel' => 'Model\Url',
            'parameters' => [
                'ID' => [
                    'type' => 'string',
                    'location' => 'uri'
                ],
            ],
        ],
    ],
    'models' => [
        'Model\Envelope' => [
            'type' => 'object',
            'properties' => [
                'status' => [
                    'type' => 'int',
                ],
                'requestId' => [
                    'type' => 'string',
                ],
                'data' => [
                    'type' => 'array',
                    'items' => [
                        'type' => 'object',
                    ],
                ],
                'count' => [
                    'type' => 'int',
                ],
            ],
            'additionalProperties' => false,
        ],
        'Model\Capture' => [
            'type' => 'object',
            'extends' => 'Model\Envelope',
            'properties' => [
                'data' => [
                    'location' => 'json',
                    'additionalProperties' => false,
                    'type' => 'array',
                    'items' => [
                        'type' => 'object',
                        'location' => 'json',
                        'additionalProperties' => false,
                        'properties' => [
                            'id' => [
                                'type' => 'string',
                            ],
                            'token' => [
                                'type' => 'string',
                            ],
                            'requested_on' => [
                                'type' => 'string',
                            ],
                            'url' => [
                                'type' => 'object',
                                '$ref' => 'Model\Url',
                            ],
                        ],
                    ],
                ],
            ],
            'additionalProperties' => false,
            // 'additionalProperties' => [
            //     'location' => 'json',
            // ],
        ],
        'Model\Url' => [
            'type' => 'object',
            'extends' => 'Model\Envelope',
            'properties' => [
                'id' => [
                    'type' => 'string',
                    'required' => true,
                ],
                'url' => [
                    'type' => 'string',
                    'required' => true,
                ],
                'title' => [
                    'type' => 'string',
                    'required' => true,
                ],
                'description' => [
                    'type' => 'string',
                    'required' => true,
                ],
            ],
            'additionalProperties' => false,
        ],
    ],
]);

$guzzleClient = new GuzzleClient($client, $description);

// $response = $client->get('/v1/capture.json', [
// 'headers' => [
//     'key' => getenv('PS_APIKEY'),
//     'secret' => getenv('PS_SECRET'),
//     'Content-Type' => 'application/json',
// ],
// ]);
// echo $response->getBody();

$result = $guzzleClient->capture(['ID' => 13854]);
// $result = $guzzleClient->url();
// var_dump($result->toArray());
// $result = $guzzleClient->url(['ID' => 980]);
// $result = $guzzleClient->bin(['foo' => 'bar', 'bar' => 'baz']);
$mapper = new JsonMapper;
$mapper->bExceptionOnUndefinedProperty = true;

var_dump($result->toArray());
class Capture
{
    public $id;
    public $token;
    /**
     * @var Url
     */
    public $url;
    /**
     * @var \DateTime
     */
    public $requested_on;
}
class Url
{
    public $id;
    public $url;
    public $title;
    public $description;
}
$contactObject = $mapper->map(json_decode(json_encode($result->toArray()['data'][0])), new Capture());
// $contactObject = $mapper->map(json_decode(json_encode($result->toArray()['data'][0])), new Capture());
var_dump($contactObject);
// echo json_decode();
